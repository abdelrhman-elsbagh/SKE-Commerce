{{-- resources/views/clientStores/index.blade.php --}}
@extends('layouts.vertical', ['page_title' => 'Client Stores'])

@section('css')
    @vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css'])
@endsection

@section('content')
    <div class="container-fluid mt-5">
        <h4 class="page-title">Client Stores</h4>
        <div class="card">
            <div class="card-body">
                <a href="{{ route('clientStores.create') }}" class="btn btn-primary mb-3">Add New Client Store</a>
                <table class="table table-bordered dt-responsive nowrap">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Domain</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($clientStores as $clientStore)
                        <tr>
                            <td>{{ $clientStore->id }}</td>
                            <td>{{ $clientStore->name }}</td>
                            <td>{{ $clientStore->domain }}</td>
                            <td>{{ $clientStore->status }}</td>
                            <td>
                                <a href="{{ route('clientStores.show', $clientStore->id) }}" class="btn btn-info">View</a>
                                <a href="{{ route('clientStores.edit', $clientStore->id) }}" class="btn btn-warning">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @vite(['resources/js/pages/demo.datatable-init.js'])
@endsection
