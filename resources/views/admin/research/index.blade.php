@extends('admin.pages.master')
@section('title', 'Edit R&D Page')
@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Edit R&D Page</h4>
                    <small class="text-muted">Auto-translates on save</small>
                </div>
                <div class="card-body">
                    <form id="createThisForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="codeid" name="codeid" value="{{ $data->id }}">

                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="alert alert-info py-2 mb-3">
                                    <i class="ri-translate-2 me-1"></i> 
                                    <small>Fill in English — will be auto-translated to {{ implode(', ', array_diff(config('translatable.locales'), ['en'])) }}</small>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Small Title (EN)</label>
                                <input type="text" class="form-control" name="name" value="{{ $data->name }}">
                            </div>

                            <div class="col-md-8">
                                <label class="form-label">Header Title (EN)</label>
                                <input type="text" class="form-control" name="short_title" value="{{ $data->short_title }}">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Header Subtitle (EN)</label>
                                <input type="text" class="form-control" name="long_title" value="{{ $data->long_title }}">
                            </div>

                            {{-- Counters --}}
                            <div class="col-md-12">
                                <label class="form-label"><b>Counters (EN)</b></label>
                                <div id="counters-container">
                                    @php $amenities = json_decode($data->extra1, true) ?: [[]]; @endphp
                                    @foreach($amenities as $i => $item)
                                        <div class="row g-2 mb-2 button-row">
                                            <div class="col-md-5">
                                                <input type="text" name="counters[{{ $i }}][count]" class="form-control" value="{{ $item['count'] ?? '' }}" placeholder="Number (e.g. 500+)">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" name="counters[{{ $i }}][subtitle]" class="form-control" value="{{ $item['subtitle'] ?? '' }}" placeholder="Sub Title">
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-danger w-100 remove-btn">X</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-dark btn-sm mt-2" id="addCounterBtn">+ Add Counter</button>
                            </div>

                            <hr>

                            <div class="col-12">
                                <label>Meta Title</label>
                                <input type="text" class="form-control" name="meta_title" value="{{ $data->meta_title }}">
                            </div>
                            <div class="col-12">
                                <label>Meta Description</label>
                                <textarea class="form-control" name="meta_description">{{ $data->meta_description }}</textarea>
                            </div>
                            <div class="col-12">
                                <label>Meta Keywords</label>
                                <input type="text" class="form-control" name="meta_keywords" value="{{ $data->meta_keywords }}">
                            </div>
                            <div class="col-12">
                                <label>Meta Image</label>
                                <input type="file" class="form-control" name="meta_image" onchange="previewImage(event, '#meta_image_preview')">
                                @if($data->meta_image)
                                    <img id="meta_image_preview" src="{{ asset('images/meta/' . $data->meta_image) }}" class="img-thumbnail mt-3" style="max-width:200px;">
                                @else
                                    <img id="meta_image_preview" src="#" class="img-thumbnail mt-3 d-none">
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-end">
                    <button type="button" id="addBtn" class="btn btn-primary">
                        <i class="ri-save-line me-1"></i> Update & Translate
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<style>
    .card { position: relative; overflow: hidden; }
    .form-loader-overlay {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(2px);
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        z-index: 50; border-radius: 0.375rem;
    }
    .spinner-ring { width: 50px; height: 50px; border: 4px solid #e5e7eb; border-top: 4px solid #3b82f6; border-radius: 50%; animation: spin 0.8s linear infinite; }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    .loader-text { margin-top: 15px; font-size: 14px; color: #374151; font-weight: 500; }
    .loader-lang-ticker { margin-top: 8px; font-size: 12px; color: #6b7280; min-height: 18px; }
    .progress-bar-container { width: 200px; height: 4px; background: #e5e7eb; border-radius: 4px; margin-top: 12px; overflow: hidden; }
    .progress-bar-fill { height: 100%; background: #3b82f6; border-radius: 4px; width: 0%; transition: width 0.3s ease; }
</style>

<script>
    var otherLocales = @json(array_values(array_diff(config('translatable.locales'), ['en'])));
    var counterIndex = {{ max(count(json_decode($data->extra1 ?? '[]', true) ?? []), 1) }};

    $(document).ready(function () {
        $("#addBtn").click(function (e) {
            e.preventDefault();
            var formData = new FormData(document.getElementById('createThisForm'));
            showFormLoader();

            $.ajax({
                url: "{{ URL::to('/admin/research') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (d) { hideFormLoader(); showSuccess(d.message); },
                error: function (xhr) { hideFormLoader(); showError(xhr.responseJSON?.message ?? 'Something went wrong.'); }
            });
        });

        $("#addCounterBtn").click(function () {
            var idx = counterIndex++;
            var html = `<div class="row g-2 mb-2 button-row">
                <div class="col-md-5"><input type="text" name="counters[${idx}][count]" class="form-control" placeholder="Number (e.g. 500+)"></div>
                <div class="col-md-6"><input type="text" name="counters[${idx}][subtitle]" class="form-control" placeholder="Sub Title"></div>
                <div class="col-md-1"><button type="button" class="btn btn-danger w-100 remove-btn">X</button></div>
            </div>`;
            $('#counters-container').append(html);
        });

        $(document).on('click', '.remove-btn', function () { $(this).closest('.button-row').remove(); });
    });

    function showFormLoader() {
        $('#createThisForm input, #createThisForm textarea, #createThisForm button').prop('disabled', true);
        var localeNames = {'ar': 'Arabic', 'fr': 'French', 'es': 'Spanish', 'de': 'German', 'it': 'Italian', 'pt': 'Portuguese', 'bn': 'Bengali', 'hi': 'Hindi', 'tr': 'Turkish', 'ur': 'Urdu'};
        var tickerMessages = ['Saving English...'];
        otherLocales.forEach(function(loc) { tickerMessages.push('Translating to ' + (localeNames[loc] || loc.toUpperCase()) + '...'); });
        tickerMessages.push('Finishing up...');
        var overlay = `<div class="form-loader-overlay" id="formLoader"><div class="spinner-ring"></div><div class="loader-text">Updating & Translating...</div><div class="progress-bar-container"><div class="progress-bar-fill" id="loaderProgress"></div></div><div class="loader-lang-ticker" id="loaderTicker">Preparing...</div></div>`;
        $('.card').append(overlay);
        var step = 0;
        var interval = setInterval(function() {
            if (step < tickerMessages.length) {
                $('#loaderProgress').css('width', Math.round(((step + 1) / tickerMessages.length) * 100) + '%');
                $('#loaderTicker').text(tickerMessages[step]); step++;
            } else { clearInterval(interval); $('#loaderTicker').text('Almost done...'); }
        }, 1000);
    }

    function hideFormLoader() {
        $('#formLoader').fadeOut(200, function() { $(this).remove(); });
        $('#createThisForm input, #createThisForm textarea, #createThisForm button').prop('disabled', false);
    }

    function previewImage(event, imgSelector) {
        if (event.target.files && event.target.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) { $(imgSelector).attr('src', e.target.result).removeClass('d-none'); };
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>
@endsection