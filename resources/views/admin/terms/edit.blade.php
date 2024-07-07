@extends('layouts.vertical', ['page_title' => 'Edit Terms and Conditions'])

@section('css')
    @vite([
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.css',
        'node_modules/quill/dist/quill.core.css',
        'node_modules/quill/dist/quill.snow.css'
    ])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Edit Terms and Conditions</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form id="edit-terms-conditions-form" action="{{ route('terms.update', $termsConditions->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="data" class="form-label">Terms and Conditions</label>
                                <div id="snow-editor" style="height: 300px;">{!! $termsConditions->data !!}</div>
                                <input type="hidden" name="data" id="data">
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

    <script>
        $(document).ready(function() {
            // Initialize Quill editor
            var quill = new Quill('#snow-editor', {
                theme: 'snow'
            });

            // Update hidden input field whenever content changes
            quill.on('text-change', function() {
                $('#data').val(quill.root.innerHTML);
            });

            $('#edit-terms-conditions-form').on('submit', function(e) {
                e.preventDefault();

                // Set the description content from the Quill editor to the hidden input field
                $('#data').val(quill.root.innerHTML);

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
                            text: 'Terms and Conditions updated successfully.',
                            icon: 'success',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000,
                            afterHidden: function () {
                                window.location.href = "{{ route('terms.edit') }}";
                            }
                        });
                    },
                    error: function(response) {
                        $.toast({
                            heading: 'Error',
                            text: 'There was an error updating the Terms and Conditions.',
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
