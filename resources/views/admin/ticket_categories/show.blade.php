@extends('layouts.vertical', ['page_title' => 'Ticket Category Details'])

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
                    <h4 class="page-title">Ticket Category Details</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name</label>
                            <p>{{ $category->name }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="ar_name" class="form-label">Arabic Name</label>
                            <p>{{ $category->ar_name }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="desc" class="form-label">Description</label>
                            <p>{{ $category->desc }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="ar_desc" class="form-label">Arabic Description</label>
                            <p>{{ $category->ar_desc }}</p>
                        </div>

                        <a href="{{ route('ticket_categories.edit', $category->id) }}" class="btn btn-warning"><i class="ri-edit-box-fill"></i> Edit</a>
                        <a href="{{ route('ticket_categories.index') }}" class="btn btn-secondary">Back to Categories</a>
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
