@extends('admin.pages.master')
@section('title', 'Edit About Page')
@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Edit {{ $about->pages == 'about' ? 'About Us' : 'Homepage About' }}</h4>
                    <small class="text-muted">Auto-translates on save</small>
                </div>
                <div class="card-body">
                    <form id="createThisForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="codeid" name="codeid" value="{{ $about->id }}">

                        <div class="row g-3">

                            <div class="col-md-12">
                                <label class="form-label">About Image</label>
                                <input type="file" class="form-control" id="image" accept="image/*"
                                    onchange="previewImage(event, '#preview-image')" name="image">
                                @if($about->image)
                                    <img id="preview-image" src="{{ asset('images/about/' . $about->image) }}"
                                        class="img-thumbnail rounded mt-3" style="max-width:200px;">
                                @else
                                    <img id="preview-image" src="#" class="img-thumbnail rounded mt-3 d-none" style="max-width:200px;">
                                @endif
                            </div>

                            @if($about->pages == 'about')
                                <div class="col-md-2">
                                    <label class="form-label">Year</label>
                                    <input type="text" class="form-control" name="year" value="{{ $about->year }}">
                                </div>
                                <div class="col-md-12"><hr></div>
                            @endif

                            {{-- Translation Notice --}}
                            <div class="col-md-12">
                                <div class="alert alert-info py-2">
                                    <i class="ri-translate-2 me-1"></i> 
                                    <small>Fill in English — will be auto-translated to {{ implode(', ', array_diff(config('translatable.locales'), ['en'])) }}</small>
                                </div>
                            </div>

                            @if($about->pages == 'about')
                                <div class="col-md-6">
                                    <label class="form-label">Header Title (EN) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="header_title" value="{{ $about->header_title }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Header Subtitle (EN)</label>
                                    <input type="text" class="form-control" name="header_subtitle" value="{{ $about->header_subtitle }}">
                                </div>
                            @endif

                            <div class="col-md-6">
                                <label class="form-label">Main Title (EN) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" value="{{ $about->title }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Sub Title (EN)</label>
                                <input type="text" class="form-control" name="sub_title" value="{{ $about->sub_title }}">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Long Description (EN)</label>
                                <textarea class="form-control" id="long_description" name="long_description">{!! $about->long_description !!}</textarea>
                            </div>

                            {{-- Features / Amenities --}}
                            <div class="col-md-12">
                                <label class="form-label"><b>Features / Amenities (EN)</b></label>
                                @php
                                    $amenities = json_decode($about->amenities ?? '[]', true) ?: [[]];
                                @endphp
                                <div id="features-container">
                                    @foreach($amenities as $i => $item)
                                        <div class="row g-2 mb-2 button-row">
                                            <div class="col-md-2">
                                                <input type="text" name="features[{{ $i }}][icon]" class="form-control"
                                                       value="{{ $item['icon'] ?? '' }}" placeholder="Icon Class">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" name="features[{{ $i }}][title]" class="form-control"
                                                       value="{{ $item['title'] ?? '' }}" placeholder="Title">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" name="features[{{ $i }}][subtitle]" class="form-control"
                                                       value="{{ $item['subtitle'] ?? '' }}" placeholder="Sub Title">
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-danger w-100 remove-btn">X</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-dark btn-sm mt-2" id="addFeatureBtn">
                                    + Add More Feature
                                </button>
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
    /* Loader Overlay Styles */
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
    var featureIndex = {{ max(count(json_decode($about->amenities ?? '[]', true) ?? []), 1) }};

    $(document).ready(function () {
        
        // Initialize single Summernote instance
        $('#long_description').summernote({ height: 200, focus: false });

        $("#addBtn").click(function (e) {
            e.preventDefault();
            
            // CRITICAL: Force Summernote to push its HTML content into the textarea 
            // so standard FormData picks it up correctly
            $('#long_description').val($('#long_description').summernote('code'));
            
            var formData = new FormData(document.getElementById('createThisForm'));
            showFormLoader();

            $.ajax({
                url: "{{ URL::to('/admin/about') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (d) { 
                    hideFormLoader(); 
                    showSuccess(d.message); 
                },
                error: function (xhr) { 
                    hideFormLoader(); 
                    showError(xhr.responseJSON?.message ?? 'Something went wrong.'); 
                }
            });
        });

        $("#addFeatureBtn").click(function () {
            var idx = featureIndex++;
            var html = `<div class="row g-2 mb-2 button-row">
                <div class="col-md-2"><input type="text" name="features[${idx}][icon]" class="form-control" placeholder="Icon Class"></div>
                <div class="col-md-3"><input type="text" name="features[${idx}][title]" class="form-control" placeholder="Title"></div>
                <div class="col-md-6"><input type="text" name="features[${idx}][subtitle]" class="form-control" placeholder="Sub Title"></div>
                <div class="col-md-1"><button type="button" class="btn btn-danger w-100 remove-btn">X</button></div>
            </div>`;
            $('#features-container').append(html);
        });

        $(document).on('click', '.remove-btn', function () {
            $(this).closest('.button-row').remove();
        });
    });

    function showFormLoader() {
        $('#createThisForm input, #createThisForm textarea, #createThisForm button').prop('disabled', true);
        $('#long_description').summernote('disable');
        
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
        $('#long_description').summernote('enable');
    }

    function previewImage(event, imgSelector) {
        if (event.target.files && event.target.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(imgSelector).attr('src', e.target.result).removeClass('d-none');
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>
@endsection