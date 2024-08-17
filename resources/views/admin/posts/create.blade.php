@extends('layouts.vertical', ['page_title' => 'Create/Edit Post'])

@section('css')
    @vite(['node_modules/jquery-toast-plugin/dist/jquery.toast.min.css'])
    <!-- Quill.js CSS via CDN -->
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">{{ isset($post) ? 'Edit Post' : 'Create Post' }}</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form id="create-edit-post-form" action="{{ isset($post) ? route('posts.update', $post->id) : route('posts.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @if(isset($post))
                                @method('PUT')
                            @endif
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ $post->title ?? '' }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <div id="quill-editor" style="height: 300px;">{!! $post->description ?? '' !!}</div>
                                <input type="hidden" name="description" id="description">
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                <div class="mt-2" id="image-preview">
                                    @if(isset($post) && $post->getFirstMediaUrl('images'))
                                        <img src="{{ $post->getFirstMediaUrl('images') }}" alt="Image Preview" style="max-width: 200px;">
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
    @vite(['node_modules/jquery-toast-plugin/dist/jquery.toast.min.js'])
    <!-- Quill.js JS via CDN -->
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Quill editor
            var quill = new Quill('#quill-editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'font': [] }, { 'size': ['small', false, 'large', 'huge'] }],  // custom font and size
                        ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
                        [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
                        [{ 'script': 'sub' }, { 'script': 'super' }],     // superscript/subscript
                        [{ 'header': 1 }, { 'header': 2 }, 'blockquote', 'code-block'],  // headers and block quote
                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],    // lists
                        [{ 'indent': '-1' }, { 'indent': '+1' }],         // outdent/indent
                        [{ 'direction': 'rtl' }],                         // text direction
                        [{ 'align': [] }],                                // text alignment
                        ['link', 'image', 'video'],                       // link, image, and video
                        ['clean']                                         // remove formatting button
                    ]
                }
            });

            // Set Quill's content to the hidden input on form submit
            $('#create-edit-post-form').on('submit', function(e) {
                e.preventDefault();
                $('#description').val(quill.root.innerHTML);

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
                            text: 'Post created/updated successfully.',
                            icon: 'success',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000,
                            afterHidden: function () {
                                window.location.href = "{{ route('posts.index') }}";
                            }
                        });

                        $('#create-edit-post-form')[0].reset();
                        $('#image-preview').html('');
                        quill.setContents([]); // Clear Quill editor content after submission
                    },
                    error: function(response) {
                        $.toast().reset('all'); // Reset previous toasts
                        $.toast({
                            heading: 'Error',
                            text: 'There was an error creating/updating the post.',
                            icon: 'error',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000
                        });
                    }
                });
            });

            $('#image').change(function() {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#image-preview').html('<img src="' + e.target.result + '" alt="Image Preview" style="max-width: 200px;">');
                }
                reader.readAsDataURL(this.files[0]);
            });
        });
    </script>
@endsection
