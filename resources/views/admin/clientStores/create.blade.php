{{-- resources/views/clientStores/create.blade.php --}}
@extends('layouts.vertical', ['page_title' => 'Create Client Store'])

@section('css')
    @vite(['node_modules/jquery-toast-plugin/dist/jquery.toast.min.css'])
@endsection

@section('content')
    <div class="container-fluid mt-3">
        <h4 class="page-title">Create Client Store</h4>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('clientStores.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="domain" class="form-label">Domain</label>
                        <input type="url" class="form-control" id="domain" name="domain" required>
                    </div>
                    <div class="mb-3">
                        <label for="secret_key" class="form-label">Secret Key</label>
                        <input type="text" class="form-control" id="secret_key" name="secret_key" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <input type="text" class="form-control" id="status" value="pending" readonly>
                    </div>
                    <button type="submit" class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @vite(['node_modules/jquery-toast-plugin/dist/jquery.toast.min.js'])
@endsection
