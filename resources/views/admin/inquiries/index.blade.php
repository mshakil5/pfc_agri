@extends('admin.pages.master')
@section('title', 'Product Inquiries')
@section('content')

<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-auto">
            <h4 class="card-title mb-0">Product Inquiries</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="inquiryTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Product</th>
                        <th>Customer Info</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Inquiry Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailContent">
                <!-- Filled by AJAX -->
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<style>
    .unread-row { background-color: #eff6ff; font-weight: 500; }
</style>

<script>
 $(document).ready(function () {
    var table = $('#inquiryTable').DataTable({
        processing: true, serverSide: true, pageLength: 25,
        ajax: "{{ route('admin.inquiries.index') }}",
        order: [[5, 'desc']],
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'product', name: 'product' },
            { data: 'customer', name: 'name' },
            { data: 'message', orderable: false, searchable: true },
            { data: 'date', name: 'created_at' },
            { data: 'action', orderable: false, searchable: false }
        ],
        // Optional: Add a callback to highlight unread rows
        createdRow: function (row, data, index) {
            if (data.is_read == 0) {
                $(row).addClass('unread-row');
            }
        }
    });

    // View Details
    $('#contentContainer').on('click', '.view-detail-btn', function () {
        let id = $(this).data('id');
        
        // Remove unread highlight
        $(this).closest('tr').removeClass('unread-row');
        
        $.get("/admin/product-inquiries/" + id, function (data) {
            let productHtml = '<span class="text-danger">Product Deleted</span>';
            
            if (data.product) {
                productHtml = `
                    <div class="d-flex gap-3 mb-3">
                        <img src="${data.product.image ? asset(data.product.image) : asset('placeholder.webp')}" class="rounded border p-1" style="width: 80px; height: 80px; object-fit: contain;">
                        <div>
                            <h6 class="mb-0">${data.product.title}</h6>
                            <span class="badge bg-light text-dark">${data.product.category}</span>
                        </div>
                    </div>
                `;
            }

            let html = `
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">Customer Name</label>
                        <p class="form-control-plaintext py-1">${data.name}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">Submitted</label>
                        <p class="form-control-plaintext py-1">${data.date}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">Email Address</label>
                        <p class="form-control-plaintext py-1">${data.email}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">Phone Number</label>
                        <p class="form-control-plaintext py-1">${data.phone || 'N/A'}</p>
                    </div>
                    <div class="col-12 mt-3">
                        <label class="form-label fw-bold text-muted">Product</label>
                        ${productHtml}
                    </div>
                    <div class="col-12 mt-3">
                        <label class="form-label fw-bold text-muted">Message</label>
                        <div class="form-control bg-light" style="min-height: 120px; white-space: pre-wrap;">${data.message}</div>
                    </div>
                </div>
            `;
            
            $('#detailContent').html(html);
            new bootstrap.Modal('#detailModal').show();
        });
    });
});
</script>
@endsection