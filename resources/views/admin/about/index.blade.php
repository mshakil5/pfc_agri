@extends('admin.pages.master')
@section('title', 'Edit About Page')
@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-9">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Edit About Us</h4>
                </div>
                <div class="card-body">
                    <form id="createThisForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="codeid" name="codeid" value="{{ $about->id }}">

                        <div class="row g-3">
                            @if ($about->pages == 'about')
                                <div class="col-md-6">
                                    <label class="form-label">Header Title</label>
                                    <input type="text" class="form-control" name="header_title" value="{{ $about->header_title }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Header Subtitle</label>
                                    <input type="text" class="form-control" name="header_subtitle" value="{{ $about->header_subtitle }}">
                                </div>

                                <hr>
                            @endif
                            

                            <div class="col-md-6">
                                <label class="form-label">Main Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" value="{{ $about->title }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Sub Title</label>
                                <input type="text" class="form-control" name="sub_title" value="{{ $about->sub_title }}">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Year</label>
                                <input type="text" class="form-control" name="year" value="{{ $about->year }}">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">About Image</label>
                                <input type="file" class="form-control" id="image" accept="image/*" onchange="previewImage(event, '#preview-image')" name="image">
                                
                                @if($about->image)
                                    <img id="preview-image" src="{{ asset('images/about/' . $about->image) }}" alt="Current Image" class="img-thumbnail rounded mt-3" style="max-width: 200px;">
                                @else
                                    <img id="preview-image" src="#" alt="" class="img-thumbnail rounded mt-3 d-none" style="max-width: 200px;">
                                @endif
                            </div>

                            <div class="col-md-12">
                                <label class="form-label"><b>Features (Amenities)</b></label>
                                <div id="features-container">
                                    @php $amenities = json_decode($about->amenities, true) ?? []; @endphp
                                    
                                    @forelse($amenities as $index => $item)
                                        <div class="row g-2 mb-2 button-row">
                                            <div class="col-md-2">
                                                <input type="text" name="features[{{ $index }}][icon]" class="form-control" value="{{ $item['icon'] ?? '' }}" placeholder="Icon Class">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" name="features[{{ $index }}][title]" class="form-control" value="{{ $item['title'] ?? '' }}" placeholder="Title">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" name="features[{{ $index }}][subtitle]" class="form-control" value="{{ $item['subtitle'] ?? '' }}" placeholder="Sub Title">
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-danger w-100 remove-btn">X</button>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="row g-2 mb-2 button-row">
                                            <div class="col-md-2">
                                                <input type="text" name="features[0][icon]" class="form-control" placeholder="Icon Class">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" name="features[0][title]" class="form-control" placeholder="Title">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" name="features[0][subtitle]" class="form-control" placeholder="Sub Title">
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-danger w-100 remove-btn">X</button>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                                <button type="button" id="add-features-button" class="btn btn-dark btn-sm mt-2">+ Add More Feature</button>
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
    $(document).ready(function() {
        var upurl = "{{ URL::to('/admin/about') }}";

        // Ajax Update
        $("#addBtn").click(function(e) {
            e.preventDefault();
            let formElement = document.getElementById('createThisForm');
            let form_data = new FormData(formElement);

            $.ajax({
                url: upurl,
                type: "POST",
                data: form_data,
                contentType: false,
                processData: false,
                success: function(d) {
                    if(typeof showSuccess === "function") showSuccess(d.message);
                    else alert(d.message);
                },
                error: function(xhr) {
                    alert("Something went wrong. Please check validation.");
                }
            });
        });

        // Dynamic Features Logic
        let featureIndex = {{ count($amenities) > 0 ? count($amenities) : 1 }};
        $('#add-features-button').click(function() {
            const html = `
                <div class="row g-2 mb-2 button-row">
                    <div class="col-md-2"><input type="text" name="features[${featureIndex}][icon]" class="form-control" placeholder="Icon Class"></div>
                    <div class="col-md-3"><input type="text" name="features[${featureIndex}][title]" class="form-control" placeholder="Title"></div>
                    <div class="col-md-6"><input type="text" name="features[${featureIndex}][subtitle]" class="form-control" placeholder="Sub Title"></div>
                    <div class="col-md-1"><button type="button" class="btn btn-danger w-100 remove-btn">X</button></div>
                </div>`;
            $('#features-container').append(html);
            featureIndex++;
        });

        $(document).on('click', '.remove-btn', function() {
            $(this).closest('.button-row').remove();
        });
    });

    function previewImage(event, imgSelector) {
        if (event.target.files && event.target.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $(imgSelector).attr('src', e.target.result).removeClass('d-none');
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>
@endsection