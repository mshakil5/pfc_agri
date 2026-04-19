@extends('admin.pages.master')
@section('title', 'Slider')
@section('content')

    <div class="container-fluid" id="newBtnSection">
        <div class="row mb-3">
            <div class="col-auto">
                <button type="button" class="btn btn-primary" id="newBtn">Add New Slider</button>
            </div>
        </div>
    </div>

    <div class="container-fluid" id="addThisFormContainer" style="display:none;">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1" id="cardTitle">Add New Slider</h4>
                <small class="text-muted">Will auto-translate to all languages on save</small>
            </div>
            <div class="card-body">
                <form id="createThisForm">
                    @csrf
                    <input type="hidden" id="codeid" name="codeid">

                    <div class="row g-3">

                        {{-- Image --}}
                        <div class="col-md-6">
                            <label class="form-label">Slider Image</label>
                            <input type="file" class="form-control" id="image" accept="image/*"
                                onchange="previewImage(event, '#preview-image')" name="image">
                            <img id="preview-image" src="#" alt="" class="img-thumbnail rounded mt-3"
                                style="max-width:300px; display:none;">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Link</label>
                            <input type="text" class="form-control" id="link" name="link" placeholder="https://">
                        </div>

                        {{-- English Fields Only --}}
                        <div class="col-md-12">
                            <div class="alert alert-info py-2 mb-3">
                                <i class="ri-translate-2 me-1"></i> 
                                <small>Fill in English — will be auto-translated to {{ implode(', ', array_diff(config('translatable.locales'), ['en'])) }}</small>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Title (EN) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" id="title" placeholder="Enter title in English">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Sub Title (EN)</label>
                            <input type="text" class="form-control" name="sub_title" id="sub_title" placeholder="Enter subtitle in English">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Hero Badge (EN)</label>
                            <input type="text" class="form-control" name="hero_badge" id="hero_badge" placeholder="Enter hero badge in English">
                        </div>

                        {{-- Buttons --}}
                        <div class="col-md-12">
                            <label class="form-label"><b>Buttons</b></label>
                            <div id="buttons-container">
                                <div class="row g-2 mb-2 button-row">
                                    <div class="col-md-5">
                                        <input type="text" name="buttons[0][label]"
                                               class="form-control" placeholder="Button Label">
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" name="buttons[0][link]"
                                               class="form-control" placeholder="Button Link">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger w-100 remove-btn">X</button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-dark btn-sm mt-2" id="addButtonBtn">+ Add Button</button>
                        </div>

                        {{-- Stat Cards --}}
                        <div class="col-md-12">
                            <label class="form-label"><b>Stat Cards</b></label>
                            <div id="statcard-container">
                                <div class="row g-2 mb-2 button-row">
                                    <div class="col-md-5">
                                        <input type="text" name="stat_card[0][value]"
                                               class="form-control" placeholder="Value (e.g. 500+)">
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" name="stat_card[0][title]"
                                               class="form-control" placeholder="Title">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger w-100 remove-btn">X</button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-dark btn-sm mt-2" id="addStatcardBtn">+ Add Stat Card</button>
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
        <ul class="nav nav-tabs mb-3" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#list" role="tab">Slider List</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#sort" role="tab">Sort Sliders</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="list" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <table id="sliderTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Title</th>
                                    <th>Image</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="sort" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Sort Sliders</h4>
                        <small class="text-muted">Drag & drop to reorder</small>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead><tr><th>Sl</th><th>Title</th></tr></thead>
                            <tbody id="sortable">
                                @foreach ($sliders as $slider)
                                    <tr data-id="{{ $slider->id }}">
                                        <td>{{ $slider->serial }}</td>
                                        <td>{{ $slider->translateOrNew(app()->getLocale())->title }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<style>
    /* Loader Overlay */
    .form-loader-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(2px);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 50;
        border-radius: 0.375rem;
    }

    .spinner-ring {
        width: 50px;
        height: 50px;
        border: 4px solid #e5e7eb;
        border-top: 4px solid #3b82f6;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .loader-text {
        margin-top: 15px;
        font-size: 14px;
        color: #374151;
        font-weight: 500;
    }

    .loader-lang-ticker {
        margin-top: 8px;
        font-size: 12px;
        color: #6b7280;
        min-height: 18px;
    }

    .form-loader-overlay .progress-bar-container {
        width: 200px;
        height: 4px;
        background: #e5e7eb;
        border-radius: 4px;
        margin-top: 12px;
        overflow: hidden;
    }

    .form-loader-overlay .progress-bar-fill {
        height: 100%;
        background: #3b82f6;
        border-radius: 4px;
        width: 0%;
        transition: width 0.3s ease;
    }

    #addThisFormContainer .card {
        position: relative;
        overflow: hidden;
    }
