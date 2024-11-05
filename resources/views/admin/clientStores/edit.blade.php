{{-- resources/views/clientStores/edit.blade.php --}}
@extends('layouts.vertical', ['page_title' => 'Edit Client Store'])

@section('css')
    @vite(['node_modules/jquery-toast-plugin/dist/jquery.toast.min.css'])
@endsection

@section('content')
    <div class="container-fluid my-5">
        <h4 class="page-title">Edit Client Store</h4>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('clientStores.update', $clientStore->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $clientStore->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="domain" class="form-label">Domain</label>
                        <input type="url" class="form-control" id="domain" name="domain" value="{{ $clientStore->domain }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="secret_key" class="form-label">Secret Key</label>
                        <input type="text" class="form-control" id="secret_key" name="secret_key" value="{{ $clientStore->secret_key }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @vite(['node_modules/jquery-toast-plugin/dist/jquery.toast.min.js'])
@endsection