@extends('admin.pages.master')
@section('title', 'Copyright')
@section('content')
<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">

<!-- jQuery (required) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-12">

                @if (session()->has('success'))
                    <div class="alert alert-success pt-3 mb-3" id="successMessage">{{ session()->get('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title mb-0 flex-grow-1">Copyright</h3>
                    </div>

                    <form action="{{ route('admin.copyright') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Copyright <span class="text-danger">*</span></label>
                                        <textarea id="summernote" name="copyright"
                                            class="form-control @error('copyright') is-invalid @enderror"
                                            rows="4">{!! $companyDetails->copyright !!}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-secondary">Update</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            height: 200,
            placeholder: 'Write copyright text...',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });
    });
</script>


@endsection