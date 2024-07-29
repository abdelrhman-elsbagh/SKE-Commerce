@extends('layouts.vertical', ['page_title' => 'User Wallets'])

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
                <div class="card">
                    <div class="card-body res-table-card">
                        <h4 class="header-title">User Wallets</h4>
                        <table id="basic-datatable" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>User ID</th>
                                <th>User</th>
                                <th>Email</th>
                                <th>Country</th>
                                <th>Currency</th>
                                <th>Active Orders</th>
                                <th>Refunded Orders</th>
                                <th>Total Deposit</th>
                                <th>Total Orders</th>
                                <th>Wallet Balance</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($wallets as $wallet)
                                <tr id="wallet-{{ $wallet->id }}">
                                    <td>{{ $wallet->user->id }}</td>
                                    <td>{{ $wallet->user->name }}</td>
                                    <td>{{ $wallet->user->email }}</td>
                                    <td>{{ $wallet->user->address }}</td>
                                    <td>{{ $wallet->user->currency->currency ?? "USD" }}</td>
                                    <td>{{ $wallet->activeOrdersCount }}</td>
                                    <td>{{ $wallet->refundedOrdersCount }}</td>
                                    <td>{{ $wallet->approvedPurchaseRequestSum ?? 0 }}</td>
                                    <td>{{ $wallet->activePendingOrdersSum ?? 0 }}</td>
                                    <td>{{ $wallet->balance ?? "" }}</td>
                                    <td class="text-center">
                                        @if($wallet->increase_status == 'increased')
                                            <span class="badge bg-success-subtle text-success rounded-pill" style="font-size: 18px">+</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger rounded-pill" style="font-size: 18px">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('user-wallets.show', $wallet->id) }}" class="btn btn-info"><i class="ri-eye-line"></i></a>
                                        <a href="{{ route('user-wallets.edit', $wallet->id) }}" class="btn btn-warning"><i class="ri-edit-box-fill"></i></a>
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
                    Are you sure you want to delete this wallet?
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
            var walletIdToDelete;

            // Open delete confirmation modal
            $(document).on('click', '.btn-delete', function() {
                walletIdToDelete = $(this).data('id');
                $('#deleteModal').modal('show');
            });

            // Confirm delete
            $('#confirmDelete').on('click', function() {
                $.ajax({
                    url: '{{ route('user-wallets.index') }}/' + walletIdToDelete,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(result) {
                        $('#deleteModal').modal('hide');
                        $('#wallet-' + walletIdToDelete).remove();
                        // Show success message
                        $.toast().reset('all'); // Reset previous toasts
                        $.toast({
                            heading: 'Success',
                            text: 'Wallet deleted successfully.',
                            icon: 'success',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000
                        });
                    },
                    error: function(err) {
                        // Show error message
                        $.toast().reset('all'); // Reset previous toasts
                        $.toast({
                            heading: 'Error',
                            text: 'An error occurred while deleting the wallet.',
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
