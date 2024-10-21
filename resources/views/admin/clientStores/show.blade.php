@extends('layouts.vertical', ['page_title' => 'Client Store Details'])

@section('content')
    <div class="container-fluid my-5">
        <h4 class="page-title">Client Store Details</h4>
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Name:</label>
                    <p class="form-control-plaintext">{{ $clientStore->name }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label">Domain:</label>
                    <p class="form-control-plaintext">{{ $clientStore->domain }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label">Secret Key:</label>
                    <p class="form-control-plaintext">{{ $clientStore->secret_key }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status:</label>
                    <p class="form-control-plaintext">{{ $clientStore->status }}</p>
                </div>
                <a href="{{ route('clientStores.index') }}" class="btn btn-secondary">Back to List</a>
                <a href="{{ route('clientStores.edit', $clientStore->id) }}" class="btn btn-primary">Edit</a>
            </div>
        </div>
    </div>
@endsection
