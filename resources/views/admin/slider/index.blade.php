@extends('admin.pages.master')
@section('title', 'Slider')
@section('content')

    <div class="container-fluid" id="newBtnSection">
        <div class="row mb-3">
            <div class="col-auto">
                <button type="button" class="btn btn-primary" id="newBtn">
                    Add New Slider
                </button>
            </div>
        </div>
    </div>

    <div class="container-fluid" id="addThisFormContainer">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1" id="cardTitle">Add New Slider</h4>
                    </div>
                    <div class="card-body">
                        <form id="createThisForm">
                            @csrf
                            <input type="hidden" id="codeid" name="codeid">

                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">Slider Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" name="title">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Sub Title </label>
                                    <input type="text" class="form-control" id="sub_title" name="sub_title">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Hero Badge </label>
                                    <input type="text" class="form-control" id="hero_badge" name="hero_badge">
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label"><b>Slider Buttons</b></label>
                                    <div id="buttons-container">
                                        <div class="row g-2 mb-2 button-row">
                                            <div class="col-md-5">
                                                <input type="text" name="buttons[0][label]" class="form-control" placeholder="Button Name">
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" name="buttons[0][link]" class="form-control" placeholder="Button Link">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-danger w-100 remove-btn">X</button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" id="add-button" class="btn btn-dark btn-sm mt-2">+ Add More Button</button>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Slider Image</label>
                                    <input type="file" class="form-control" id="image" accept="image/*"
                                        onchange="previewImage(event, '#preview-image')" name="image">
                                    <img id="preview-image" src="#" alt="" class="img-thumbnail rounded mt-3"
                                        style="max-width: 300px;">
                                </div>

                                <!-- Aminities -->
                                <div class="col-md-12">
                                    <label class="form-label"><b>Features</b></label>
                                    <div id="features-container">
                                        <div class="row g-2 mb-2 button-row">
                                            <div class="col-md-5">
                                                <input type="text" name="features[0][value]" class="form-control" placeholder="Values">
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" name="features[0][title]" class="form-control" placeholder="Title">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-danger w-100 remove-btn">X</button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" id="add-features-button" class="btn btn-dark btn-sm mt-2">+ Add More Button</button>
                                </div>
                                <!-- Aminities -->


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
        <ul class="nav nav-tabs mb-3" id="sliderTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="list-tab" data-bs-toggle="tab" href="#list" role="tab">Slider List</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="sort-tab" data-bs-toggle="tab" href="#sort" role="tab">Sort Sliders</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="list" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Sliders</h4>
                    </div>
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
                        <small class="text-muted">Drag & drop rows to change order</small>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Title</th>
                                </tr>
                            </thead>
                            <tbody id="sortable">
                                @foreach ($sliders as $slider)
                                    <tr data-id="{{ $slider->id }}">
                                        <td>{{ $slider->serial }}</td>
                                        <td>{{ $slider->title }}</td>
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
        $(document).ready(function() {
            $("#sortable").sortable({
                placeholder: "ui-state-highlight",
                cursor: "grab",
                forcePlaceholderSize: true,
                opacity: 0.8,
                update: function(event, ui) {
                    var order = $(this).sortable('toArray', {
                        attribute: 'data-id'
                    });
                    $.ajax({
                        url: "{{ route('sliders.updateOrder') }}",
                        method: "POST",
                        data: {
                            _token: '{{ csrf_token() }}',
                            order: order
                        },
                        success: function(res) {
                            showSuccess(res.message);
                            $("#sortable tr").each(function(index) {
                                $(this).find("td:first").text(index + 1);
                            });
                            reloadTable('#sliderTable');
                        },
                        error: function(xhr) {
                            showError(xhr.responseJSON?.message ?? "Something went wrong!");
                        }
                    });
                }
            });

            $('#sliderTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: "{{ route('allslider') }}",
                columns: [{
                        data: 'serial',
                        name: 'serial',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false
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

            $(document).on('change', '.toggle-status', function() {
                var slider_id = $(this).data('id');
                var status = $(this).prop('checked') ? 1 : 0;
                $.post('/admin/slider-status', {
                    _token: '{{ csrf_token() }}',
                    slider_id: slider_id,
                    status: status
                }, function(d) {
                    reloadTable('#sliderTable');
                    showSuccess(d.message);
                }).fail(() => showError('Failed to update status'));
            });

            $("#addThisFormContainer").hide();
            $("#newBtn").click(function() {
                clearform();
                $("#addThisFormContainer").slideDown(300);
                $("#newBtn").hide();
            });
            $("#FormCloseBtn").click(function() {
                $("#addThisFormContainer").slideUp(300);
                setTimeout(() => {
                    $("#newBtn").show();
                }, 300);
            });

            var url = "{{ URL::to('/admin/slider') }}";
            var upurl = "{{ URL::to('/admin/slider') }}";

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#addBtn").click(function(e) {
                e.preventDefault();
                let form_data = new FormData($('#createThisForm')[0]);
                
                $.ajax({
                    url: "{{ route('dealer.store') }}", 
                    type: "POST",
                    data: form_data,
                    contentType: false,
                    processData: false,
                    success: function(d) {
                        showSuccess(d.message);
                        $("#FormCloseBtn").click();
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            // Get the errors object from Laravel
                            let errors = xhr.responseJSON.errors;
                            let errorMessages = [];

                            // Loop through the errors object
                            Object.values(errors).forEach(function(messages) {
                                errorMessages.push(messages[0]); // Get the first error message for each field
                            });

                            // Display all messages joined by a line break or comma
                            showError(errorMessages.join("<br>")); 
                        } else {
                            // Handle other errors (500, 404, etc.)
                            showError(xhr.responseJSON?.message ?? "Something went wrong. Please try again.");
                        }
                    }
                });
            });

            $("#contentContainer").on('click', '#EditBtn', function() {
                $("#cardTitle").text('Update Slider Information');
                let codeid = $(this).attr('rid');
                $.get(url + '/' + codeid + '/edit', function(data) {
                    populateForm(data);
                });
            });

            function populateForm(data) {
                // Basic Fields
                $("#codeid").val(data.id);
                $("#title").val(data.title);
                $("#sub_title").val(data.sub_title);
                $("#hero_badge").val(data.hero_badge);
                $("#link").val(data.link);

                // 1. Handle Buttons (JSON Array)
                let buttonContainer = $("#buttons-container");
                buttonContainer.empty(); // Clear existing rows
                if (data.buttons && data.buttons.length > 0) {
                    data.buttons.forEach((btn, index) => {
                        addButtonRow(buttonContainer, 'buttons', index, btn.label, btn.link);
                    });
                } else {
                    addButtonRow(buttonContainer, 'buttons', 0, '', ''); // Add one empty row if none exist
                }

                // 2. Handle Features / Stat Cards (JSON Array)
                let featureContainer = $("#features-container");
                featureContainer.empty(); // Clear existing rows
                // Note: Your JSON shows 'stat_card', adjust key name if necessary
                let features = data.stat_card || data.features || []; 
                if (features.length > 0) {
                    features.forEach((feat, index) => {
                        addFeatureRow(featureContainer, 'features', index, feat.value, feat.title);
                    });
                } else {
                    addFeatureRow(featureContainer, 'features', 0, '', '');
                }

                // Image Preview
                let imgSrc = data.image ? '/images/slider/' + data.image : '#';
                $('#preview-image').attr('src', imgSrc);

                // UI Toggle
                $("#addBtn").val('Update').html('Update');
                $("#addThisFormContainer").slideDown(300);
                $("#newBtn").hide();

            }

            function clearform() {
                $('#createThisForm')[0].reset();
                $("#codeid").val('');
                
                // Reset dynamic sections to just one empty row
                $("#buttons-container").html('');
                addButtonRow($("#buttons-container"), 'buttons', 0, '', '');
                
                $("#features-container").html('');
                addFeatureRow($("#features-container"), 'features', 0, '', '');

                $("#addBtn").val('Create').html('Create');
                $('#preview-image').attr('src', '#');
                $("#cardTitle").text('Add New Slider');
            }
        });

        function previewImage(event, imgSelector) {
            if (event.target.files && event.target.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $(imgSelector).attr('src', e.target.result);
                };
                reader.readAsDataURL(event.target.files[0]);
            }
        }

        let buttonIndex = 1;
        document.getElementById('add-button').addEventListener('click', function() {
            const container = document.getElementById('buttons-container');
            const html = `
                <div class="row g-2 mb-2 button-row">
                    <div class="col-md-5">
                        <input type="text" name="buttons[${buttonIndex}][label]" class="form-control" placeholder="Button Name">
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="buttons[${buttonIndex}][link]" class="form-control" placeholder="Button Link">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger w-100 remove-btn">X</button>
                    </div>
                </div>`;
            container.insertAdjacentHTML('beforeend', html);
            buttonIndex++;
        });


        let featurebuttonIndex = 1;
        document.getElementById('add-features-button').addEventListener('click', function() {
            const container = document.getElementById('features-container');
            const html = `
                <div class="row g-2 mb-2 button-row">
                    <div class="col-md-5">
                        <input type="text" name="buttons[${featurebuttonIndex}][label]" class="form-control" placeholder="Value">
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="buttons[${featurebuttonIndex}][link]" class="form-control" placeholder="Title">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger w-100 remove-btn">X</button>
                    </div>
                </div>`;
            container.insertAdjacentHTML('beforeend', html);
            featurebuttonIndex++;
        });

        // Remove row functionality
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-btn')) {
                e.target.closest('.button-row').remove();
            }
        });

        function addButtonRow(container, prefix, index, label = '', link = '') {
            let html = `
                <div class="row g-2 mb-2 button-row">
                    <div class="col-md-5">
                        <input type="text" name="${prefix}[${index}][label]" value="${label}" class="form-control" placeholder="Button Name">
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="${prefix}[${index}][link]" value="${link}" class="form-control" placeholder="Button Link">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger w-100 remove-btn">X</button>
                    </div>
                </div>`;
            container.append(html);
        }

        function addFeatureRow(container, prefix, index, value = '', title = '') {
            let html = `
                <div class="row g-2 mb-2 button-row">
                    <div class="col-md-5">
                        <input type="text" name="${prefix}[${index}][value]" value="${value}" class="form-control" placeholder="Values">
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="${prefix}[${index}][title]" value="${title}" class="form-control" placeholder="Title">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger w-100 remove-btn">X</button>
                    </div>
                </div>`;
            container.append(html);
        }




    </script>

@endsection
