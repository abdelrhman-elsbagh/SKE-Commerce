@extends('layouts.vertical', ['page_title' => 'Edit Tag'])

@section('css')
    @vite([
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.css'
    ])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Edit Tag</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form id="edit-tag-form" action="{{ route('tags.update', $tag->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required value="{{ $tag->name }}">
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                <div class="mt-2" id="image-preview">
                                    @if($tag->getFirstMediaUrl('images'))
                                        <img src="{{ $tag->getFirstMediaUrl('images') }}" alt="Image Preview" style="max-width: 200px;">
                                    @endif
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @vite([
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.js'
    ])

    <script>
        $(document).ready(function() {
            $('#image').change(function() {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#image-preview').html('<img src="' + e.target.result + '" alt="Image Preview" style="max-width: 200px;">');
                }
                reader.readAsDataURL(this.files[0]);
            });

            $('#edit-tag-form').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $.toast().reset('all'); // Reset previous toasts
                        $.toast({
                            heading: 'Success',
                            text: 'Tag updated successfully.',
                            icon: 'success',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000,
                            afterHidden: function () {
                                window.location.href = "{{ route('tags.index') }}";
                            }
                        });
                    },
                    error: function(response) {
                        if (response.responseJSON && response.responseJSON.errors) {
                            var errors = response.responseJSON.errors;
                            for (var key in errors) {
                                if (errors.hasOwnProperty(key)) {
                                    $.toast().reset('all'); // Reset previous toasts
                                    $.toast({
                                        heading: 'Error',
                                        text: errors[key][0],
                                        icon: 'error',
                                        loader: true,
                                        loaderBg: '#f96868',
                                        position: 'top-right',
                                        hideAfter: 3000
                                    });
                                }
                            }
                        } else {
                            $.toast().reset('all'); // Reset previous toasts
                            $.toast({
                                heading: 'Error',
                                text: 'There was an error updating the tag.',
                                icon: 'error',
                                loader: true,
                                loaderBg: '#f96868',
                                position: 'top-right',
                                hideAfter: 3000
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection
