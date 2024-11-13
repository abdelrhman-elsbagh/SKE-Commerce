@extends('layouts.vertical', ['page_title' => 'Create Footer Link'])

@section('css')
    @vite(['node_modules/jquery-toast-plugin/dist/jquery.toast.min.css'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="page-title-box">
            <h4 class="page-title">Create Footer Link</h4>
        </div>

        <div class="card">
            <div class="card-body">
                <form id="create-footer-form" action="{{ route('footer.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="tag" class="form-label">Tag</label>
                        <input type="text" class="form-control" id="tag" name="tag" required>
                    </div>
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="link" class="form-label">Link</label>
                        <input type="url" class="form-control" id="link" name="link" required>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @vite(['node_modules/jquery-toast-plugin/dist/jquery.toast.min.js'])

    <script>
        $('#create-footer-form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function() {
                    $.toast({ heading: 'Success', text: 'Footer link created.', icon: 'success', loaderBg: '#f96868', position: 'top-right', hideAfter: 3000 });
                    window.location.href = "{{ route('footer.index') }}";
                },
                error: function() {
                    $.toast({ heading: 'Error', text: 'Error creating footer link.', icon: 'error', loaderBg: '#f96868', position: 'top-right', hideAfter: 3000 });
                }
            });
        });
    </script>
@endsection
