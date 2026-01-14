@extends('admin.pages.master')
@section('title', 'Edit About Page')
@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-9">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Edit</h4>
                </div>
                <div class="card-body">
                    <form id="createThisForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="codeid" name="codeid" value="{{ $data->id }}">

                        <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Small title</label>
                                    <input type="text" class="form-control" name="name" value="{{ $data->name }}">
                                </div>

                                <div class="col-md-8">
                                    <label class="form-label">Header title</label>
                                    <input type="text" class="form-control" name="short_title" value="{{ $data->short_title }}">
                                </div>

                                
                                <div class="col-md-12">
                                    <label class="form-label">Header subtitle</label>
                                    <input type="text" class="form-control" name="long_title" value="{{ $data->long_title }}">
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label"><b>Features (Amenities)</b></label>
                                    <div id="features-container">
                                        @php $amenities = json_decode($data->extra1, true) ?? []; @endphp
                                        
                                        @forelse($amenities as $index => $item)
                                            <div class="row g-2 mb-2 button-row">
                                                <div class="col-md-5">
                                                    <input type="text" name="features[{{ $index }}][count]" class="form-control" value="{{ $item['count'] ?? '' }}" placeholder="Number">
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
                                                    <div class="col-md-5">
                                                        <input type="text" name="features[0][count]" class="form-control" placeholder="Number">
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

                                <hr>
                            
                            
                                <div class="col-12 mb-3">
                                    <label>Meta Title</label>
                                    <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{$data->meta_title}}">
                                </div>
                                <div class="col-12 mb-3">
                                    <label>Meta Description</label>
                                    <textarea class="form-control ckeditor-classic" id="meta_description" name="meta_description">{{$data->meta_title}}</textarea>
                                </div>
                                <div class="col-12 mb-3">
                                    <label>Meta Keywords</label>
                                    <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" value="{{$data->meta_keywords}}">
                                </div>
                                <div class="col-12 mb-3">
                                    <label>Meta Image</label>
                                    <input type="file" class="form-control" id="meta_image" name="meta_image"
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
    $(document).ready(function() {
        var upurl = "{{ URL::to('/admin/research') }}";

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
                    <div class="col-md-5"><input type="text" name="features[${featureIndex}][count]" class="form-control" placeholder="Number"></div>
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