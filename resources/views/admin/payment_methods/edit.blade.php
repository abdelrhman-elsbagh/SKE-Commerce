@extends('layouts.vertical', ['page_title' => 'Edit Payment Method'])

@section('css')
    @vite([
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.css',
    ])
    <!-- Quill.js CSS via CDN -->
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <style>
        .uk-card-body {
            display: flex;
            flex-direction: column;
        }
        .description {
            flex: 1;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Edit Payment Method</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form id="edit-payment-method-form" action="{{ route('payment-methods.update', $paymentMethod->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="gateway" class="form-label">Gateway</label>
                                <input type="text" class="form-control" id="gateway" name="gateway" value="{{ $paymentMethod->gateway }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="5">{{ $paymentMethod->description }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="ar_gateway" class="form-label">Arabic Gateway</label>
                                <input type="text" class="form-control" id="ar_gateway" name="ar_gateway" value="{{ $paymentMethod->ar_gateway }}">
                            </div>
                            <div class="mb-3">
                                <label for="ar_description" class="form-label">Arabic Description</label>
                                <textarea class="form-control" id="ar_description" name="ar_description" rows="5">{{ $paymentMethod->ar_description }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                <div class="mt-2" id="image-preview">
                                    @if($paymentMethod->getFirstMediaUrl('payment_method_images'))
                                        <img src="{{ $paymentMethod->getFirstMediaUrl('payment_method_images') }}" alt="Image Preview" style="max-width: 200px;">
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
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.js',
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

            $('#edit-payment-method-form').on('submit', function(e) {
                e.preventDefault();

                // Set the description content from the Quill editor to the hidden input field
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
                            text: 'Payment Method updated successfully.',
                            icon: 'success',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000,
                            afterHidden: function () {
                                window.location.href = "{{ route('payment-methods.index') }}";
                            }
                        });
                    },
                    error: function(response) {
                        $.toast({
                            heading: 'Error',
                            text: 'There was an error updating the payment method.',
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
