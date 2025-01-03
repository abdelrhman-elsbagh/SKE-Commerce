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
                    <label class="form-label">Email:</label>
                    <p class="form-control-plaintext">{{ $clientStore->email }}</p>
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
                    <label class="form-label">Orders Count:</label>
                    <p class="form-control-plaintext">{{ $clientStore->external_orders_count ?? 0 }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label">Cost Orders Total (USD):</label>
                    <p class="form-control-plaintext">USD {{ number_format($clientStore->external_orders_total ?? 0, 2) }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label">Imported Items:</label>
                    <p class="form-control-plaintext">{{ $clientStore->total_sub_items_count ?? 0 }}</p>
                </div>
                <a href="{{ route('clientStores.index') }}" class="btn btn-secondary">Back to List</a>
                <a href="{{ route('clientStores.edit', $clientStore->id) }}" class="btn btn-primary">Edit</a>
            </div>
        </div>
    </div>
@endsection
