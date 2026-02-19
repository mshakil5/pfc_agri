@extends('admin.pages.master')
@section('title', 'Edit R&D Page')
@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Edit R&D Page</h4>
                </div>
                <div class="card-body">
                    <form id="createThisForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="codeid" name="codeid" value="{{ $data->id }}">

                        <div class="row g-3">

                            {{-- Language Tabs --}}
                            <div class="col-md-12">
                                <ul class="nav nav-tabs nav-tabs-custom nav-success mb-3" role="tablist">
                                    @foreach(config('translatable.locales') as $index => $locale)
                                        <li class="nav-item">
                                            <a class="nav-link {{ $index == 0 ? 'active' : '' }}"
                                               data-bs-toggle="tab" href="#rnd-tab-{{ $locale }}" role="tab">
                                                {{ strtoupper($locale) }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="tab-content">
                                    @foreach(config('translatable.locales') as $index => $locale)
                                        @php
                                            $isDefault = $locale === 'en';
                                            $trans = !$isDefault ? ($data->translations[$locale] ?? []) : [];
                                            $amenities = $isDefault
                                                ? (json_decode($data->extra1, true) ?? [])
                                                : ($trans['counters'] ?? []);
                                        @endphp
                                        <div class="tab-pane {{ $index == 0 ? 'active' : '' }}"
                                             id="rnd-tab-{{ $locale }}" role="tabpanel">
                                            <div class="row g-3">

                                                <div class="col-md-4">
                                                    <label class="form-label">Small Title ({{ strtoupper($locale) }})</label>
                                                    <input type="text" class="form-control"
                                                           name="{{ $isDefault ? 'name' : "trans[$locale][name]" }}"
                                                           value="{{ $isDefault ? $data->name : ($trans['name'] ?? '') }}">
                                                </div>

                                                <div class="col-md-8">
                                                    <label class="form-label">Header Title ({{ strtoupper($locale) }})</label>
                                                    <input type="text" class="form-control"
                                                           name="{{ $isDefault ? 'short_title' : "trans[$locale][short_title]" }}"
                                                           value="{{ $isDefault ? $data->short_title : ($trans['short_title'] ?? '') }}">
                                                </div>

                                                <div class="col-md-12">
                                                    <label class="form-label">Header Subtitle ({{ strtoupper($locale) }})</label>
                                                    <input type="text" class="form-control"
                                                           name="{{ $isDefault ? 'long_title' : "trans[$locale][long_title]" }}"
                                                           value="{{ $isDefault ? $data->long_title : ($trans['long_title'] ?? '') }}">
                                                </div>

                                                {{-- Counters --}}
                                                <div class="col-md-12">
                                                    <label class="form-label"><b>Counters ({{ strtoupper($locale) }})</b></label>
                                                    <div id="features-container-{{ $locale }}">
                                                        @php $amenities = $amenities ?: [[]]; @endphp
                                                        @foreach($amenities as $i => $item)
                                                            <div class="row g-2 mb-2 button-row">
                                                                <div class="col-md-5">
                                                                    <input type="text"
                                                                           name="{{ $isDefault ? "features[$i][count]" : "trans[$locale][counters][$i][count]" }}"
                                                                           class="form-control" value="{{ $item['count'] ?? '' }}" placeholder="Number">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <input type="text"
                                                                           name="{{ $isDefault ? "features[$i][subtitle]" : "trans[$locale][counters][$i][subtitle]" }}"
                                                                           class="form-control" value="{{ $item['subtitle'] ?? '' }}" placeholder="Sub Title">
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <button type="button" class="btn btn-danger w-100 remove-btn">X</button>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <button type="button" class="btn btn-dark btn-sm mt-2 add-counter-btn"
                                                            data-locale="{{ $locale }}" data-default="{{ $isDefault ? '1' : '0' }}">
                                                        + Add Counter
                                                    </button>
                                                </div>

                                            </div>
                                        </div>
                                    @endforeach
                                </div>
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
                                <input type="file" class="form-control" name="meta_image"
                                    onchange="previewImage(event, '#meta_image_preview')">
                                <img id="meta_image_preview" src="#" class="img-thumbnail mt-3">
                            </div>

                        </div>
                    </form>
                </div>
                <div class="card-footer text-end">
                    <button type="button" id="addBtn" class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    var counterIndex = {};
    @foreach(config('translatable.locales') as $locale)
        @php
            $isDefault = $locale === 'en';
            $cnt = $isDefault
                ? count(json_decode($data->extra1 ?? '[]', true) ?? [])
                : count(($data->translations[$locale]['counters'] ?? []));
        @endphp
        counterIndex['{{ $locale }}'] = {{ max($cnt, 1) }};
    @endforeach

    $(document).ready(function () {

        $("#addBtn").click(function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{ URL::to('/admin/research') }}",
                type: "POST",
                data: new FormData(document.getElementById('createThisForm')),
                contentType: false,
                processData: false,
                success: function (d) { showSuccess(d.message); },
                error: function (xhr) { showError(xhr.responseJSON?.message ?? 'Something went wrong.'); }
            });
        });

        $(document).on('click', '.add-counter-btn', function () {
            var locale    = $(this).data('locale');
            var isDefault = $(this).data('default') == '1';
            var idx       = counterIndex[locale]++;
            var html      = '';

            if (isDefault) {
                html = `<div class="row g-2 mb-2 button-row">
                    <div class="col-md-5"><input type="text" name="features[${idx}][count]" class="form-control" placeholder="Number"></div>
                    <div class="col-md-6"><input type="text" name="features[${idx}][subtitle]" class="form-control" placeholder="Sub Title"></div>
                    <div class="col-md-1"><button type="button" class="btn btn-danger w-100 remove-btn">X</button></div>
                </div>`;
            } else {
                html = `<div class="row g-2 mb-2 button-row">
                    <div class="col-md-5"><input type="text" name="trans[${locale}][counters][${idx}][count]" class="form-control" placeholder="Number"></div>
                    <div class="col-md-6"><input type="text" name="trans[${locale}][counters][${idx}][subtitle]" class="form-control" placeholder="Sub Title"></div>
                    <div class="col-md-1"><button type="button" class="btn btn-danger w-100 remove-btn">X</button></div>
                </div>`;
            }

            $('#features-container-' + locale).append(html);
        });

        $(document).on('click', '.remove-btn', function () {
            $(this).closest('.button-row').remove();
        });
    });

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