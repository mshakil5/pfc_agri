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
                                @foreach ($parentCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->translateOrNew(app()->getLocale())->name ?? $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Category Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*"
                                onchange="previewImage(event, '#preview-image')">
                            <img id="preview-image" src="#" alt="" class="img-thumbnail rounded mt-3"
                                style="max-width:300px; display:none;">
                        </div>

                        {{-- Language Tabs --}}
                        <div class="col-md-12">
                            <ul class="nav nav-tabs nav-tabs-custom nav-success mb-3" role="tablist">
                                @foreach(config('translatable.locales') as $index => $locale)
                                    <li class="nav-item">
                                        <a class="nav-link {{ $index == 0 ? 'active' : '' }}"
                                           data-bs-toggle="tab" href="#category-tab-{{ $locale }}" role="tab">
                                            {{ strtoupper($locale) }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="tab-content">
                                @foreach(config('translatable.locales') as $index => $locale)
                                    <div class="tab-pane {{ $index == 0 ? 'active' : '' }}"
                                         id="category-tab-{{ $locale }}" role="tabpanel">
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <label class="form-label">Name ({{ strtoupper($locale) }}) @if($locale === 'en') <span class="text-danger">*</span> @endif</label>
                                                <input type="text" class="form-control"
                                                       name="{{ $locale }}[name]" id="{{ $locale }}_name">
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label">Description ({{ strtoupper($locale) }})</label>
                                                <textarea class="form-control" name="{{ $locale }}[description]"
                                                          id="{{ $locale }}_description" rows="3"></textarea>
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
                            <th>Parent Category</th>
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
<script>
    function loadParentCategories() {
        $.get("{{ route('parent.categories') }}", function (response) {
            $('#parent_id').empty().append('<option value="">Select Parent Category</option>');
            response.forEach(function (cat) {
                var name = (cat.translations && cat.translations.length > 0) ? cat.translations[0].name : cat.name;
                $('#parent_id').append('<option value="' + cat.id + '">' + name + '</option>');
            });
            $('#parent_id').trigger('change');
        });
    }

    $(document).ready(function () {

        $('.select2').select2({ placeholder: "Select Parent Category", allowClear: true, width: '100%' });

        $('#categoryTable').DataTable({
            processing: true, serverSide: true, pageLength: 25,
            ajax: "{{ route('allcategory') }}",
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'parent_category', orderable: false, searchable: false },
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

        $("#newBtn").click(function () {
            clearForm();
            $(this).hide();
            $("#addThisFormContainer").slideDown(300);
            loadParentCategories();
        });

        $("#FormCloseBtn").click(function () {
            $("#addThisFormContainer").slideUp(300);
            setTimeout(() => $("#newBtn").show(), 300);
        });

        $("#addBtn").click(function (e) {
            e.preventDefault();
            var isUpdate = $("#codeid").val() !== '';
            var url = isUpdate ? "{{ URL::to('/admin/category-update') }}" : "{{ URL::to('/admin/category') }}";

            $.ajax({
                url: url,
                type: "POST",
                data: new FormData($('#createThisForm')[0]),
                contentType: false,
                processData: false,
                success: function (d) {
                    showSuccess(d.message);
                    $("#FormCloseBtn").click();
                    reloadTable('#categoryTable');
                    loadParentCategories();
                },
                error: function (xhr) {
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

        $("#contentContainer").on('click', '#EditBtn', function () {
            let id = $(this).attr('rid');
            $.get("/admin/category/" + id + "/edit", function (data) {
                populateForm(data);
            });
        });

        function populateForm(data) {
            $("#codeid").val(data.id);
            $("#cardTitle").text('Update Category');
            $("#addBtn").html('Update');
            $("#addThisFormContainer").slideDown(300);
            $("#newBtn").hide();

            loadParentCategories();
            setTimeout(function () {
                $('#parent_id').val(data.parent_id || null).trigger('change');
            }, 300);

            if (data.image) {
                $('#preview-image').attr('src', data.image).show();
            }

            if (data.translations && data.translations.length > 0) {
                data.translations.forEach(function (t) {
                    $('#' + t.locale + '_name').val(t.name);
                    $('#' + t.locale + '_description').val(t.description);
                });
            }
        }

        function clearForm() {
            $('#createThisForm')[0].reset();
            $("#codeid").val('');
            $("#cardTitle").text('Add New Category');
            $("#addBtn").html('Create');
            $('#preview-image').attr('src', '#').hide();
            $('#parent_id').val(null).trigger('change');
        }
    });
</script>
@endsection