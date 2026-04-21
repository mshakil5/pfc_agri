@extends('admin.pages.master')
@section('title', 'Awards')
@section('content')

<div class="container-fluid" id="newBtnSection">
    <button type="button" class="btn btn-primary mb-3" id="newBtn">Add New Award</button>
</div>

<div class="container-fluid" id="addThisFormContainer" style="display: none;">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 id="cardTitle" class="mb-0">Add New Award</h4>
            <small class="text-muted">Auto-translates on save</small>
        </div>
        <div class="card-body">
            <form id="createThisForm">
                @csrf
                <input type="hidden" id="codeid" name="codeid">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Award Image <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*"
                               onchange="previewImage(event, '#preview-image')">
                        <img id="preview-image" src="#" alt="Preview" class="img-thumbnail mt-2" style="max-width:150px; display:none;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Year</label>
                        <input type="number" class="form-control" name="year" id="year" placeholder="2025">
                    </div>
                </div>

                <div class="alert alert-info py-2 mb-3">
                    <i class="ri-translate-2 me-1"></i> 
                    <small>Fill in English — will be auto-translated to {{ implode(', ', array_diff(config('translatable.locales'), ['en'])) }}</small>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Title (EN) <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Organization (EN)</label> 
                        <input type="text" name="organization" id="organization" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Tag (EN)</label>
                        <input type="text" name="tag" id="tag" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Description (EN)</label>
                        <textarea name="description" id="description" class="form-control summernote"></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer text-end">
            <button type="button" id="addBtn" class="btn btn-primary">
                <i class="ri-save-line me-1"></i> Save & Translate
            </button>
            <button type="button" id="FormCloseBtn" class="btn btn-light">Cancel</button>
        </div>
    </div>
</div>

