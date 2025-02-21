@extends('layouts.vertical', ['page_title' => 'Ticket Details'])

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
                    <h4 class="page-title">Ticket Details</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="ticket_category" class="form-label">Category</label>
                            <p>{{ $ticket->category->name }}</p>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <p>{{ $ticket->message }}</p>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <p>{{ ucfirst($ticket->status) }}</p>
                        </div>

                        @if($ticket->getFirstMediaUrl('images'))
                            <div class="mb-3">
                                <label for="image" class="form-label">Ticket Image</label>
                                <img src="{{ $ticket->getFirstMediaUrl('images') }}" alt="Ticket Image" style="max-width: 300px; max-height: 200px;">
                            </div>
                        @endif

                        <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-warning"><i class="ri-edit-box-fill"></i> Edit</a>
                        <a href="{{ route('tickets.index') }}" class="btn btn-secondary">Back to Tickets</a>
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
            // Show toast for success or error messages passed via session flash
            @if(session('success'))
            $.toast().reset('all'); // Reset any existing toasts
            $.toast({
                heading: 'Success',
                text: '{{ session('success') }}',
                icon: 'success',
                loader: true,
                loaderBg: '#f96868',
                position: 'top-right',
                hideAfter: 3000
            });
            @elseif(session('error'))
            $.toast().reset('all');
            $.toast({
                heading: 'Error',
                text: '{{ session('error') }}',
                icon: 'error',
                loader: true,
                loaderBg: '#f96868',
                position: 'top-right',
                hideAfter: 3000
            });
            @endif
        });
    </script>
@endsection
