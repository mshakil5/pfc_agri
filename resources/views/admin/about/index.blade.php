@extends('admin.pages.master')
@section('title', 'Edit About Page')
@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Edit About Us</h4>
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

                            {{-- Language Tabs --}}
                            <div class="col-md-12">
                                <ul class="nav nav-tabs nav-tabs-custom nav-success mb-3" role="tablist">
                                    @foreach(config('translatable.locales') as $index => $locale)
                                        <li class="nav-item">
                                            <a class="nav-link {{ $index == 0 ? 'active' : '' }}"
                                               data-bs-toggle="tab" href="#about-tab-{{ $locale }}" role="tab">
                                                {{ strtoupper($locale) }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="tab-content">
                                    @foreach(config('translatable.locales') as $index => $locale)
                                        @php
                                            $isDefault = $locale === 'en';
                                            $trans = !$isDefault ? ($about->translations[$locale] ?? []) : [];
                                        @endphp
                                        <div class="tab-pane {{ $index == 0 ? 'active' : '' }}"
                                             id="about-tab-{{ $locale }}" role="tabpanel">
                                            <div class="row g-3">

                                                @if($about->pages == 'about')
                                                    <div class="col-md-6">
                                                        <label class="form-label">Header Title ({{ strtoupper($locale) }})</label>
                                                        <input type="text" class="form-control"
                                                               name="{{ $isDefault ? 'header_title' : "trans[$locale][header_title]" }}"
                                                               value="{{ $isDefault ? $about->header_title : ($trans['header_title'] ?? '') }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Header Subtitle ({{ strtoupper($locale) }})</label>
                                                        <input type="text" class="form-control"
                                                               name="{{ $isDefault ? 'header_subtitle' : "trans[$locale][header_subtitle]" }}"
                                                               value="{{ $isDefault ? $about->header_subtitle : ($trans['header_subtitle'] ?? '') }}">
                                                    </div>
                                                @endif

                                                <div class="col-md-6">
                                                    <label class="form-label">Main Title ({{ strtoupper($locale) }}) @if($isDefault)<span class="text-danger">*</span>@endif</label>
                                                    <input type="text" class="form-control"
                                                           name="{{ $isDefault ? 'title' : "trans[$locale][title]" }}"
                                                           value="{{ $isDefault ? $about->title : ($trans['title'] ?? '') }}">
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Sub Title ({{ strtoupper($locale) }})</label>
                                                    <input type="text" class="form-control"
                                                           name="{{ $isDefault ? 'sub_title' : "trans[$locale][sub_title]" }}"
                                                           value="{{ $isDefault ? $about->sub_title : ($trans['sub_title'] ?? '') }}">
                                                </div>

                                                <div class="col-md-12">
                                                    <label class="form-label">Long Description ({{ strtoupper($locale) }})</label>
                                                    <textarea class="form-control summernote-{{ $locale }}"
                                                              name="{{ $isDefault ? 'long_description' : "trans[$locale][long_description]" }}">{!! $isDefault ? $about->long_description : ($trans['long_description'] ?? '') !!}</textarea>
                                                </div>

                                                {{-- Amenities --}}
                                                <div class="col-md-12">
                                                    <label class="form-label"><b>Features / Amenities ({{ strtoupper($locale) }})</b></label>
                                                    @php
                                                        $amenities = $isDefault
                                                            ? (json_decode($about->amenities, true) ?? [])
                                                            : ($trans['amenities'] ?? []);
                                                    @endphp
                                                    <div id="features-container-{{ $locale }}">
                                                        @php $amenities = $amenities ?: [[]]; @endphp
                                                        @foreach($amenities as $i => $item)
                                                            <div class="row g-2 mb-2 button-row">
                                                                @if($isDefault)
                                                                    <div class="col-md-2">
                                                                        <input type="text" name="features[{{ $i }}][icon]" class="form-control"
                                                                               value="{{ $item['icon'] ?? '' }}" placeholder="Icon Class">
                                                                    </div>
                                                                @endif
                                                                <div class="{{ $isDefault ? 'col-md-3' : 'col-md-5' }}">
                                                                    <input type="text"
                                                                           name="{{ $isDefault ? "features[$i][title]" : "trans[$locale][amenities][$i][title]" }}"
                                                                           class="form-control" value="{{ $item['title'] ?? '' }}" placeholder="Title">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <input type="text"
                                                                           name="{{ $isDefault ? "features[$i][subtitle]" : "trans[$locale][amenities][$i][subtitle]" }}"
                                                                           class="form-control" value="{{ $item['subtitle'] ?? '' }}" placeholder="Sub Title">
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <button type="button" class="btn btn-danger w-100 remove-btn">X</button>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <button type="button" class="btn btn-dark btn-sm mt-2 add-feature-btn"
                                                            data-locale="{{ $locale }}" data-default="{{ $isDefault ? '1' : '0' }}">
                                                        + Add More Feature
                                                    </button>
                                                </div>

                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="card-footer text-end">
                    <button type="button" id="addBtn" class="btn btn-primary">Update About Page</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    var featureIndex = {};
    @foreach(config('translatable.locales') as $locale)
        @php
            $isDefault = $locale === 'en';
            $cnt = $isDefault
                ? count(json_decode($about->amenities ?? '[]', true) ?? [])
                : count(($about->translations[$locale]['amenities'] ?? []));
        @endphp
        featureIndex['{{ $locale }}'] = {{ max($cnt, 1) }};
    @endforeach

    $(document).ready(function () {

        @foreach(config('translatable.locales') as $locale)
            $('.summernote-{{ $locale }}').summernote({ height: 200, focus: false });
        @endforeach

        $("#addBtn").click(function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{ URL::to('/admin/about') }}",
                type: "POST",
                data: new FormData(document.getElementById('createThisForm')),
                contentType: false,
                processData: false,
                success: function (d) { showSuccess(d.message); },
                error: function (xhr) { showError(xhr.responseJSON?.message ?? 'Something went wrong.'); }
            });
        });

        $(document).on('click', '.add-feature-btn', function () {
            var locale    = $(this).data('locale');
            var isDefault = $(this).data('default') == '1';
            var idx       = featureIndex[locale]++;
            var html      = '';

            if (isDefault) {
                html = `<div class="row g-2 mb-2 button-row">
                    <div class="col-md-2"><input type="text" name="features[${idx}][icon]" class="form-control" placeholder="Icon Class"></div>
                    <div class="col-md-3"><input type="text" name="features[${idx}][title]" class="form-control" placeholder="Title"></div>
                    <div class="col-md-6"><input type="text" name="features[${idx}][subtitle]" class="form-control" placeholder="Sub Title"></div>
                    <div class="col-md-1"><button type="button" class="btn btn-danger w-100 remove-btn">X</button></div>
                </div>`;
            } else {
                html = `<div class="row g-2 mb-2 button-row">
                    <div class="col-md-5"><input type="text" name="trans[${locale}][amenities][${idx}][title]" class="form-control" placeholder="Title"></div>
                    <div class="col-md-6"><input type="text" name="trans[${locale}][amenities][${idx}][subtitle]" class="form-control" placeholder="Sub Title"></div>
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