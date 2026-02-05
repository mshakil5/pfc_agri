@extends('admin.pages.master')
@section('title', 'Awards')
@section('content')

<div class="container-fluid" id="newBtnSection">
    <button type="button" class="btn btn-primary mb-3" id="newBtn">Add New Award</button>
</div>

<div class="container-fluid" id="addThisFormContainer" style="display: none;">
    <div class="card">
        <div class="card-header"><h4 id="cardTitle">Add New Award</h4></div>
        <div class="card-body">
            <form id="createThisForm">
                @csrf
                <input type="hidden" id="codeid" name="codeid">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="icon">Award Icon</label>
                        <select class="form-control select2" name="icon" id="icon">
                            <option value="">Select Icon</option>
                            <option value="fas fa-trophy">Trophy (Standard Award)</option>
                            <option value="fas fa-medal">Medal (Achievement)</option>
                            <option value="fas fa-award">Award Badge</option>
                            <option value="fas fa-leaf">Leaf (Sustainability/Eco)</option>
                            <option value="fas fa-star">Star (Excellence)</option>
                            <option value="fas fa-crown">Crown (Leadership)</option>
                            <option value="fas fa-certificate">Certificate</option>
                            <option value="fas fa-globe">Globe (International)</option>
                            <option value="fas fa-microscope">Microscope (Innovation/Research)</option>
                            <option value="fas fa-lightbulb">Lightbulb (Idea/Innovation)</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Year</label>
                        <input type="number" class="form-control" name="year" id="year" placeholder="2025">
                    </div>
                </div>

                <ul class="nav nav-tabs nav-tabs-custom nav-success mb-3" role="tablist">
                    @foreach(config('translatable.locales') as $index => $locale)
                        <li class="nav-item">
                            <a class="nav-link {{ $index == 0 ? 'active' : '' }}" data-bs-toggle="tab" href="#tab-{{ $locale }}" role="tab">
                                {{ strtoupper($locale) }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content">
                    @foreach(config('translatable.locales') as $index => $locale)
                        <div class="tab-pane {{ $index == 0 ? 'active' : '' }}" id="tab-{{ $locale }}" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Title ({{ strtoupper($locale) }})</label>
                                    <input type="text" name="{{ $locale }}[title]" id="{{ $locale }}_title" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Organization ({{ strtoupper($locale) }})</label>
                                    <input type="text" name="{{ $locale }}[organization]" id="{{ $locale }}_organization" class="form-control">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label>Tag ({{ strtoupper($locale) }})</label>
                                    <input type="text" name="{{ $locale }}[tag]" id="{{ $locale }}_tag" class="form-control">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label>Description ({{ strtoupper($locale) }})</label>
                                    <textarea name="{{ $locale }}[description]" id="{{ $locale }}_description" class="form-control summernote"></textarea>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </form>
        </div>
        <div class="card-footer text-end">
            <button type="button" id="addBtn" class="btn btn-primary">Save Award</button>
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
<script>
    $(document).ready(function() {
        var table = $('#awardTable').DataTable({
            processing: true, serverSide: true,
            ajax: "{{ route('admin.awards') }}",
            columns: [
                { 
                    data: 'DT_RowIndex', 
                    name: 'DT_RowIndex', 
                    orderable: false,   // <--- Add this
                    searchable: false  // <--- Add this
                },
                { data: 'year', name: 'year' },
                { data: 'title', name: 'title' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        $("#addBtn").click(function() {
            let id = $("#codeid").val();
            let url = id ? "{{ url('/admin/awards-update') }}" : "{{ url('/admin/awards') }}";
            let form_data = new FormData($('#createThisForm')[0]);

            $.ajax({
                url: url, type: "POST", data: form_data,
                contentType: false, processData: false,
                success: function(d) {
                    showSuccess(d.message);
                    $("#addThisFormContainer").slideUp();
                    $("#newBtn").show();
                    table.draw();
                },
                error: function(xhr) {
                    showError(xhr.responseJSON.message);
                }
            });
        });

        $('#contentContainer').on('click', '#EditBtn', function() {
            let id = $(this).attr('rid');
            $.get("/admin/awards/" + id + "/edit", function(data) {
                $("#codeid").val(data.id);
                $("#icon").val(data.icon).trigger('change');
                $("#year").val(data.year);

                // Loop through translations and populate tab fields
                data.translations.forEach(function(t) {
                    $(`#${t.locale}_title`).val(t.title);
                    $(`#${t.locale}_organization`).val(t.organization);
                    $(`#${t.locale}_tag`).val(t.tag);
                    $(`#${t.locale}_description`).summernote('code', t.description);
                });

                $("#addThisFormContainer").slideDown();
                $("#newBtn").hide();
                $("#cardTitle").text('Edit Award');
            });
        });
        
        // Form toggle logic same as your provided code...
        $("#newBtn").click(function() {
            $('#createThisForm')[0].reset();
            $(".summernote").summernote('code', '');
            $("#icon").val('').trigger('change');
            $("#codeid").val('');
            $("#addThisFormContainer").slideDown();
            $(this).hide();
        });
        $("#FormCloseBtn").click(function() {
            $("#addThisFormContainer").slideUp();
            $("#newBtn").show();
        });
    });
</script>
@endsection