@extends('admin.pages.master')
@section('title', 'Products')
@section('content')

    <div class="container-fluid" id="newBtnSection">
        <div class="row mb-3">
            <div class="col-auto">
                <button type="button" class="btn btn-primary" id="newBtn">Add New Product</button>
            </div>
        </div>
    </div>

    <div class="container-fluid" id="addThisFormContainer" style="display:none;">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1" id="cardTitle">Add New Product</h4>
                <small class="text-muted">Auto-translates on save</small>
            </div>
            <div class="card-body">
                <form id="createThisForm">
                    @csrf
                    <input type="hidden" id="codeid" name="codeid">

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-control select2" id="category_id" name="category_id" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->translateOrNew(app()->getLocale())->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tag</label>
                            <select class="form-control select2" id="tag_id" name="tag_id">
                                <option value="">Select Tag</option>
                                @foreach ($tags as $tag)
                                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Price (£)</label>
                            <input type="number" step="0.01" class="form-control" name="price" id="price" placeholder="0.00">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Main Thumbnail <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewImage(event, '#preview-image')">
                            <img id="preview-image" src="#" alt="" class="img-thumbnail rounded mt-2" style="max-width:150px; display:none;">
                        </div>

                        {{-- Gallery Images --}}
                        <div class="col-md-6">
                            <label class="form-label">Gallery Images</label>
                            <input type="file" class="form-control" id="new_images_input" name="new_images[]" accept="image/*" multiple>
                            <div id="existing-gallery-container" class="d-flex flex-wrap gap-2 mt-2">
                                <!-- Existing images will load here via JS -->
                            </div>
                        </div>

                        <div class="col-12"><hr></div>
                        
                        <div class="col-12">
                            <div class="alert alert-info py-2 mb-0">
                                <i class="ri-translate-2 me-1"></i> 
                                <small>Fill in English — will be auto-translated to {{ implode(', ', array_diff(config('translatable.locales'), ['en'])) }}</small>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Product Title (EN) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" id="title">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Short Description (EN)</label>
                            <textarea class="form-control" name="short_description" id="short_description" rows="2"></textarea>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Full Description (EN)</label>
                            <textarea class="form-control" id="long_description" name="long_description"></textarea>
                        </div>

                        {{-- Features List --}}
                        <div class="col-md-12">
                            <label class="form-label"><b>Features List (EN)</b> <small class="text-muted">(Translates per language)</small></label>
                            <div id="features-container">
                                <div class="row g-2 mb-2 feature-row">
                                    <div class="col-md-11">
                                        <input type="text" name="features[]" class="form-control" placeholder="e.g. Waterproof, Lightweight, 1 Year Warranty">
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger w-100 remove-feature-btn">X</button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-dark btn-sm mt-2" id="addFeatureBtn">+ Add Feature</button>
                        </div>

                        {{-- Technical Specs --}}
                        <div class="col-md-12 mt-4">
                            <label class="form-label"><b>Technical Specs (EN)</b> <small class="text-muted">(Translates per language)</small></label>
                            <textarea class="form-control" id="specs" name="specs"></textarea>
                        </div>

                        {{-- Downloads --}}
                        <div class="col-md-12 mt-4">
                            <label class="form-label"><b>Downloads (PDFs/Docs)</b></label>
                            <input type="file" class="form-control" id="new_downloads_input" name="new_downloads[]" accept=".pdf,.doc,.docx,.xls,.xlsx" multiple>
                            <div id="existing-downloads-container" class="mt-2">
                                <!-- Existing downloads will load here via JS -->
                            </div>
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

    <div class="container-fluid" id="contentContainer">
        <div class="card">
            <div class="card-body">
                <table id="productTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Price</th>
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
    .form-loader-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.9); backdrop-filter: blur(2px); display: flex; flex-direction: column; align-items: center; justify-content: center; z-index: 50; border-radius: 0.375rem; }
    .spinner-ring { width: 50px; height: 50px; border: 4px solid #e5e7eb; border-top: 4px solid #3b82f6; border-radius: 50%; animation: spin 0.8s linear infinite; }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    .loader-text { margin-top: 15px; font-size: 14px; color: #374151; font-weight: 500; }
    .loader-lang-ticker { margin-top: 8px; font-size: 12px; color: #6b7280; min-height: 18px; }
    .progress-bar-container { width: 200px; height: 4px; background: #e5e7eb; border-radius: 4px; margin-top: 12px; overflow: hidden; }
    .progress-bar-fill { height: 100%; background: #3b82f6; border-radius: 4px; width: 0%; transition: width 0.3s ease; }
    .gallery-thumb { position: relative; width: 80px; height: 80px; }
    .gallery-thumb img { width: 100%; height: 100%; object-fit: cover; border-radius: 4px; border: 1px solid #ddd; }
    .gallery-thumb .remove-gallery-btn { position: absolute; top: -6px; right: -6px; background: #dc3545; color: white; border-radius: 50%; width: 20px; height: 20px; font-size: 10px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; }
    .download-item { transition: 0.2s; } .download-item:hover { background: #f1f5f9 !important; }
</style>

<script>
    var otherLocales = @json(array_values(array_diff(config('translatable.locales'), ['en'])));
    var isEditorInitialized = false;
    var isSelect2Initialized = false;

    $(document).ready(function () {

        var table = $('#productTable').DataTable({
            processing: true, serverSide: true, pageLength: 25,
            ajax: "{{ route('allproducts') }}",
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'image', orderable: false, searchable: false },
                { data: 'title', name: 'title' },
                { data: 'category_name', name: 'category_name' },
                { data: 'price', name: 'price' },
                { data: 'status', orderable: false, searchable: false },
                { data: 'action', orderable: false, searchable: false }
            ]
        });

        $(document).on('change', '.toggle-status', function () {
            $.post('/admin/products-status', {
                _token: '{{ csrf_token() }}', product_id: $(this).data('id'), status: $(this).prop('checked') ? 1 : 0
            }, function (d) { reloadTable('#productTable'); showSuccess(d.message); }).fail(() => showError('Failed'));
        });

        $("#newBtn").click(function () { clearForm(); $(this).hide(); $("#addThisFormContainer").slideDown(300, initPlugins); });
        $("#FormCloseBtn").click(function () { $("#addThisFormContainer").slideUp(300); setTimeout(() => $("#newBtn").show(), 300); });

        $("#addBtn").click(function (e) {
            e.preventDefault();
            if(isEditorInitialized) { 
                $('#long_description').val($('#long_description').summernote('code'));
                $('#specs').val($('#specs').summernote('code')); 
            }
            var formData = new FormData($('#createThisForm')[0]);
            showFormLoader();

            var isUpdate = $("#codeid").val() !== '';
            var url = isUpdate ? "{{ URL::to('/admin/products-update') }}" : "{{ URL::to('/admin/products') }}";

            $.ajax({
                url: url, type: "POST", data: formData, contentType: false, processData: false,
                success: function (d) { hideFormLoader(); showSuccess(d.message); $("#FormCloseBtn").click(); table.draw(); },
                error: function (xhr) {
                    hideFormLoader();
                    if (xhr.status === 422) { let msgs = []; Object.values(xhr.responseJSON.errors).forEach(m => msgs.push(m[0])); showError(msgs.join("<br>")); }
                    else { showError(xhr.responseJSON?.message ?? "Something went wrong!"); }
                }
            });
        });

        $("#contentContainer").on('click', '#EditBtn', function () {
            let id = $(this).attr('rid');
            $.get("/admin/products/" + id + "/edit", function (data) { populateForm(data); });
        });

        $("#addFeatureBtn").click(function () {
            var $row = $('<div class="row g-2 mb-2 feature-row"></div>');
            $row.append('<div class="col-md-11"><input type="text" name="features[]" class="form-control" placeholder="e.g. Waterproof"></div>');
            $row.append('<div class="col-md-1"><button type="button" class="btn btn-danger w-100 remove-feature-btn">X</button></div>');
            $('#features-container').append($row);
        });

        $(document).on('click', '.remove-feature-btn', function () { $(this).closest('.feature-row').remove(); });

        // Remove Gallery Image
        $(document).on('click', '.remove-gallery-btn', function(e) {
            e.preventDefault();
            var thumb = $(this).closest('.gallery-thumb');
            thumb.find('input[name="existing_images[]"]').attr('name', 'delete_images[]');
            thumb.remove();
        });

        // Remove Download File
        $(document).on('click', '.remove-download-btn', function(e) {
            e.preventDefault();
            var item = $(this).closest('.download-item');
            var path = item.data('path');
            var $delInput = $('<input type="hidden" name="delete_downloads[]" value="">').val(path);
            $('#createThisForm').append($delInput);
            item.remove();
        });

        // --- Helpers ---
        function populateForm(data) {
            $("#codeid").val(data.id);
            $("#title").val(data.title);
            $("#short_description").val(data.short_description);
            $("#price").val(data.price);
            
            $("#addThisFormContainer").slideDown(300, function() {
                initPlugins();
                $("#category_id").val(data.category_id || null).trigger('change');
                $("#tag_id").val(data.tag_id || null).trigger('change');
                if(isEditorInitialized) { 
                    $('#long_description').summernote('code', data.long_description || ''); 
                    $('#specs').summernote('code', data.specs || ''); 
                }
            });

            if (data.image) { $('#preview-image').attr('src', data.image).show(); }

            // Gallery Images
            $('#existing-gallery-container').empty();
            if (data.images && data.images.length > 0) {
                data.images.forEach(function(img) {
                    var $thumb = $('<div class="gallery-thumb"></div>').data('path', img);
                    $thumb.append('<img src="" alt="Gallery">').find('img').attr('src', img);
                    $thumb.append('<button type="button" class="remove-gallery-btn">X</button>');
                    $thumb.append('<input type="hidden" name="existing_images[]" value="">').find('input').val(img);
                    $('#existing-gallery-container').append($thumb);
                });
            }

            // PDF Downloads
            $('#existing-downloads-container').empty();
            if (data.downloads && data.downloads.length > 0) {
                data.downloads.forEach(function(dl) {
                    var $item = $('<div class="download-item d-flex align-items-center justify-content-between border p-2 rounded mb-2 bg-light"></div>').data('path', dl.path);
                    var $text = $('<div class="d-flex align-items-center"></div>');
                    $text.append('<i class="fas fa-file-pdf text-danger me-2"></i>');
                    var $name = $('<span class="fw-bold small"></span>').text(dl.name);
                    $text.append($name);
                    $item.append($text);
                    $item.append('<button type="button" class="btn btn-sm btn-danger remove-download-btn">X</button>');
                    $item.append($('<input type="hidden" name="existing_downloads[]" value="">').val(dl.path));
                    $('#existing-downloads-container').append($item);
                });
            }

            // Features
            $('#features-container').empty();
            if (data.features && data.features.length > 0) {
                data.features.forEach(function(feat) {
                    var $row = $('<div class="row g-2 mb-2 feature-row"></div>');
                    $row.append('<div class="col-md-11"><input type="text" name="features[]" class="form-control"></div>');
                    $row.find('input').val(feat);
                    $row.append('<div class="col-md-1"><button type="button" class="btn btn-danger w-100 remove-feature-btn">X</button></div>');
                    $('#features-container').append($row);
                });
            } else {
                $('#addFeatureBtn').click();
            }

            $("#newBtn").hide();
            $("#cardTitle").text('Update Product');
            $("#addBtn").html('<i class="ri-save-line me-1"></i> Update & Translate');
        }

        function clearForm() {
            $('#createThisForm')[0].reset();
            $("#codeid").val(''); $("#preview-image").hide();
            $('#existing-gallery-container').empty();
            $('#existing-downloads-container').empty();
            
            $('#features-container').empty();
            var $row = $('<div class="row g-2 mb-2 feature-row"></div>');
            $row.append('<div class="col-md-11"><input type="text" name="features[]" class="form-control" placeholder="e.g. Waterproof"></div>');
            $row.append('<div class="col-md-1"><button type="button" class="btn btn-danger w-100 remove-feature-btn">X</button></div>');
            $('#features-container').append($row);
            
            if(isSelect2Initialized) { $("#category_id").val(null).trigger('change'); $("#tag_id").val(null).trigger('change'); }
            if(isEditorInitialized) { $('#long_description').summernote('code', ''); $('#specs').summernote('code', ''); }
            
            $("#cardTitle").text('Add New Product');
            $("#addBtn").html('<i class="ri-save-line me-1"></i> Create & Translate');
        }

        function initPlugins() {
            if(!isSelect2Initialized) { $('.select2').select2({ width: '100%' }); isSelect2Initialized = true; }
            if(!isEditorInitialized) { 
                $('#long_description').summernote({ height: 250 }); 
                $('#specs').summernote({ height: 200 }); 
                isEditorInitialized = true; 
            }
        }

        function showFormLoader() {
            $('#createThisForm input, #createThisForm textarea, #createThisForm button, #createThisForm select, #addBtn, #FormCloseBtn').prop('disabled', true);
            if(isEditorInitialized) { $('#long_description').summernote('disable'); $('#specs').summernote('disable'); }
            $('.select2').prop('disabled', true).trigger('change');
            var localeNames = {'ar': 'Arabic', 'fr': 'French', 'es': 'Spanish', 'de': 'German', 'it': 'Italian', 'pt': 'Portuguese', 'bn': 'Bengali', 'hi': 'Hindi', 'tr': 'Turkish', 'ur': 'Urdu'};
            var tickerMessages = ['Saving English...'];
            otherLocales.forEach(function(loc) { tickerMessages.push('Translating to ' + (localeNames[loc] || loc.toUpperCase()) + '...'); });
            tickerMessages.push('Finishing up...');
            var overlay = `<div class="form-loader-overlay" id="formLoader"><div class="spinner-ring"></div><div class="loader-text">Saving & Translating...</div><div class="progress-bar-container"><div class="progress-bar-fill" id="loaderProgress"></div></div><div class="loader-lang-ticker" id="loaderTicker">Preparing...</div></div>`;
            $('#addThisFormContainer .card').append(overlay);
            var step = 0;
            var interval = setInterval(function() {
                if (step < tickerMessages.length) { $('#loaderProgress').css('width', Math.round(((step + 1) / tickerMessages.length) * 100) + '%'); $('#loaderTicker').text(tickerMessages[step]); step++; }
                else { clearInterval(interval); $('#loaderTicker').text('Almost done...'); }
            }, 1200);
        }

        function hideFormLoader() {
            $('#formLoader').fadeOut(200, function() { $(this).remove(); });
            $('#createThisForm input, #createThisForm textarea, #createThisForm button, #createThisForm select, #addBtn, #FormCloseBtn').prop('disabled', false);
            if(isEditorInitialized) { $('#long_description').summernote('enable'); $('#specs').summernote('enable'); }
            $('.select2').prop('disabled', false).trigger('change');
        }

        function previewImage(event, imgSelector) {
            if (event.target.files && event.target.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) { $(imgSelector).attr('src', e.target.result).show(); };
                reader.readAsDataURL(event.target.files[0]);
            }
        }
    });
</script>
@endsection