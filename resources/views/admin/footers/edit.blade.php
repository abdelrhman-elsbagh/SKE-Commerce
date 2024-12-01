@extends('layouts.vertical', ['page_title' => 'Edit Footer Link'])

@section('css')
    @vite(['node_modules/jquery-toast-plugin/dist/jquery.toast.min.css'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="page-title-box">
            <h4 class="page-title">Edit Footer Link</h4>
        </div>

        <div class="card">
            <div class="card-body">
                <form id="edit-footer-form" action="{{ route('footer.update', $footer->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="tag" class="form-label">Tag</label>
                        <input type="text" class="form-control" id="tag" name="tag" value="{{ old('tag', $footer->tag) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $footer->title) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="ar_tag" class="form-label">Arabic Tag</label>
                        <input type="text" class="form-control" id="ar_tag" name="ar_tag" value="{{ old('tag', $footer->ar_tag) }}">
                    </div>
                    <div class="mb-3">
                        <label for="ar_title" class="form-label">Arabic Title</label>
                        <input type="text" class="form-control" id="title" name="ar_title" value="{{ old('title', $footer->ar_title) }}">
                    </div>
                    <div class="mb-3">
                        <label for="link" class="form-label">Link</label>
                        <input type="url" class="form-control" id="link" name="link" value="{{ old('link', $footer->link) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    </div>
                    <div class="mb-3">
                        @if($footer->getFirstMediaUrl('images'))
                            <img src="{{ $footer->getFirstMediaUrl('images') }}" alt="Current image" style="width: 100px; height: 100px;">
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @vite(['node_modules/jquery-toast-plugin/dist/jquery.toast.min.js'])

    <script>
        $('#edit-footer-form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function() {
                    $.toast({ heading: 'Success', text: 'Footer link updated.', icon: 'success', loaderBg: '#f96868', position: 'top-right', hideAfter: 3000 });
                    window.location.href = "{{ route('footer.index') }}";
                },
                error: function() {
                    $.toast({ heading: 'Error', text: 'Error updating footer link.', icon: 'error', loaderBg: '#f96868', position: 'top-right', hideAfter: 3000 });
                }
            });
        });
    </script>
@endsection