</style>

<script>
var buttonIndex = 1;
var statCardIndex = 1;
var otherLocales = @json(array_values(array_diff(config('translatable.locales'), ['en'])));

 $(document).ready(function () {

    // Sortable
    $("#sortable").sortable({
        placeholder: "ui-state-highlight",
        cursor: "grab",
        forcePlaceholderSize: true,
        opacity: 0.8,
        update: function () {
            var order = $(this).sortable('toArray', { attribute: 'data-id' });
            $.ajax({
                url: "{{ route('sliders.updateOrder') }}",
                method: "POST",
                data: { _token: '{{ csrf_token() }}', order: order },
                success: function (res) {
                    showSuccess(res.message);
                    $("#sortable tr").each(function (i) { $(this).find("td:first").text(i + 1); });
                    reloadTable('#sliderTable');
                },
                error: function (xhr) { showError(xhr.responseJSON?.message ?? "Something went wrong!"); }
            });
        }
    });

    // DataTable
    var table = $('#sliderTable').DataTable({
        processing: true, serverSide: true, pageLength: 25,
        ajax: "{{ route('allslider') }}",
        columns: [
            { data: 'serial', name: 'serial', orderable: false, searchable: false },
            { data: 'title',  name: 'title' },
            { data: 'image',  name: 'image',  orderable: false, searchable: false },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    // Toggle status
    $(document).on('change', '.toggle-status', function () {
        var slider_id = $(this).data('id');
        var status = $(this).prop('checked') ? 1 : 0;
        $.post('/admin/slider-status', { _token: '{{ csrf_token() }}', slider_id, status }, function (d) {
            reloadTable('#sliderTable');
            showSuccess(d.message);
        }).fail(() => showError('Failed to update status'));
    });

    // New / Close
    $("#newBtn").click(function () {
        clearForm();
        $("#addThisFormContainer").slideDown(300);
        $(this).hide();
    });

    $("#FormCloseBtn").click(function () {
        $("#addThisFormContainer").slideUp(300);
        setTimeout(() => $("#newBtn").show(), 300);
    });

    // Save with Loader
    $("#addBtn").click(function (e) {
        e.preventDefault();
        
        // 1. Create FormData FIRST (before disabling inputs)
        var formData = new FormData($('#createThisForm')[0]);
        
        // 2. Then show loader (which disables inputs)
        showFormLoader();

        $.ajax({
            url: "{{ route('slider.store') }}",
            type: "POST",
            data: formData,  // 3. Use the already-created formData
            contentType: false,
            processData: false,
            success: function (d) {
                hideFormLoader();
                showSuccess(d.message);
                $("#FormCloseBtn").click();
                table.ajax.reload();
            },
            error: function (xhr) {
                hideFormLoader();
                if (xhr.status === 422) {
                    let msgs = [];
                    Object.values(xhr.responseJSON.errors).forEach(m => msgs.push(m[0]));
                    showError(msgs.join("<br>"));
                } else {
                    showError(xhr.responseJSON?.message ?? "Something went wrong.");
                }
            }
        });
    });

    // Edit
    $("#contentContainer").on('click', '#EditBtn', function () {
        let id = $(this).attr('rid');
        $.get("/admin/slider/" + id + "/edit", function (data) {
            populateForm(data);
        });
    });

    // Add button row
    $("#addButtonBtn").click(function () {
        $("#buttons-container").append(buildButtonRow(buttonIndex++, '', ''));
    });

    // Add stat card row
    $("#addStatcardBtn").click(function () {
        $("#statcard-container").append(buildStatCardRow(statCardIndex++, '', ''));
    });

    // Remove row
    $(document).on('click', '.remove-btn', function () {
        $(this).closest('.button-row').remove();
    });

    // ---- Loader Functions ----

    function showFormLoader() {
        // Disable form inputs
        $('#createThisForm input, #createThisForm button, #createThisForm select').prop('disabled', true);
        $('#addBtn, #FormCloseBtn').prop('disabled', true);

        var localeNames = {
            'ar': 'Arabic', 'fr': 'French', 'es': 'Spanish', 'de': 'German',
            'it': 'Italian', 'pt': 'Portuguese', 'ru': 'Russian', 'zh': 'Chinese',
            'ja': 'Japanese', 'ko': 'Korean', 'bn': 'Bengali', 'hi': 'Hindi',
            'tr': 'Turkish', 'ur': 'Urdu'
        };

        var totalLocales = otherLocales.length;

        var overlay = `
            <div class="form-loader-overlay" id="formLoader">
                <div class="spinner-ring"></div>
                <div class="loader-text">Saving & Translating...</div>
                <div class="progress-bar-container">
                    <div class="progress-bar-fill" id="loaderProgress"></div>
                </div>
                <div class="loader-lang-ticker" id="loaderTicker">Preparing...</div>
            </div>
        `;

        $('#addThisFormContainer .card').append(overlay);

        // Animate progress & language ticker
        var step = 0;
        var tickerMessages = ['Saving English...'];

        otherLocales.forEach(function(loc) {
            tickerMessages.push('Translating to ' + (localeNames[loc] || loc.toUpperCase()) + '...');
        });
        tickerMessages.push('Finishing up...');

        var interval = setInterval(function() {
            if (step < tickerMessages.length) {
                var progress = Math.round(((step + 1) / tickerMessages.length) * 100);
                $('#loaderProgress').css('width', progress + '%');
                $('#loaderTicker').text(tickerMessages[step]);
                step++;
            } else {
                clearInterval(interval);
                $('#loaderTicker').text('Almost done...');
            }
        }, 800); // Adjust timing based on your translation speed
    }

    function hideFormLoader() {
        $('#formLoader').fadeOut(200, function() {
            $(this).remove();
        });
        // Re-enable form inputs
        $('#createThisForm input, #createThisForm button, #createThisForm select').prop('disabled', false);
        $('#addBtn, #FormCloseBtn').prop('disabled', false);
    }

    // ---- Form Helpers ----

    function populateForm(data) {
        $("#codeid").val(data.id);
        $("#link").val(data.link);
        $("#title").val(data.title);
        $("#sub_title").val(data.sub_title);
        $("#hero_badge").val(data.hero_badge);
        $("#cardTitle").text('Update Slider');
        $("#addBtn").html('<i class="ri-save-line me-1"></i> Update & Translate');

        if (data.image) {
            $('#preview-image').attr('src', '/images/slider/' + data.image).show();
        }

        let btnContainer = $("#buttons-container");
        btnContainer.empty();
        let buttons = data.buttons || [];
        if (buttons.length > 0) {
            buttons.forEach((btn, i) => btnContainer.append(buildButtonRow(i, btn.label, btn.link)));
            buttonIndex = buttons.length;
        } else {
            btnContainer.append(buildButtonRow(0, '', ''));
            buttonIndex = 1;
        }

        let scContainer = $("#statcard-container");
        scContainer.empty();
        let statCards = data.stat_card || [];
        if (statCards.length > 0) {
            statCards.forEach((sc, i) => scContainer.append(buildStatCardRow(i, sc.value, sc.title)));
            statCardIndex = statCards.length;
        } else {
            scContainer.append(buildStatCardRow(0, '', ''));
            statCardIndex = 1;
        }

        $("#addThisFormContainer").slideDown(300);
        $("#newBtn").hide();
    }

    function clearForm() {
        $('#createThisForm')[0].reset();
        $("#codeid").val('');
        $("#cardTitle").text('Add New Slider');
        $("#addBtn").html('<i class="ri-save-line me-1"></i> Create & Translate');
        $('#preview-image').attr('src', '#').hide();

        buttonIndex = 1;
        statCardIndex = 1;
        $("#buttons-container").html(buildButtonRow(0, '', ''));
        $("#statcard-container").html(buildStatCardRow(0, '', ''));
    }

    function buildButtonRow(index, label, link) {
        return `<div class="row g-2 mb-2 button-row">
            <div class="col-md-5">
                <input type="text" name="buttons[${index}][label]" value="${label ?? ''}" class="form-control" placeholder="Button Label">
            </div>
            <div class="col-md-5">
                <input type="text" name="buttons[${index}][link]" value="${link ?? ''}" class="form-control" placeholder="Button Link">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger w-100 remove-btn">X</button>
            </div>
        </div>`;
    }

    function buildStatCardRow(index, value, title) {
        return `<div class="row g-2 mb-2 button-row">
            <div class="col-md-5">
                <input type="text" name="stat_card[${index}][value]" value="${value ?? ''}" class="form-control" placeholder="Value (e.g. 500+)">
            </div>
            <div class="col-md-5">
                <input type="text" name="stat_card[${index}][title]" value="${title ?? ''}" class="form-control" placeholder="Title">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger w-100 remove-btn">X</button>
            </div>
        </div>`;
    }
});

function previewImage(event, imgSelector) {
    if (event.target.files && event.target.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) { $(imgSelector).attr('src', e.target.result).show(); };
        reader.readAsDataURL(event.target.files[0]);
    }
}
</script>
@endsection