@extends('admin.pages.master')
@section('title', 'Dealer Management')
@section('content')

    <div class="container-fluid" id="newBtnSection">
        <div class="row mb-3">
            <div class="col-auto">
                <button type="button" class="btn btn-primary" id="newBtn">
                    <i class="ri-add-line align-bottom me-1"></i> Add New Dealer
                </button>
            </div>
        </div>
    </div>

    <div class="container-fluid" id="addThisFormContainer" style="display: none;">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1" id="cardTitle">Add New Dealer</h4>
                    </div>
                    <div class="card-body">
                        <form id="createThisForm">
                            @csrf
                            <input type="hidden" id="codeid" name="codeid">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Dealer Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Region</label>
                                    <input type="text" class="form-control" id="region" name="region">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Website URL</label>
                                    <input type="url" class="form-control" id="website_url" name="website_url">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Latitude</label>
                                    <input type="number" 
                                        step="any" 
                                        class="form-control" 
                                        id="lat" 
                                        name="lat" 
                                        placeholder="e.g. 54.6078">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Longitude</label>
                                    <input type="number" 
                                        step="any" 
                                        class="form-control" 
                                        id="lng" 
                                        name="lng" 
                                        placeholder="e.g. -5.9264">
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label"><b>Product Categories (Specialties)</b></label>
                                    <div class="row mt-2">
                                        @foreach($productCats as $cat)
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input service-checkbox" type="checkbox" name="services[]" value="{{ $cat->id }}" id="cat{{ $cat->id }}">
                                                <label class="form-check-label" for="cat{{ $cat->id }}">
                                                    {{ $cat->name }}
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-end">
                        <button type="button" id="FormCloseBtn" class="btn btn-light me-2">Cancel</button>
                        <button type="submit" id="addBtn" class="btn btn-primary">Create Dealer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid" id="contentContainer">
        <div class="card">
            <div class="card-body">
                <table id="dealerTable" class="table table-bordered dt-responsive nowrap align-middle" style="width:100%">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Dealer Name</th>
                            <th>Region</th>
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
    $(document).ready(function() {

            $(document).on('change', '.toggle-status', function() {
                var id = $(this).data('id');
                var status = $(this).prop('checked') ? 1 : 0;
                $.post('/admin/dealer-status', {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    status: status
                }, function(d) {
                    reloadTable('#dealerTable');
                    showSuccess(d.message);
                }).fail(() => showError('Failed to update status'));
            });

        var table = $('#dealerTable').DataTable({
            processing: true,
            serverSide: true, // Recommended for better performance
            ajax: {
                url: "{{ route('alldealer') }}",
                type: 'GET',
                error: function (xhr, error, thrown) {
                    console.log("Datatable Error: ", xhr.responseText); // This helps you see the actual error in Console
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'name', name: 'name' },
                { data: 'region', name: 'region', defaultContent: '' }, // Added defaultContent to prevent null errors
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        $("#newBtn").click(function() {
            clearform();
            $("#addThisFormContainer").slideDown(300);
            $(this).hide();
        });

        $("#FormCloseBtn").click(function() {
            $("#addThisFormContainer").slideUp(300);
            setTimeout(() => { $("#newBtn").show(); }, 300);
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
                }
            });
        });

        $("#contentContainer").on('click', '#EditBtn', function() {
            let codeid = $(this).attr('rid');
            $.get("{{ URL::to('/admin/dealer') }}/" + codeid + '/edit', function(data) {
                populateForm(data);
            });
        });

        function populateForm(data) {
            clearform();
            $("#codeid").val(data.id);
            $("#name").val(data.name);
            $("#region").val(data.region);
            $("#phone").val(data.phone);
            $("#website_url").val(data.website_url);
            $("#lat").val(data.lat);
            $("#lng").val(data.lng);

            // Handle Checkboxes
            if (data.services) {
                data.services.forEach(id => {
                    $(`#cat${id}`).prop('checked', true);
                });
            }

            $("#addBtn").val('Update').html('Update Dealer');
            $("#cardTitle").text('Update Dealer');
            $("#addThisFormContainer").slideDown(300);
            $("#newBtn").hide();
        }

        function clearform() {
            $('#createThisForm')[0].reset();
            $("#codeid").val('');
            $(".service-checkbox").prop('checked', false);
            $("#addBtn").val('Create').html('Create Dealer');
            $("#cardTitle").text('Add New Dealer');
        }
    });
</script>
@endsection