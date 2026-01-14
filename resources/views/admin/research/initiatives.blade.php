@extends('admin.pages.master')
@section('title', 'Initiatives')
@section('content')
    <div class="container-fluid" id="newBtnSection">
        <div class="row mb-3">
            <div class="col-auto">
                <button type="button" class="btn btn-primary" id="newBtn">
                    Add New Initiative
                </button>
            </div>
        </div>
    </div>

    <div class="container-fluid" id="addThisFormContainer">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1" id="cardTitle">Add New Initiative</h4>
                    </div>
                    <div class="card-body">
                        <form id="createThisForm">
                            @csrf
                            <input type="hidden" id="codeid" name="codeid">

                            <div class="row g-3">

                                <div class="col-md-4">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-control select2">
                                        <option value="0">Planning</option>
                                        <option value="1">In Progress</option>
                                        <option value="2">Testing</option>
                                        <option value="3">Complete</option>
                                        <option value="4">Decline</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="date" name="date">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Deadline </label>
                                    <input type="date" class="form-control" id="deadline" name="deadline">
                                </div>


                                <div class="col-md-12">
                                    <label class="form-label">Initiative Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" name="title">
                                </div>
                            

                                <div class="col-md-6">
                                    <label class="form-label">Initiative Image</label>
                                    <input type="file" class="form-control" id="feature_image" accept="image/*"
                                        onchange="previewImage(event, '#preview-image')">
                                </div>

                                <div class="col-md-6">
                                    <img id="preview-image" src="/placeholder.webp" alt="" class="img-thumbnail rounded"
                                        style="max-width: 200px; max-height: 200px;">
                                    <button type="button" class="btn btn-sm btn-danger mt-2" id="removeImageBtn" style="display:none;">
                                        Remove Image
                                    </button>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Short Description</label>
                                    <textarea class="form-control" id="short_description" name="short_description" rows="2"
                                        placeholder="Enter short description (optional)"></textarea>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Long Description</label>
                                    <textarea class="form-control summernote" id="long_description" name="long_description"
                                        placeholder="Enter long description (optional)"></textarea>
                                </div>

                                <hr>

                                <div class="col-12 mb-3">
                                    <label>Meta Title</label>
                                    <input type="text" class="form-control" id="meta_title" name="meta_title">
                                </div>
                                <div class="col-12 mb-3">
                                    <label>Meta Description</label>
                                    <textarea class="form-control summernote" id="meta_description" name="meta_description"></textarea>
                                </div>
                                <div class="col-12 mb-3">
                                    <label>Meta Keywords</label>
                                    <input type="text" class="form-control" id="meta_keywords" name="meta_keywords">
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
                        <button type="submit" id="addBtn" class="btn btn-primary">Create</button>
                        <button type="button" id="FormCloseBtn" class="btn btn-light">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid" id="contentContainer">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Initiatives List</h4>

            </div>

            <div class="card-body">
                <table id="productTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Status</th>
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
        let currentProductId = null;

        $(document).ready(function() {

            $('#productTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: {
                    url: "{{ route('admin.initiatives') }}"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    { data: 'feature_image', name: 'feature_image', orderable:false, searchable:false },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#filterCategory').change(function() {
                reloadTable('#productTable');
            });

            $(document).on('change', '.toggle-status', function() {
                var product_id = $(this).data('id');
                var status = $(this).prop('checked') ? 1 : 0;
                $.post('/admin/initiatives-status', {
                    _token: '{{ csrf_token() }}',
                    product_id: product_id,
                    status: status
                }, function(d) {
                    reloadTable('#productTable');
                    showSuccess(d.message);
                }).fail(() => showError('Failed to update status'));
            });

            $("#addThisFormContainer").hide();
            $("#newBtn").click(function() {
                clearform();
                $("#addThisFormContainer").slideDown(300);
                $("#newBtn").hide();
                pageTop();
            });
            $("#FormCloseBtn").click(function() {
                $("#addThisFormContainer").slideUp(300);
                setTimeout(() => {
                    $("#newBtn").show();
                }, 300);
            });

            var url = "{{ URL::to('/admin/initiatives') }}";
            var upurl = "{{ URL::to('/admin/initiatives-update') }}";

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#addBtn").click(function() {
                let id = $("#codeid").val();
                let isUpdate = id ? true : false;
                let submitUrl = isUpdate ? upurl : url;

                var form_data = new FormData();
                form_data.append("title", $("#title").val());
                form_data.append("status", $("#status").val());
                form_data.append("date", $("#date").val());
                form_data.append("deadline", $("#deadline").val());
                form_data.append("short_description", $("#short_description").val());
                
                // Specifically target IDs for summernote to avoid mixing descriptions
                form_data.append("long_description", $("#long_description").summernote('code'));
                form_data.append("meta_description", $("#meta_description").summernote('code'));
                
                form_data.append("meta_title", $("#meta_title").val());
                form_data.append("meta_keywords", $("#meta_keywords").val());

                if (id) form_data.append("codeid", id);

                // Images
                var featureImg = document.getElementById('feature_image').files[0];
                if (featureImg) form_data.append("feature_image", featureImg);

                var metaImg = document.getElementById('meta_image').files[0];
                if (metaImg) form_data.append("meta_image", metaImg);

                $.ajax({
                    url: submitUrl,
                    type: "POST",
                    data: form_data,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $("#addBtn").prop('disabled', true).text('Processing...');
                    },
                    success: function(d) {
                        showSuccess(d.message);
                        $("#addThisFormContainer").slideUp(300);
                        setTimeout(() => { $("#newBtn").show(); }, 300);
                        reloadTable('#productTable');
                        clearform();
                    },
                    error: function(xhr) {
                        pageTop();
                        if (xhr.responseJSON?.errors) {
                            let errors = Object.values(xhr.responseJSON.errors).flat();
                            showError(errors[0]);
                        } else {
                            showError(xhr.responseJSON?.message ?? "Something went wrong!");
                        }
                    },
                    complete: function() {
                        $("#addBtn").prop('disabled', false).text(isUpdate ? 'Update' : 'Create');
                    }
                });
            });



            $("#contentContainer").on('click', '#EditBtn', function() {
                $("#cardTitle").text('Update Initiative');
                codeid = $(this).attr('rid');
                currentProductId = codeid;
                $.get(url + '/' + codeid + '/edit', {}, function(d) {
                    populateForm(d);
                });
            });

            $("#removeImageBtn").click(function(e) {
                e.preventDefault();
                if (!currentProductId) return;
                
                $.post('/admin/initiatives/' + currentProductId + '/remove-image', {
                    _token: '{{ csrf_token() }}'
                }, function(d) {
                    $("#preview-image").attr('src', '/placeholder.webp');
                    $("#removeImageBtn").hide();
                    $("#image").val('');
                    showSuccess(d.message);
                }).fail(() => showError('Failed to remove image'));
            });

            function populateForm(data) {
                // 1. Basic Text & Hidden Fields
                $("#codeid").val(data.id);
                $("#title").val(data.title);
                $("#date").val(data.date);
                $("#deadline").val(data.deadline);
                $("#short_description").val(data.short_description);
                
                // 2. Select2 / Dropdowns
                // We must trigger('change') so Select2 updates its visual display
                $("#status").val(data.status).trigger('change');

                // 3. Summernote Editors
                // Target each ID specifically to ensure content goes to the right box
                $("#long_description").summernote('code', data.long_description || '');
                $("#meta_description").summernote('code', data.meta_description || '');

                // 4. SEO / Meta Text Fields
                $("#meta_title").val(data.meta_title);
                $("#meta_keywords").val(data.meta_keywords);

                // 5. Image Previews
                // Main Initiative Image
                if (data.image) {
                    $("#preview-image").attr('src', data.image);
                    if (data.image !== '/placeholder.webp') {
                        $("#removeImageBtn").show();
                    }
                } else {
                    $("#preview-image").attr('src', '/placeholder.webp');
                    $("#removeImageBtn").hide();
                }

                // Meta Image Preview
                if (data.meta_image) {
                    $("#meta_image_preview").attr('src', data.meta_image);
                } else {
                    $("#meta_image_preview").attr('src', '/placeholder.webp'); // or hide it
                }

                // 6. UI Transition
                $("#cardTitle").text('Update Initiative');
                $("#addBtn").val('Update').html('Update');
                $("#addThisFormContainer").slideDown(300);
                $("#newBtn").hide();
                pageTop();
            }

            function clearform() {
                // Reset the standard form fields
                $('#createThisForm')[0].reset();
                
                // Reset Hidden ID
                $("#codeid").val('');
                
                // Reset Select2
                $("#status").val(0).trigger('change');
                
                // Clear Summernote instances
                $(".summernote").summernote('code', '');
                
                // Reset Image Previews
                $("#preview-image").attr('src', '/placeholder.webp');
                $("#meta_image_preview").attr('src', '/placeholder.webp');
                $("#removeImageBtn").hide();
                
                // Reset Buttons and Titles
                $("#addBtn").val('Create').html('Create');
                $("#cardTitle").text('Add New Initiative');
                currentProductId = null;
            }
        });


    </script>

@endsection