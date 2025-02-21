@extends('layouts.vertical', ['page_title' => 'Edit Ticket'])

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
                    <h4 class="page-title">Edit Ticket</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form id="edit-ticket-form" action="{{ route('tickets.update', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Ticket Category -->
                            <div class="mb-3">
                                <label for="ticket_category_id" class="form-label">Category</label>
                                <select class="form-control" name="ticket_category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $ticket->ticket_category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Message -->
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="3" readonly>{{ $ticket->message }}</textarea>
                            </div>

                            <!-- User Email (Read-Only) -->
                            <div class="mb-3">
                                <label for="user_email" class="form-label">User Email</label>
                                <input type="email" class="form-control" id="user_email" name="user_email" value="{{ $ticket->user->email }}" readonly>
                            </div>

                            <!-- Response -->
                            <div class="mb-3">
                                <label for="response" class="form-label">Response</label>
                                <textarea class="form-control" id="response" name="response" rows="3">{{ $ticket->response }}</textarea>
                            </div>

                            <!-- Status -->
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control" name="status" required>
                                    <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                    <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                </select>
                            </div>

                            <!-- Upload Image -->
                            <div class="mb-3">
                                <label for="image" class="form-label">Upload Image</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                <div id="image-preview" class="mt-3">
                                    @if($ticket->getFirstMediaUrl('images'))
                                        <img src="{{ $ticket->getFirstMediaUrl('images') }}" alt="Current Image" style="max-width: 200px;">
                                    @endif
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Update Ticket</button>
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
            $('#edit-ticket-form').on('submit', function(e) {
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
                            text: 'Ticket updated successfully.',
                            icon: 'success',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000,
                            afterHidden: function () {
                                window.location.href = "{{ route('tickets.index') }}"; // Redirect after success
                            }
                        });
                    },
                    error: function(response) {
                        $.toast().reset('all'); // Reset any previous toasts
                        $.toast({
                            heading: 'Error',
                            text: 'There was an error updating the ticket.',
                            icon: 'error',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000
                        });
                    }
                });
            });

            // Preview image when selected
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
