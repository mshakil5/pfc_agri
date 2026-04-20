@extends('admin.pages.master')
@section('title', 'Team Members')
@section('content')

<div class="container-fluid" id="newBtnSection">
    <button type="button" class="btn btn-primary mb-3" id="newBtn">Add Team Member</button>
</div>

<div class="container-fluid" id="addThisFormContainer" style="display:none;">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 id="cardTitle" class="mb-0">Add Team Member</h4>
            <small class="text-muted">Name stays English, Bio/Designation translates</small>
        </div>
        <div class="card-body">
            <form id="createThisForm">
                @csrf
                <input type="hidden" id="codeid" name="codeid">
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="e.g. James Fletcher">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Profile Image <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewImage(event, '#preview-image')">
                        <img id="preview-image" src="#" alt="" class="img-thumbnail rounded-circle mt-2" style="max-width:100px; display:none;">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone Number</label>
                        <input type="text" class="form-control" name="phone" id="phone" placeholder="+44 ...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" name="email" id="email">
                    </div>
                    
                    <div class="alert alert-info py-2">
                        <i class="ri-translate-2 me-1"></i> <small>Designation & Bio will auto-translate to {{ implode(', ', array_diff(config('translatable.locales'), ['en'])) }}</small>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Designation (EN) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="designation" id="designation" placeholder="e.g. Managing Director">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Short Bio (EN)</label>
                        <textarea class="form-control" name="bio" id="bio" rows="3" placeholder="Brief background..."></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer text-end">
            <button type="button" id="addBtn" class="btn btn-primary"><i class="ri-save-line me-1"></i> Save & Translate</button>
            <button type="button" id="FormCloseBtn" class="btn btn-light">Cancel</button>
        </div>
    </div>
</div>

<div class="container-fluid" id="contentContainer">
    <div class="card">
        <div class="card-body">
            <table id="teamTable" class="table table-bordered table-striped">
                <thead><tr><th>Sl</th><th>Image</th><th>Name</th><th>Designation</th><th>Status</th><th>Action</th></tr></thead>
            </table>
        </div>
    </div>
</div>

@endsection

@section('script')
<style>
    #addThisFormContainer .card { position: relative; overflow: hidden; }
    .form-loader-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.9); backdrop-filter: blur(2px); display: flex; flex-direction: column; align-items: center; justify-content: center; z-index: 50; }
    .spinner-ring { width: 50px; height: 50px; border: 4px solid #e5e7eb; border-top: 4px solid #3b82f6; border-radius: 50%; animation: spin 0.8s linear infinite; }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    .loader-text { margin-top: 15px; font-size: 14px; color: #374151; }
    .loader-lang-ticker { margin-top: 8px; font-size: 12px; color: #6b7280; min-height: 18px; }
    .progress-bar-container { width: 200px; height: 4px; background: #e5e7eb; border-radius: 4px; margin-top: 12px; overflow: hidden; }
    .progress-bar-fill { height: 100%; background: #3b82f6; border-radius: 4px; width: 0%; transition: width 0.3s ease; }
</style>

<script>
    var otherLocales = @json(array_values(array_diff(config('translatable.locales'), ['en'])));

    $(document).ready(function () {
        var table = $('#teamTable').DataTable({
            processing: true, serverSide: true, pageLength: 25,
            ajax: "{{ route('admin.team.index') }}",
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'image', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'designation', name: 'designation' },
                { data: 'status', orderable: false, searchable: false },
                { data: 'action', orderable: false, searchable: false }
            ]
        });

        $(document).on('change', '.toggle-status', function () {
            $.post('/admin/team-status', { _token: '{{ csrf_token() }}', id: $(this).data('id'), status: $(this).prop('checked') ? 1 : 0 }, function (d) { reloadTable('#teamTable'); showSuccess(d.message); }).fail(() => showError('Failed'));
        });

        $("#newBtn").click(function () { clearForm(); $(this).hide(); $("#addThisFormContainer").slideDown(300); });
        $("#FormCloseBtn").click(function () { $("#addThisFormContainer").slideUp(300); setTimeout(() => $("#newBtn").show(), 300); });

        $("#addBtn").click(function (e) {
            e.preventDefault();
            var formData = new FormData($('#createThisForm')[0]);
            showFormLoader();
            var url = $("#codeid").val() ? "{{ url('/admin/team-members-update') }}" : "{{ url('/admin/team-members') }}";
            
            $.ajax({ url: url, type: "POST", data: formData, contentType: false, processData: false,
                success: function (d) { hideFormLoader(); showSuccess(d.message); $("#FormCloseBtn").click(); table.draw(); },
                error: function (xhr) { hideFormLoader(); if (xhr.status === 422) { let msgs = []; Object.values(xhr.responseJSON.errors).forEach(m => msgs.push(m[0])); showError(msgs.join("<br>")); } else { showError("Something went wrong."); } }
            });
        });

        $('#contentContainer').on('click', '#EditBtn', function () {
            let id = $(this).attr('rid');
            $.get("/admin/team-members/" + id + "/edit", function (data) { populateForm(data); });
        });

        function populateForm(data) {
            $("#codeid").val(data.id); $("#name").val(data.name); $("#phone").val(data.phone);
            $("#email").val(data.email); $("#designation").val(data.designation); $("#bio").val(data.bio);
            if (data.image) { $('#preview-image').attr('src', data.image).show(); }
            $("#newBtn").hide(); $("#cardTitle").text('Update Team Member');
            $("#addBtn").html('<i class="ri-save-line me-1"></i> Update & Translate');
            $("#addThisFormContainer").slideDown(300);
        }

        function clearForm() {
            $('#createThisForm')[0].reset(); $("#codeid").val(''); $('#preview-image').hide();
            $("#cardTitle").text('Add Team Member'); $("#addBtn").html('<i class="ri-save-line me-1"></i> Save & Translate');
        }

        function showFormLoader() {
            $('#createThisForm input, #createThisForm textarea, #createThisForm button').prop('disabled', true);
            var localeNames = {'ar': 'Arabic', 'fr': 'French', 'es': 'Spanish', 'de': 'German', 'it': 'Italian', 'pt': 'Portuguese', 'bn': 'Bengali', 'hi': 'Hindi', 'tr': 'Turkish', 'ur': 'Urdu'};
            var tickerMessages = ['Saving...'];
            otherLocales.forEach(function(loc) { tickerMessages.push('Translating ' + (localeNames[loc] || loc.toUpperCase())); });
            var overlay = `<div class="form-loader-overlay" id="formLoader"><div class="spinner-ring"></div><div class="loader-text">Saving & Translating...</div><div class="progress-bar-container"><div class="progress-bar-fill" id="loaderProgress"></div></div><div class="loader-lang-ticker" id="loaderTicker"></div></div>`;
            $('#addThisFormContainer .card').append(overlay);
            var step = 0;
            var interval = setInterval(function() { if (step < tickerMessages.length) { $('#loaderProgress').css('width', Math.round(((step + 1) / tickerMessages.length) * 100) + '%'); $('#loaderTicker').text(tickerMessages[step]); step++; } else { clearInterval(interval); } }, 1000);
        }

        function hideFormLoader() { $('#formLoader').fadeOut(200, function() { $(this).remove(); }); $('#createThisForm input, #createThisForm textarea, #createThisForm button').prop('disabled', false); }
        function previewImage(event, imgSelector) { if (event.target.files && event.target.files[0]) { var reader = new FileReader(); reader.onload = function (e) { $(imgSelector).attr('src', e.target.result).show(); }; reader.readAsDataURL(event.target.files[0]); } }
    });
</script>
@endsection