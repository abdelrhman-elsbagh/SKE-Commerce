@extends('layouts.vertical', ['page_title' => 'Edit Ticket Category'])

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
                    <h4 class="page-title">Edit Ticket Category</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form id="edit-ticket-category-form" action="{{ route('ticket_categories.update', $category->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="name" class="form-label">Category Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $category->name) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="ar_name" class="form-label">Arabic Name</label>
                                <input type="text" class="form-control" id="ar_name" name="ar_name" value="{{ old('ar_name', $category->ar_name) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="desc" class="form-label">Description</label>
                                <textarea class="form-control" id="desc" name="desc" rows="3">{{ old('desc', $category->desc) }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="ar_desc" class="form-label">Arabic Description</label>
                                <textarea class="form-control" id="ar_desc" name="ar_desc" rows="3">{{ old('ar_desc', $category->ar_desc) }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Category</button>
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
            // Handle form submission with AJAX
            $('#edit-ticket-category-form').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                var formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $.toast().reset('all'); // Reset any previous toasts
                        $.toast({
                            heading: 'Success',
                            text: 'Ticket category updated successfully.',
                            icon: 'success',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000,
                            afterHidden: function () {
                                window.location.href = "{{ route('ticket_categories.index') }}"; // Redirect after success
                            }
                        });
                    },
                    error: function(response) {
                        $.toast().reset('all'); // Reset any previous toasts
                        $.toast({
                            heading: 'Error',
                            text: 'There was an error updating the ticket category.',
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
