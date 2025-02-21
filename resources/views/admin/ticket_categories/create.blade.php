@extends('layouts.vertical', ['page_title' => 'Create Ticket Category'])

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
                    <h4 class="page-title">Create Ticket Category</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form id="create-ticket-category-form" action="{{ route('ticket_categories.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Category Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="ar_name" class="form-label">Arabic Name</label>
                                <input type="text" class="form-control" id="ar_name" name="ar_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="desc" class="form-label">Description</label>
                                <textarea class="form-control" id="desc" name="desc" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="ar_desc" class="form-label">Arabic Description</label>
                                <textarea class="form-control" id="ar_desc" name="ar_desc" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Create Category</button>
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
            $('#create-ticket-category-form').on('submit', function(e) {
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
                            text: 'Ticket category created successfully.',
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
                            text: 'There was an error creating the ticket category.',
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
