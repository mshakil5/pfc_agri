@extends('admin.pages.master')
@section('title', 'Manage Blog')
@section('content')

<div class="container-fluid" id="newBtnSection">
    <button type="button" class="btn btn-primary mb-3" id="newBtn">Add New Blog Post</button>
</div>

<div class="container-fluid" id="addThisFormContainer" style="display: none;">
    <div class="card">
        <div class="card-header"><h4 id="cardTitle">Add New Blog</h4></div>
        <div class="card-body">
            <form id="createThisForm">
                @csrf
                <input type="hidden" id="codeid" name="codeid">
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Author Name</label>
                        <input type="text" class="form-control" name="author_name" id="author_name">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Publish Date</label>
                        <input type="date" class="form-control" name="published_at" id="published_at">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Blog Image</label>
                        <input type="file" class="form-control" name="image" id="imageInput" accept="image/*">
                        <div id="imagePreviewContainer" class="mt-2" style="display:none;">
                            <img id="imagePreview" src="" alt="Preview" style="width: 80px; height: 50px; object-fit: cover; border-radius: 5px; border: 1px solid #ddd;">
                        </div>
                    </div>
                </div>

                <ul class="nav nav-tabs nav-tabs-custom nav-success mb-3" role="tablist">
                    @foreach(config('translatable.locales') as $index => $locale)
                        <li class="nav-item">
                            <a class="nav-link {{ $index == 0 ? 'active' : '' }}" data-bs-toggle="tab" href="#blog-{{ $locale }}">
                                {{ strtoupper($locale) }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content">
                    @foreach(config('translatable.locales') as $index => $locale)
                        <div class="tab-pane {{ $index == 0 ? 'active' : '' }}" id="blog-{{ $locale }}">
                            <div class="mb-3">
                                <label>Title ({{ strtoupper($locale) }})</label>
                                <input type="text" name="{{ $locale }}[title]" id="{{ $locale }}_title" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Excerpt ({{ strtoupper($locale) }})</label>
                                <textarea name="{{ $locale }}[excerpt]" id="{{ $locale }}_excerpt" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="mb-3">
                                <label>Full Content ({{ strtoupper($locale) }})</label>
                                <textarea name="{{ $locale }}[description]" id="{{ $locale }}_description" class="form-control summernote"></textarea>
                            </div>
                        </div>
                    @endforeach
                </div>
            </form>
        </div>
        <div class="card-footer text-end">
            <button type="button" id="addBtn" class="btn btn-primary">Save Post</button>
            <button type="button" id="FormCloseBtn" class="btn btn-light">Cancel</button>
        </div>
    </div>
</div>

<div class="container-fluid" id="contentContainer">
    <div class="card">
        <div class="card-body">
            <table id="blogTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    $(document).ready(function() {
        var table = $('#blogTable').DataTable({
            processing: true, serverSide: true,
            ajax: "{{ route('admin.blogs') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'image', name: 'image', orderable: false, searchable: false },
                { data: 'title', name: 'title' },
                { data: 'author_name', name: 'author_name' },
                { data: 'published_at', name: 'published_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        // Submit Logic
        $("#addBtn").click(function() {
            let id = $("#codeid").val();
            let url = id ? "{{ url('/admin/blogs-update') }}" : "{{ url('/admin/blogs') }}";
            $.ajax({
                url: url, type: "POST", data: new FormData($('#createThisForm')[0]),
                contentType: false, processData: false,
                success: function(d) {
                    showSuccess(d.message);
                    $("#addThisFormContainer").slideUp();
                    $("#newBtn").show();
                    table.draw();
                }
            });
        });

        // Edit Logic
        $('#contentContainer').on('click', '#EditBtn', function() {
            let id = $(this).attr('rid');
            $.get("/admin/blogs/" + id + "/edit", function(data) {
                $("#codeid").val(data.id);
                $("#author_name").val(data.author_name);
                $("#published_at").val(data.published_at);
                $("#image").val(data.image);

                data.translations.forEach(function(t) {
                    $(`#${t.locale}_title`).val(t.title);
                    $(`#${t.locale}_excerpt`).val(t.excerpt);
                    $(`#${t.locale}_description`).summernote('code', t.description);
                });

                if (data.image) {
                    $("#imagePreview").attr('src', '/' + data.image);
                    $("#imagePreviewContainer").show();
                } else {
                    $("#imagePreviewContainer").hide();
                }
        

                $("#addThisFormContainer").slideDown();
                $("#newBtn").hide();
                $("#cardTitle").text('Edit Blog Post');
            });
        });

        // Toggle Buttons
        $("#newBtn").click(function() {
            $('#createThisForm')[0].reset();
            $("#imagePreviewContainer").hide();
            $(".summernote").summernote('code', '');
            $("#codeid").val('');
            $("#addThisFormContainer").slideDown();
            $(this).hide();
        });
        $("#FormCloseBtn").click(function() {
            $("#addThisFormContainer").slideUp();
            $("#newBtn").show();
        });
    });
</script>
@endsection