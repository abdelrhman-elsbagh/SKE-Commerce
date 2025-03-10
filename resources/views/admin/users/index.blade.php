@extends('layouts.vertical', ['page_title' => 'Users'])

@section('css')
    @vite([
        'node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css',
        'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css',
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.css'
    ])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Users</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body res-table-card">
                        <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">Create New User</a>
                        <table id="basic-datatable" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Type</th>
                                <th>Name</th>
                                <th>Whatsapp Num</th>
                                <th>Email</th>
                                <th>Country</th>
                                <th>Roles</th>
                                <th>Status</th>
                                <th>Fee Group</th>
                                <th>Currency</th>
                                <th>Balance</th>
                                <th>Total Orders</th>
                                <th>Domain</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr id="user-{{ $user->id }}">
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        {{ $user->hasPermissionTo('admin') ? 'Staff' : ($user->is_external == 1 ? "API Partner" : "Client") }}
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->phone ?? "" }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->address }}</td>
                                    <td>{{ implode(', ', $user->roles->pluck('name')->toArray()) }}</td>
                                    <td>
                                        <span class="badge @if($user->status == 'active') bg-success-subtle text-success @elseif($user->status == 'inactive') bg-danger-subtle text-danger @endif rounded-pill">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $user->feeGroup->name ?? "" }}</td>
                                    <td>{{ $user->currency->currency ?? "USD" }}</td>
                                    <td>{{ number_format($user->wallet->balance ?? 0, 2) }} {{ $user->currency->currency ?? "USD" }}</td>
                                    <td>{{ number_format($user->total_orders, 2) }} {{ $user->currency->currency ?? "USD" }}</td>
                                    <td>{{ $user->domain ?? "NA" }}</td>
                                    <td>
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning"><i class="ri-edit-box-fill"></i></a>
                                        <button class="btn btn-danger btn-delete" data-id="{{ $user->id }}"><i class="ri-delete-bin-5-line"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div> <!-- container -->

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
                    Are you sure you want to delete this user?
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
    @vite([
        'resources/js/pages/demo.datatable-init.js',
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.js'
    ])

    <script>
        $(document).ready(function() {
            var userIdToDelete;

            // Open delete confirmation modal
            $(document).on('click', '.btn-delete', function() {
                userIdToDelete = $(this).data('id');
                $('#deleteModal').modal('show');
            });

            // Confirm delete
            $('#confirmDelete').on('click', function() {
                $.ajax({
                    url: '{{ route('users.index') }}/' + userIdToDelete,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(result) {
                        $('#deleteModal').modal('hide');
                        $('#user-' + userIdToDelete).remove();
                        $.toast({
                            heading: 'Success',
                            text: 'User deleted successfully.',
                            icon: 'success',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000
                        });
                    },
                    error: function(xhr) {
                        $.toast({
                            heading: 'Error',
                            text: 'An error occurred while deleting the user.',
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