<div class="container-fluid" id="contentContainer">
    <div class="card">
        <div class="card-body">
            <table id="awardTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Image</th>
                        <th>Year</th>
                        <th>Title (Current Language)</th>
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
    .spinner-ring {
        width: 50px; height: 50px; border: 4px solid #e5e7eb;
        border-top: 4px solid #3b82f6; border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    .loader-text { margin-top: 15px; font-size: 14px; color: #374151; font-weight: 500; }
    .loader-lang-ticker { margin-top: 8px; font-size: 12px; color: #6b7280; min-height: 18px; }
    .progress-bar-container { width: 200px; height: 4px; background: #e5e7eb; border-radius: 4px; margin-top: 12px; overflow: hidden; }
    .progress-bar-fill { height: 100%; background: #3b82f6; border-radius: 4px; width: 0%; transition: width 0.3s ease; }
</style>

<script>
    var otherLocales = @json(array_values(array_diff(config('translatable.locales'), ['en'])));
    var isEditorInitialized = false;

    $(document).ready(function() {

        var table = $('#awardTable').DataTable({
            processing: true, serverSide: true,
            ajax: "{{ route('admin.awards') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'image', name: 'image', orderable: false, searchable: false },
                { data: 'year', name: 'year' },
                { data: 'title', name: 'title' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        // Submit Logic (CSRF Safe)
        $("#addBtn").click(function(e) {
            e.preventDefault();
            var formData = new FormData($('#createThisForm')[0]); 
            showFormLoader();

            let id = $("#codeid").val();
            let url = id ? "{{ url('/admin/awards-update') }}" : "{{ url('/admin/awards') }}";
            
            $.ajax({
                url: url, type: "POST", data: formData,
                contentType: false, processData: false,
                success: function(d) {
                    hideFormLoader();
                    showSuccess(d.message);
                    $("#FormCloseBtn").click();
                    table.draw();
                },
                error: function(xhr) {
                    hideFormLoader();
                    if (xhr.status === 422) {
                        let msgs = []; Object.values(xhr.responseJSON.errors).forEach(m => msgs.push(m[0]));
                        showError(msgs.join("<br>"));
                    } else { showError(xhr.responseJSON?.message || "Something went wrong."); }
                }
            });
        });

        // Edit Logic
        $('#contentContainer').on('click', '#EditBtn', function() {
            let id = $(this).attr('rid');
            $.get("/admin/awards/" + id + "/edit", function(data) {
                $("#codeid").val(data.id);
                $("#year").val(data.year);
                $("#title").val(data.title);
                $("#organization").val(data.organization);
                $("#tag").val(data.tag);

                $("#addThisFormContainer").slideDown(300, function() {
                    if(!isEditorInitialized) { initSummernote(); isEditorInitialized = true; }
                    $('#description').summernote('code', data.description);
                });
                
                // Handle Image Preview
                if (data.image) {
                    $('#preview-image').attr('src', data.image).show();
                }

                $("#newBtn").hide();
                $("#cardTitle").text('Edit Award');
                $("#addBtn").html('<i class="ri-save-line me-1"></i> Update & Translate');
            });
        });
        
        // Toggle Buttons
        $("#newBtn").click(function() {
            clearForm();
            $("#addThisFormContainer").slideDown(300, function() {
                if(!isEditorInitialized) { initSummernote(); isEditorInitialized = true; }
            });
            $(this).hide();
        });

        $("#FormCloseBtn").click(function() {
            $("#addThisFormContainer").slideUp(300);
            setTimeout(() => $("#newBtn").show(), 300);
        });
    });

    // --- Plugin Initializers ---
    function initSummernote() {
        $('.summernote').summernote({
            height: 200,
            placeholder: 'Write award description in English here...',
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });
    }

    function clearForm() {
        $('#createThisForm')[0].reset();
        $("#codeid").val('');
        $('#preview-image').attr('src', '#').hide();
        if(isEditorInitialized) { $('#description').summernote('code', ''); }
        $("#cardTitle").text('Add New Award');
        $("#addBtn").html('<i class="ri-save-line me-1"></i> Save & Translate');
    }

    // --- Loader Functions ---
    function showFormLoader() {
        $('#createThisForm input, #createThisForm textarea, #createThisForm button, #createThisForm select, #addBtn, #FormCloseBtn').prop('disabled', true);
        $('.summernote').summernote('disable');
        
        var localeNames = {'ar': 'Arabic', 'fr': 'French', 'es': 'Spanish', 'de': 'German', 'it': 'Italian', 'pt': 'Portuguese', 'bn': 'Bengali', 'hi': 'Hindi', 'tr': 'Turkish', 'ur': 'Urdu'};
        var tickerMessages = ['Saving English...'];
        otherLocales.forEach(function(loc) { tickerMessages.push('Translating to ' + (localeNames[loc] || loc.toUpperCase()) + '...'); });
        tickerMessages.push('Finishing up...');

        var overlay = `<div class="form-loader-overlay" id="formLoader">
            <div class="spinner-ring"></div>
            <div class="loader-text">Saving & Translating...</div>
            <div class="progress-bar-container"><div class="progress-bar-fill" id="loaderProgress"></div></div>
            <div class="loader-lang-ticker" id="loaderTicker">Preparing...</div>
        </div>`;
        
        $('#addThisFormContainer .card').append(overlay);

        var step = 0;
        var interval = setInterval(function() {
            if (step < tickerMessages.length) {
                var progress = Math.round(((step + 1) / tickerMessages.length) * 100);
                $('#loaderProgress').css('width', progress + '%');
                $('#loaderTicker').text(tickerMessages[step]);
                step++;
            } else { clearInterval(interval); $('#loaderTicker').text('Almost done...'); }
        }, 1000);
    }

    function hideFormLoader() {
        $('#formLoader').fadeOut(200, function() { $(this).remove(); });
        $('#createThisForm input, #createThisForm textarea, #createThisForm button, #createThisForm select, #addBtn, #FormCloseBtn').prop('disabled', false);
        $('.summernote').summernote('enable');
    }

    function previewImage(event, imgSelector) {
        if (event.target.files && event.target.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) { $(imgSelector).attr('src', e.target.result).show(); };
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>
@endsection