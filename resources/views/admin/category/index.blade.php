@extends('admin.pages.master')
@section('title', 'Category')
@section('content')

    <div class="container-fluid" id="newBtnSection">
        <div class="row mb-3">
            <div class="col-auto">
                <button type="button" class="btn btn-primary" id="newBtn">Add New Category</button>
            </div>
        </div>
    </div>

    <div class="container-fluid" id="addThisFormContainer" style="display:none;">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1" id="cardTitle">Add New Category</h4>
                <small class="text-muted">Auto-translates on save</small>
            </div>
            <div class="card-body">
                <form id="createThisForm">
                    @csrf
                    <input type="hidden" id="codeid" name="codeid">

                    <div class="row g-3">
                        <div class="col-md-6 d-none">
                            <label class="form-label">Parent Category</label>
                            <select class="form-control select2" id="parent_id" name="parent_id">
                                <option value="">Select Parent Category</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Category Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*"
                                onchange="previewImage(event, '#preview-image')">
                            <img id="preview-image" src="#" alt="" class="img-thumbnail rounded mt-3"
                                style="max-width:300px; display:none;">
                        </div>

                        {{-- Translation Notice --}}
                        <div class="col-md-12">
                            <div class="alert alert-info py-2 mb-0">
                                <i class="ri-translate-2 me-1"></i> 
                                <small>Fill in English — will be auto-translated to {{ implode(', ', array_diff(config('translatable.locales'), ['en'])) }}</small>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Name (EN) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Category name in English">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Description (EN)</label>
                            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Category description in English"></textarea>
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
            <div class="card-header">
                <h4 class="card-title mb-0">Categories</h4>
            </div>
            <div class="card-body">
                <table id="categoryTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Name</th>
                            <th>Image</th>
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
    /* Loader Overlay Styles */
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
    var otherLocales = @json(array_values(array_diff(config('translatable.locales'), ['en'])));
    var isSelect2Initialized = false;

    function loadParentCategories() {
        $.get("{{ route('parent.categories') }}", function (response) {
            $('#parent_id').empty().append('<option value="">Select Parent Category</option>');
            response.forEach(function (cat) {
                $('#parent_id').append('<option value="' + cat.id + '">' + cat.name + '</option>');
            });
            $('#parent_id').trigger('change');
        });
    }

    $(document).ready(function () {

        $('#categoryTable').DataTable({
            processing: true, serverSide: true, pageLength: 25,
            ajax: "{{ route('allcategory') }}",
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'image', orderable: false, searchable: false },
                { data: 'status', orderable: false, searchable: false },
                { data: 'action', orderable: false, searchable: false }
            ]
        });

        $(document).on('change', '.toggle-status', function () {
            $.post('/admin/category-status', {
                _token: '{{ csrf_token() }}',
                category_id: $(this).data('id'),
                status: $(this).prop('checked') ? 1 : 0
            }, function (d) {
                reloadTable('#categoryTable');
                showSuccess(d.message);
            }).fail(() => showError('Failed to update status'));
        });

        // Toggle UI
        $("#newBtn").click(function () {
            clearForm();
            $(this).hide();
            $("#addThisFormContainer").slideDown(300, function() {
                if(!isSelect2Initialized) { $('.select2').select2({ placeholder: "Select Parent Category", allowClear: true, width: '100%' }); isSelect2Initialized = true; }
            });
            loadParentCategories();
        });

        $("#FormCloseBtn").click(function () {
            $("#addThisFormContainer").slideUp(300);
            setTimeout(() => $("#newBtn").show(), 300);
        });

        // Submit Logic (CSRF Safe)
        $("#addBtn").click(function (e) {
            e.preventDefault();
            var formData = new FormData($('#createThisForm')[0]); // Get data BEFORE disabling
            showFormLoader();

            var isUpdate = $("#codeid").val() !== '';
            var url = isUpdate ? "{{ URL::to('/admin/category-update') }}" : "{{ URL::to('/admin/category') }}";

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (d) {
                    hideFormLoader();
                    showSuccess(d.message);
                    $("#FormCloseBtn").click();
                    reloadTable('#categoryTable');
                    loadParentCategories();
                },
                error: function (xhr) {
                    hideFormLoader();
                    if (xhr.status === 422) {
                        let msgs = [];
                        Object.values(xhr.responseJSON.errors).forEach(m => msgs.push(m[0]));
                        showError(msgs.join("<br>"));
                    } else {
                        showError(xhr.responseJSON?.message ?? "Something went wrong!");
                    }
                }
            });
        });

        // Edit Logic
        $("#contentContainer").on('click', '#EditBtn', function () {
            let id = $(this).attr('rid');
            $.get("/admin/category/" + id + "/edit", function (data) {
                populateForm(data);
            });
        });

        function populateForm(data) {
            $("#codeid").val(data.id);
            $("#name").val(data.name);
            $("#description").val(data.description);
            $("#cardTitle").text('Update Category');
            $("#addBtn").html('<i class="ri-save-line me-1"></i> Update & Translate');
            
            $("#addThisFormContainer").slideDown(300, function() {
                if(!isSelect2Initialized) { $('.select2').select2({ placeholder: "Select Parent Category", allowClear: true, width: '100%' }); isSelect2Initialized = true; }
                loadParentCategories();
                setTimeout(function () {
                    $('#parent_id').val(data.parent_id || null).trigger('change');
                }, 300);
            });

            $("#newBtn").hide();

            if (data.image) {
                $('#preview-image').attr('src', data.image).show();
            }
        }

        function clearForm() {
            $('#createThisForm')[0].reset();
            $("#codeid").val('');
            $("#cardTitle").text('Add New Category');
            $("#addBtn").html('<i class="ri-save-line me-1"></i> Create & Translate');
            $('#preview-image').attr('src', '#').hide();
            if(isSelect2Initialized) $('#parent_id').val(null).trigger('change');
        }

        // --- Loader Functions ---
        function showFormLoader() {
            $('#createThisForm input, #createThisForm textarea, #createThisForm button, #createThisForm select, #addBtn, #FormCloseBtn').prop('disabled', true);
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
            }, 800);
        }

        function hideFormLoader() {
            $('#formLoader').fadeOut(200, function() { $(this).remove(); });
            $('#createThisForm input, #createThisForm textarea, #createThisForm button, #createThisForm select, #addBtn, #FormCloseBtn').prop('disabled', false);
            $('.select2').prop('disabled', false).trigger('change');
        }
    });
</script>
@endsection