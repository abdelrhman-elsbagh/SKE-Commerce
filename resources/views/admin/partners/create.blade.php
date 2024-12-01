@extends('layouts.vertical', ['page_title' => 'Create Agents'])

@section('css')
    @vite([
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.css',
    ])
    <!-- Quill.js CSS via CDN -->
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Create Agents</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form id="create-partner-form" action="{{ route('partners.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <div id="snow-editor" style="height: 300px;"></div>
                                <input type="hidden" name="description" id="description">
                            </div>
                            <div class="mb-3">
                                <label for="ar_name" class="form-label">Arabic Name</label>
                                <input type="text" class="form-control" id="ar_name" name="ar_name">
                            </div>
                            <div class="mb-3">
                                <label for="ar_description" class="form-label">Arabic Description</label>
                                <div id="snow-editor2" style="height: 300px;"></div>
                                <input type="hidden" name="ar_description" id="ar_description">
                            </div>
                            <div class="mb-3">
                                <label for="facebook" class="form-label">Facebook</label>
                                <input type="url" class="form-control" id="facebook" name="facebook">
                            </div>
                            <div class="mb-3">
                                <label for="whatsapp" class="form-label">Whatsapp</label>
                                <input type="text" class="form-control" id="whatsapp" name="whatsapp">
                            </div>
                            <div class="mb-3">
                                <label for="insta" class="form-label">Instagram</label>
                                <input type="url" class="form-control" id="insta" name="insta">
                            </div>
                            <div class="mb-3">
                                <label for="telegram" class="form-label">Telegram</label>
                                <input type="text" class="form-control" id="telegram" name="telegram">
                            </div>
                            <div class="mb-3">
                                <label for="partner_image" class="form-label">Image</label>
                                <input type="file" class="form-control" id="partner_image" name="partner_image" accept="image/*">
                                <div class="mt-2" id="image-preview"></div>
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
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.js',
    ])
    <!-- Quill.js JS via CDN -->
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Quill editor with a more feature-rich toolbar
            var quill = new Quill('#snow-editor', {
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
                        ['link', 'image', 'video'],                       // link, image and video
                        ['clean']                                         // remove formatting button
                    ]
                }
            });

            var quill2 = new Quill('#snow-editor2', {
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
                        ['link', 'image', 'video'],                       // link, image and video
                        ['clean']                                         // remove formatting button
                    ]
                }
            });

            $('#partner_image').change(function() {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#image-preview').html('<img src="' + e.target.result + '" alt="Image Preview" style="max-width: 200px;">');
                }
                reader.readAsDataURL(this.files[0]);
            });

            $('#create-partner-form').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                // Capture Quill content into the hidden input
                var quillContent = quill.root.innerHTML;
                $('#description').val(quillContent); // Set the value of the hidden input


                var ar_quillContent = quill2.root.innerHTML;
                $('#ar_description').val(ar_quillContent); // Set the value of the hidden input

                var formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $.toast({
                            heading: 'Success',
                            text: 'Agent created successfully.',
                            icon: 'success',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000,
                            afterHidden: function () {
                                window.location.href = "{{ route('partners.index') }}";
                            }
                        });

                        $('#create-partner-form')[0].reset();
                        $('#image-preview').html('');
                        quill.setContents([]); // Clear Quill editor content after submission
                    },
                    error: function(response) {
                        $.toast({
                            heading: 'Error',
                            text: 'There was an error creating the partner.',
                            icon: 'error',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000
                        });
                    }
                });
            });
        });
    </script>
@endsection
