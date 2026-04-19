@extends('admin.pages.master')
@section('title', 'Initiatives')
@section('content')
    <div class="container-fluid" id="newBtnSection">
        <div class="row mb-3">
            <div class="col-auto">
                <button type="button" class="btn btn-primary" id="newBtn">Add New Initiative</button>
            </div>
        </div>
    </div>

    <div class="container-fluid" id="addThisFormContainer" style="display:none;">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1" id="cardTitle">Add New Initiative</h4>
                        <small class="text-muted">Auto-translates on save</small>
                    </div>
                    <div class="card-body">
                        <form id="createThisForm">
                            @csrf
                            <input type="hidden" id="codeid" name="codeid">

                            <div class="row g-3">
                                <div class="col-md-12">
                                    <div class="alert alert-info py-2 mb-0">
                                        <i class="ri-translate-2 me-1"></i> 
                                        <small>Fill in English — will be auto-translated to {{ implode(', ', array_diff(config('translatable.locales'), ['en'])) }}</small>
                                    </div>
                                </div>

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
                                    <label class="form-label">Deadline</label>
                                    <input type="date" class="form-control" id="deadline" name="deadline">
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Initiative Title (EN) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" name="title">
                                </div>
                            
                                <div class="col-md-6">
                                    <label class="form-label">Initiative Image</label>
                                    <input type="file" class="form-control" id="feature_image" accept="image/*" onchange="previewImage(event, '#preview-image')">
                                </div>

                                <div class="col-md-6">
                                    <img id="preview-image" src="/placeholder.webp" alt="" class="img-thumbnail rounded" style="max-width: 200px; max-height: 200px;">
                                    <button type="button" class="btn btn-sm btn-danger mt-2" id="removeImageBtn" style="display:none;">Remove Image</button>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Short Description (EN)</label>
                                    <textarea class="form-control" id="short_description" name="short_description" rows="2"></textarea>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Long Description (EN)</label>
                                    <textarea class="form-control summernote" id="long_description" name="long_description"></textarea>
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
                                    <input type="file" class="form-control" id="meta_image" name="meta_image" onchange="previewImage(event, '#meta_image_preview')">
                                    <img id="meta_image_preview" src="/placeholder.webp" class="img-thumbnail mt-3" style="max-width:150px;">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-end">
                        <button type="button" id="addBtn" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i> Create & Translate
                        </button>
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
<style>
    #addThisFormContainer .card { position: relative; overflow: hidden; }
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
    let currentProductId = null;
    var otherLocales = @json(array_values(array_diff(config('translatable.locales'), ['en'])));
    var isEditorInitialized = false;
    var isSelect2Initialized = false;

    $(document).ready(function() {
        var table = $('#productTable').DataTable({
            processing: true, serverSide: true, pageLength: 25,
            ajax: { url: "{{ route('admin.initiatives') }}" },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'feature_image', name: 'feature_image', orderable:false, searchable:false },
                { data: 'title', name: 'title' },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        var url = "{{ URL::to('/admin/initiatives') }}";
        var upurl = "{{ URL::to('/admin/initiatives-update') }}";

        // Toggle UI
        $("#newBtn").click(function() {
            clearform();
            initPlugins();
            $("#addThisFormContainer").slideDown(300);
            $("#newBtn").hide();
            pageTop();
        });
        $("#FormCloseBtn").click(function() {
            $("#addThisFormContainer").slideUp(300);
            setTimeout(() => { $("#newBtn").show(); }, 300);
        });

        // Submit Logic (Manually appending to handle Summernote properly)
        $("#addBtn").click(function(e) {
            e.preventDefault();
            let id = $("#codeid").val();
            let isUpdate = id ? true : false;
            let submitUrl = isUpdate ? upurl : url;

            var form_data = new FormData();
            form_data.append("_token", '{{ csrf_token() }}');
            form_data.append("title", $("#title").val());
            form_data.append("status", $("#status").val());
            form_data.append("date", $("#date").val());
            form_data.append("deadline", $("#deadline").val());
            form_data.append("short_description", $("#short_description").val());
            
            // Specific Summernote targets
            form_data.append("long_description", $("#long_description").summernote('code'));
            form_data.append("meta_description", $("#meta_description").summernote('code'));
            
            form_data.append("meta_title", $("#meta_title").val());
            form_data.append("meta_keywords", $("#meta_keywords").val());
            if (id) form_data.append("codeid", id);

            var featureImg = document.getElementById('feature_image').files[0];
            if (featureImg) form_data.append("feature_image", featureImg);

            var metaImg = document.getElementById('meta_image').files[0];
            if (metaImg) form_data.append("meta_image", metaImg);

            showFormLoader(); // Show loader before sending

            $.ajax({
                url: submitUrl, type: "POST", data: form_data,
                contentType: false, processData: false,
                success: function(d) {
                    hideFormLoader();
                    showSuccess(d.message);
                    $("#addThisFormContainer").slideUp(300);
                    setTimeout(() => { $("#newBtn").show(); }, 300);
                    table.draw();
                    clearform();
                },
                error: function(xhr) {
                    hideFormLoader();
                    pageTop();
                    if (xhr.responseJSON?.errors) {
                        let errors = Object.values(xhr.responseJSON.errors).flat();
                        showError(errors[0]);
                    } else {
                        showError(xhr.responseJSON?.message ?? "Something went wrong!");
                    }
                }
            });
        });

        // Edit Logic
        $("#contentContainer").on('click', '#EditBtn', function() {
            codeid = $(this).attr('rid');
            currentProductId = codeid;
            $.get(url + '/' + codeid + '/edit', {}, function(d) {
                populateForm(d);
            });
        });

        function populateForm(data) {
            $("#codeid").val(data.id);
            $("#title").val(data.title);
            $("#date").val(data.date);
            $("#deadline").val(data.deadline);
            $("#short_description").val(data.short_description);
            $("#status").val(data.status).trigger('change');
            
            initPlugins(); // Ensure initialized before setting code
            
            $("#long_description").summernote('code', data.long_description || '');
            $("#meta_description").summernote('code', data.meta_description || '');
            $("#meta_title").val(data.meta_title);
            $("#meta_keywords").val(data.meta_keywords);

            if (data.feature_image) {
                $("#preview-image").attr('src', data.feature_image);
                if (data.feature_image !== '/placeholder.webp') $("#removeImageBtn").show();
            } else {
                $("#preview-image").attr('src', '/placeholder.webp');
                $("#removeImageBtn").hide();
            }

            if (data.meta_image) {
                $("#meta_image_preview").attr('src', data.meta_image);
            } else {
                $("#meta_image_preview").attr('src', '/placeholder.webp');
            }

            $("#cardTitle").text('Update Initiative');
            $("#addBtn").html('<i class="ri-save-line me-1"></i> Update & Translate');
            $("#addThisFormContainer").slideDown(300);
            $("#newBtn").hide();
            pageTop();
        }

        function clearform() {
            $('#createThisForm')[0].reset();
            $("#codeid").val('');
            $("#status").val(0).trigger('change');
            if(isEditorInitialized) $(".summernote").summernote('code', '');
            $("#preview-image").attr('src', '/placeholder.webp');
            $("#meta_image_preview").attr('src', '/placeholder.webp');
            $("#removeImageBtn").hide();
            $("#addBtn").html('<i class="ri-save-line me-1"></i> Create & Translate');
            $("#cardTitle").text('Add New Initiative');
            currentProductId = null;
        }

        function initPlugins() {
            if(!isSelect2Initialized) { $('.select2').select2({ width: '100%' }); isSelect2Initialized = true; }
            if(!isEditorInitialized) { 
                $('.summernote').summernote({ height: 200, placeholder: 'Enter content in English...' }); 
                isEditorInitialized = true; 
            }
        }
    });

    function showFormLoader() {
        $('#createThisForm input, #createThisForm textarea, #createThisForm button, #createThisForm select').prop('disabled', true);
        $('.summernote').summernote('disable');
        $('.select2').prop('disabled', true).trigger('change');
        
        var localeNames = {'ar': 'Arabic', 'fr': 'French', 'es': 'Spanish', 'de': 'German', 'it': 'Italian', 'pt': 'Portuguese', 'bn': 'Bengali', 'hi': 'Hindi', 'tr': 'Turkish', 'ur': 'Urdu'};
        var tickerMessages = ['Saving English...'];
        otherLocales.forEach(function(loc) { tickerMessages.push('Translating to ' + (localeNames[loc] || loc.toUpperCase()) + '...'); });
        tickerMessages.push('Finishing up...');
        var overlay = `<div class="form-loader-overlay" id="formLoader"><div class="spinner-ring"></div><div class="loader-text">Saving & Translating...</div><div class="progress-bar-container"><div class="progress-bar-fill" id="loaderProgress"></div></div><div class="loader-lang-ticker" id="loaderTicker">Preparing...</div></div>`;
        $('#addThisFormContainer .card').append(overlay);
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
        $('#createThisForm input, #createThisForm textarea, #createThisForm button, #createThisForm select').prop('disabled', false);
        $('.summernote').summernote('enable');
        $('.select2').prop('disabled', false).trigger('change');
    }

    function previewImage(event, imgSelector) {
        if (event.target.files && event.target.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) { $(imgSelector).attr('src', e.target.result); };
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>
@endsection