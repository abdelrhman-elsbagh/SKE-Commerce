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
                        <th>Email</th>
                        <th>Domain</th>
                        <th>Orders Count</th>
                        <th>Cost Orders Total (USD)</th>
                        <th>Imported Items</th> <!-- New column for total sub-items -->
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($clientStores as $clientStore)
                        <tr id="client-{{ $clientStore->id }}">
                            <td>{{ $clientStore->id }}</td>
                            <td>{{ $clientStore->name }}</td>
                            <td>{{ $clientStore->email ?? "" }}</td>
                            <td>{{ $clientStore->domain }}</td>
                            <td>{{ $clientStore->external_orders_count ?? 0 }}</td>
                            <td>USD {{ number_format($clientStore->external_orders_total ?? 0, 2) }}</td>
                            <td>{{ $clientStore->total_sub_items_count ?? 0 }}</td> <!-- Display total sub-items -->
                            <td>
                                <a href="{{ route('clientStores.show', $clientStore->id) }}" class="btn btn-info">View</a>
                                <a href="{{ route('clientStores.edit', $clientStore->id) }}" class="btn btn-warning">Edit</a>
                                <button type="button" class="btn btn-danger btn-delete" data-id="{{ $clientStore->id }}"><i class="ri-delete-bin-5-line"></i></button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this client?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete"><i class="ri-delete-bin-5-line"></i></button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @vite(['resources/js/pages/demo.datatable-init.js'])

    <script>
        $(document).ready(function() {
            var clientIdToDelete;

            // Open delete confirmation modal
            $(document).on('click', '.btn-delete', function() {
                clientIdToDelete = $(this).data('id');
                $('#deleteModal').modal('show');
            });

            // Confirm delete
            $('#confirmDelete').on('click', function() {
                $.ajax({
                    url: '{{ route('clientStores.index') }}/' + clientIdToDelete,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(result) {
                        $('#deleteModal').modal('hide');
                        $('#client-' + clientIdToDelete).remove();
                        $.toast().reset('all'); // Reset previous toasts
                        $.toast({
                            heading: 'Success',
                            text: 'Client deleted successfully.',
                            icon: 'success',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000
                        });
                    },
                    error: function(response) {
                        $.toast().reset('all'); // Reset previous toasts
                        $.toast({
                            heading: 'Error',
                            text: 'There was an error deleting the client.',
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
