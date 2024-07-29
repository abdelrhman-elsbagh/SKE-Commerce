@extends('layouts.vertical', ['page_title' => 'Create/Edit Notification'])

@section('css')
    @vite(['node_modules/jquery-toast-plugin/dist/jquery.toast.min.css'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">{{ isset($notification) ? 'Edit Notification' : 'Create Notification' }}</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form id="create-edit-notification-form" action="{{ isset($notification) ? route('notifications.update', $notification->id) : route('notifications.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @if(isset($notification))
                                @method('PUT')
                            @endif
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ $notification->title ?? '' }}">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description">{{ $notification->description ?? '' }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="attachment" class="form-label">Attachment</label>
                                <input type="file" class="form-control" id="attachment" name="attachment" accept="image/*">
                                <div class="mt-2" id="attachment-preview">
                                    @if(isset($notification) && $notification->getFirstMediaUrl('attachments'))
                                        <img src="{{ $notification->getFirstMediaUrl('attachments') }}" alt="Attachment Preview" style="max-width: 200px;">
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

    <script>
        $(document).ready(function() {
            $('#attachment').change(function() {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#attachment-preview').html('<img src="' + e.target.result + '" alt="Attachment Preview" style="max-width: 200px;">');
                }
                reader.readAsDataURL(this.files[0]);
            });

            $('#create-edit-notification-form').on('submit', function(e) {
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
                            text: 'Notification created/updated successfully.',
                            icon: 'success',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000,
                            afterHidden: function () {
                                window.location.href = "{{ route('notifications.index') }}";
                            }
                        });

                        // Optionally, reset the form fields
                        $('#create-edit-notification-form')[0].reset();
                        $('#attachment-preview').html('');
                    },
                    error: function(response) {
                        $.toast().reset('all'); // Reset previous toasts
                        $.toast({
                            heading: 'Error',
                            text: 'There was an error creating/updating the notification.',
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
