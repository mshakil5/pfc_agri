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
            </div>
            <div class="card-body">
                <form id="createThisForm">
                    @csrf
                    <input type="hidden" id="codeid" name="codeid">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-control select2" id="category_id" name="category_id">
                                <option value="">Select Category</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->translateOrNew(app()->getLocale())->name ?? $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tag</label>
                            <select class="form-control select2" id="tag_id" name="tag_id">
                                <option value="">Select Tag</option>
                                @foreach ($tags as $tag)
                                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 d-none">
                            <label class="form-label">Price</label>
                            <input type="number" class="form-control" id="price" name="price" step="0.01" min="0">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Product Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*"
                                onchange="previewImage(event, '#preview-image')">
                        </div>

                        <div class="col-md-6">
                            <img id="preview-image" src="/placeholder.webp" alt="" class="img-thumbnail rounded"
                                style="max-width:200px; max-height:200px;">
                            <button type="button" class="btn btn-sm btn-danger mt-2" id="removeImageBtn" style="display:none;">
                                Remove Image
                            </button>
                        </div>

                        {{-- Language Tabs --}}
                        <div class="col-md-12">
                            <ul class="nav nav-tabs nav-tabs-custom nav-success mb-3" role="tablist">
                                @foreach(config('translatable.locales') as $index => $locale)
                                    <li class="nav-item">
                                        <a class="nav-link {{ $index == 0 ? 'active' : '' }}"
                                           data-bs-toggle="tab" href="#product-tab-{{ $locale }}" role="tab">
                                            {{ strtoupper($locale) }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="tab-content">
                                @foreach(config('translatable.locales') as $index => $locale)
                                    <div class="tab-pane {{ $index == 0 ? 'active' : '' }}"
                                         id="product-tab-{{ $locale }}" role="tabpanel">
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <label class="form-label">Title ({{ strtoupper($locale) }}) @if($locale === 'en') <span class="text-danger">*</span> @endif</label>
                                                <input type="text" class="form-control"
                                                       name="{{ $locale }}[title]" id="{{ $locale }}_title">
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label">Short Description ({{ strtoupper($locale) }})</label>
                                                <textarea class="form-control" name="{{ $locale }}[short_description]"
                                                          id="{{ $locale }}_short_description" rows="2"></textarea>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label">Long Description ({{ strtoupper($locale) }})</label>
                                                <textarea class="form-control summernote-{{ $locale }}"
                                                          name="{{ $locale }}[long_description]"
                                                          id="{{ $locale }}_long_description"></textarea>
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
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Products List</h4>
                <div style="width:200px;">
                    <select id="filterCategory" class="form-control select2">
                        <option value="">All Categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->translateOrNew(app()->getLocale())->name ?? $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-body">
                <table id="productTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Category</th>
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
    let currentProductId = null;

    $(document).ready(function () {

        // Init summernote for each locale
        @foreach(config('translatable.locales') as $locale)
            $('.summernote-{{ $locale }}').summernote({ height: 200, toolbar: [['style',['bold','italic','underline']],['para',['ul','ol']],['insert',['link']]] });
        @endforeach

        $('.select2').select2({ width: '100%' });

        $('#productTable').DataTable({
            processing: true, serverSide: true, pageLength: 25,
            ajax: {
                url: "{{ route('allproducts') }}",
                data: function (d) { d.category_id = $('#filterCategory').val(); }
            },
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'image', orderable: false, searchable: false },
                { data: 'title', name: 'title' },
                { data: 'category_name', orderable: false, searchable: false },
                { data: 'status', orderable: false, searchable: false },
                { data: 'action', orderable: false, searchable: false }
            ]
        });

        $('#filterCategory').change(function () { reloadTable('#productTable'); });

        $(document).on('change', '.toggle-status', function () {
            $.post('/admin/products-status', {
                _token: '{{ csrf_token() }}',
                product_id: $(this).data('id'),
                status: $(this).prop('checked') ? 1 : 0
            }, function (d) {
                reloadTable('#productTable');
                showSuccess(d.message);
            }).fail(() => showError('Failed to update status'));
        });

        $("#newBtn").click(function () {
            clearForm();
            $(this).hide();
            $("#addThisFormContainer").slideDown(300);
        });

        $("#FormCloseBtn").click(function () {
            $("#addThisFormContainer").slideUp(300);
            setTimeout(() => $("#newBtn").show(), 300);
        });

        $("#addBtn").click(function (e) {
            e.preventDefault();
            var isUpdate = $("#codeid").val() !== '';
            var url = isUpdate ? "{{ URL::to('/admin/products-update') }}" : "{{ URL::to('/admin/products') }}";

            $.ajax({
                url: url,
                type: "POST",
                data: new FormData($('#createThisForm')[0]),
                contentType: false,
                processData: false,
                success: function (d) {
                    showSuccess(d.message);
                    $("#FormCloseBtn").click();
                    reloadTable('#productTable');
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
            currentProductId = id;
            $.get("/admin/products/" + id + "/edit", function (data) {
                populateForm(data);
            });
        });

        $("#removeImageBtn").click(function () {
            if (!currentProductId) return;
            $.post('/admin/products/' + currentProductId + '/remove-image', { _token: '{{ csrf_token() }}' }, function (d) {
                $("#preview-image").attr('src', '/placeholder.webp');
                $("#removeImageBtn").hide();
                $("#image").val('');
                showSuccess(d.message);
            }).fail(() => showError('Failed to remove image'));
        });

        function populateForm(data) {
            $("#codeid").val(data.id);
            $("#cardTitle").text('Update Product');
            $("#addBtn").html('Update');
            $("#category_id").val(data.category_id).trigger('change');
            $("#tag_id").val(data.tag_id).trigger('change');
            $("#price").val(data.price);
            $("#preview-image").attr('src', data.image || '/placeholder.webp');
            $("#removeImageBtn").toggle(!!(data.image && data.image !== '/placeholder.webp'));
            $("#addThisFormContainer").slideDown(300);
            $("#newBtn").hide();

            if (data.translations && data.translations.length > 0) {
                data.translations.forEach(function (t) {
                    $('#' + t.locale + '_title').val(t.title);
                    $('#' + t.locale + '_short_description').val(t.short_description);
                    $('.summernote-' + t.locale).summernote('code', t.long_description ?? '');
                });
            }
        }

        function clearForm() {
            $('#createThisForm')[0].reset();
            $("#codeid").val('');
            $("#cardTitle").text('Add New Product');
            $("#addBtn").html('Create');
            $("#category_id").val(null).trigger('change');
            $("#tag_id").val(null).trigger('change');
            $("#preview-image").attr('src', '/placeholder.webp');
            $("#removeImageBtn").hide();
            currentProductId = null;

            @foreach(config('translatable.locales') as $locale)
                $('.summernote-{{ $locale }}').summernote('code', '');
            @endforeach
        }
    });
</script>
@endsection