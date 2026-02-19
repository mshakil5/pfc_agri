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
            </div>
            <div class="card-body">
                <form id="createThisForm">
                    @csrf
                    <input type="hidden" id="codeid" name="codeid">

                    <div class="row g-3">

                        {{-- Image (shared across locales) --}}
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

                        {{-- Language Tabs --}}
                        <div class="col-md-12">
                            <ul class="nav nav-tabs nav-tabs-custom nav-success mb-3" role="tablist">
                                @foreach(config('translatable.locales') as $index => $locale)
                                    <li class="nav-item">
                                        <a class="nav-link {{ $index == 0 ? 'active' : '' }}"
                                           data-bs-toggle="tab" href="#slider-tab-{{ $locale }}" role="tab">
                                            {{ strtoupper($locale) }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="tab-content">
                                @foreach(config('translatable.locales') as $index => $locale)
                                    <div class="tab-pane {{ $index == 0 ? 'active' : '' }}"
                                         id="slider-tab-{{ $locale }}" role="tabpanel">
                                        <div class="row g-3">

                                            <div class="col-md-12">
                                                <label class="form-label">Title ({{ strtoupper($locale) }}) <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control"
                                                       name="{{ $locale }}[title]" id="{{ $locale }}_title">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Sub Title ({{ strtoupper($locale) }})</label>
                                                <input type="text" class="form-control"
                                                       name="{{ $locale }}[sub_title]" id="{{ $locale }}_sub_title">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Hero Badge ({{ strtoupper($locale) }})</label>
                                                <input type="text" class="form-control"
                                                       name="{{ $locale }}[hero_badge]" id="{{ $locale }}_hero_badge">
                                            </div>

                                            {{-- Buttons per locale --}}
                                            <div class="col-md-12">
                                                <label class="form-label"><b>Buttons ({{ strtoupper($locale) }})</b></label>
                                                <div id="buttons-container-{{ $locale }}">
                                                    <div class="row g-2 mb-2 button-row">
                                                        <div class="col-md-5">
                                                            <input type="text" name="{{ $locale }}[buttons][0][label]"
                                                                   class="form-control" placeholder="Button Label">
                                                        </div>
                                                        <div class="col-md-5">
                                                            <input type="text" name="{{ $locale }}[buttons][0][link]"
                                                                   class="form-control" placeholder="Button Link">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="button" class="btn btn-danger w-100 remove-btn">X</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-dark btn-sm mt-2 add-button-btn"
                                                        data-locale="{{ $locale }}">+ Add Button</button>
                                            </div>

                                            {{-- Stat Cards per locale --}}
                                            <div class="col-md-12">
                                                <label class="form-label"><b>Stat Cards ({{ strtoupper($locale) }})</b></label>
                                                <div id="statcard-container-{{ $locale }}">
                                                    <div class="row g-2 mb-2 button-row">
                                                        <div class="col-md-5">
                                                            <input type="text" name="{{ $locale }}[stat_card][0][value]"
                                                                   class="form-control" placeholder="Value (e.g. 500+)">
                                                        </div>
                                                        <div class="col-md-5">
                                                            <input type="text" name="{{ $locale }}[stat_card][0][title]"
                                                                   class="form-control" placeholder="Title">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="button" class="btn btn-danger w-100 remove-btn">X</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-dark btn-sm mt-2 add-statcard-btn"
                                                        data-locale="{{ $locale }}">+ Add Stat Card</button>
                                            </div>

                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="card-footer text-end">
                <button type="button" id="addBtn" class="btn btn-primary">Create</button>
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
<script>
// Track dynamic row indices per locale
var buttonIndex   = {};
var statCardIndex = {};

@foreach(config('translatable.locales') as $locale)
    buttonIndex['{{ $locale }}']   = 1;
    statCardIndex['{{ $locale }}'] = 1;
@endforeach

$(document).ready(function () {

    // Sortable
    $("#sortable").sortable({
        placeholder: "ui-state-highlight",
        cursor: "grab",
        forcePlaceholderSize: true,
        opacity: 0.8,
        update: function (event, ui) {
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
        var status    = $(this).prop('checked') ? 1 : 0;
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

    // Save
    $("#addBtn").click(function (e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('slider.store') }}",
            type: "POST",
            data: new FormData($('#createThisForm')[0]),
            contentType: false,
            processData: false,
            success: function (d) {
                showSuccess(d.message);
                $("#FormCloseBtn").click();
                table.ajax.reload();
            },
            error: function (xhr) {
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
    $(document).on('click', '.add-button-btn', function () {
        let locale    = $(this).data('locale');
        let container = $("#buttons-container-" + locale);
        let idx       = buttonIndex[locale]++;
        container.append(buildButtonRow(locale, idx, '', ''));
    });

    // Add stat card row
    $(document).on('click', '.add-statcard-btn', function () {
        let locale    = $(this).data('locale');
        let container = $("#statcard-container-" + locale);
        let idx       = statCardIndex[locale]++;
        container.append(buildStatCardRow(locale, idx, '', ''));
    });

    // Remove row
    $(document).on('click', '.remove-btn', function () {
        $(this).closest('.button-row').remove();
    });

    // ---- Helpers ----

    function populateForm(data) {
        $("#codeid").val(data.id);
        $("#link").val(data.link);
        $("#cardTitle").text('Update Slider');
        $("#addBtn").html('Update');

        if (data.image) {
            $('#preview-image').attr('src', '/images/slider/' + data.image).show();
        }

        if (data.translations && data.translations.length > 0) {
            data.translations.forEach(function (t) {
                let locale = t.locale;
                $('#' + locale + '_title').val(t.title);
                $('#' + locale + '_sub_title').val(t.sub_title);
                $('#' + locale + '_hero_badge').val(t.hero_badge);

                // Buttons
                let btnContainer = $("#buttons-container-" + locale);
                btnContainer.empty();
                let buttons = t.buttons || [];
                if (buttons.length > 0) {
                    buttons.forEach((btn, i) => btnContainer.append(buildButtonRow(locale, i, btn.label, btn.link)));
                    buttonIndex[locale] = buttons.length;
                } else {
                    btnContainer.append(buildButtonRow(locale, 0, '', ''));
                    buttonIndex[locale] = 1;
                }

                // Stat Cards
                let scContainer = $("#statcard-container-" + locale);
                scContainer.empty();
                let statCards = t.stat_card || [];
                if (statCards.length > 0) {
                    statCards.forEach((sc, i) => scContainer.append(buildStatCardRow(locale, i, sc.value, sc.title)));
                    statCardIndex[locale] = statCards.length;
                } else {
                    scContainer.append(buildStatCardRow(locale, 0, '', ''));
                    statCardIndex[locale] = 1;
                }
            });
        }

        $("#addThisFormContainer").slideDown(300);
        $("#newBtn").hide();
    }

    function clearForm() {
        $('#createThisForm')[0].reset();
        $("#codeid").val('');
        $("#cardTitle").text('Add New Slider');
        $("#addBtn").html('Create');
        $('#preview-image').attr('src', '#').hide();

        @foreach(config('translatable.locales') as $locale)
            $("#buttons-container-{{ $locale }}").html(buildButtonRow('{{ $locale }}', 0, '', ''));
            buttonIndex['{{ $locale }}'] = 1;
            $("#statcard-container-{{ $locale }}").html(buildStatCardRow('{{ $locale }}', 0, '', ''));
            statCardIndex['{{ $locale }}'] = 1;
        @endforeach
    }

    function buildButtonRow(locale, index, label, link) {
        return `<div class="row g-2 mb-2 button-row">
            <div class="col-md-5">
                <input type="text" name="${locale}[buttons][${index}][label]" value="${label ?? ''}" class="form-control" placeholder="Button Label">
            </div>
            <div class="col-md-5">
                <input type="text" name="${locale}[buttons][${index}][link]" value="${link ?? ''}" class="form-control" placeholder="Button Link">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger w-100 remove-btn">X</button>
            </div>
        </div>`;
    }

    function buildStatCardRow(locale, index, value, title) {
        return `<div class="row g-2 mb-2 button-row">
            <div class="col-md-5">
                <input type="text" name="${locale}[stat_card][${index}][value]" value="${value ?? ''}" class="form-control" placeholder="Value (e.g. 500+)">
            </div>
            <div class="col-md-5">
                <input type="text" name="${locale}[stat_card][${index}][title]" value="${title ?? ''}" class="form-control" placeholder="Title">
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