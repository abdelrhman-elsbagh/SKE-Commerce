@extends('layouts.vertical', ['page_title' => isset($feeGroup) ? 'Edit Fee Group' : 'Create Fee Group'])

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
                    <h4 class="page-title">{{ isset($feeGroup) ? 'Edit Fee Group' : 'Create Fee Group' }}</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form id="create-edit-fee-group-form" action="{{ isset($feeGroup) ? route('fee_groups.update', $feeGroup->id) : route('fee_groups.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @if(isset($feeGroup))
                                @method('PUT')
                            @endif
                            <div class="mb-3">
                                <label for="fee" class="form-label">Fee % </label>
                                <input type="number" step="0.01" class="form-control" id="fee" name="fee" value="{{ $feeGroup->fee ?? '' }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="name"
                                    name="name"
                                    value="{{ $feeGroup->name ?? '' }}"
                                    @if(isset($feeGroup) && $feeGroup->name == "Default")
                                        readonly disabled title="The name of the 'Default' Fee Group cannot be changed."
                                    @endif
                                >

                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Cover Image</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                <div class="mt-2" id="image-preview">
                                    @if(isset($feeGroup) && $feeGroup->getFirstMediaUrl('images'))
                                        <img src="{{ $feeGroup->getFirstMediaUrl('images') }}" alt="Image Preview" style="max-width: 200px;">
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="logo" class="form-label">Logo</label>
                                <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                                <div class="mt-2" id="logo-preview">
                                    @if(isset($feeGroup) && $feeGroup->getFirstMediaUrl('logos'))
                                        <img src="{{ $feeGroup->getFirstMediaUrl('logos') }}" alt="Logo Preview" style="max-width: 200px;">
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

            $('#logo').change(function() {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#logo-preview').html('<img src="' + e.target.result + '" alt="Logo Preview" style="max-width: 200px;">');
                }
                reader.readAsDataURL(this.files[0]);
            });

            $('#create-edit-fee-group-form').on('submit', function(e) {
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
                            text: 'Fee Group created/updated successfully.',
                            icon: 'success',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000000,
                            afterHidden: function () {
                                window.location.href = "{{ route('fee_groups.index') }}";
                            }
                        });

                        // Optionally, reset the form fields
                        $('#create-edit-fee-group-form')[0].reset();
                        $('#image-preview').html('');
                        $('#logo-preview').html('');
                    },
                    error: function(response) {
                        $.toast().reset('all'); // Reset previous toasts
                        $.toast({
                            heading: 'Error',
                            text: 'There was an error creating/updating the fee group.',
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
