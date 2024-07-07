@extends('layouts.vertical', ['page_title' => 'Orders'])

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
                    <h4 class="page-title">Orders</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <table id="basic-datatable" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Service ID</th>
                                <th>Order Type</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $order)
                                <tr id="order-{{ $order->id }}">
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->user->name }}</td>
                                    <td>{{ $order->subItems[0]->service_id ?? ""}}</td>
                                    <td>{{ $order->order_type }}</td>
                                    <td>{{ $order->created_at }}</td>
                                    <td>{{ $order->updated_at }}</td>
                                    <td>{{ $order->total }}</td>

                                    <td>
                                        @if($order->status == 'canceled' || $order->status == 'refunded')
                                            <span class="badge bg-danger-subtle text-danger rounded-pill">{{ ucfirst($order->status) }}</span>
                                        @elseif($order->status == 'active')
                                            <span class="badge bg-success-subtle text-success rounded-pill">{{ ucfirst($order->status) }}</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-warning rounded-pill">{{ ucfirst($order->status) }}</span>
                                        @endif
                                    </td>

                                    <td>
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-info"><i class=" ri-eye-line"></i></a>
                                        <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-warning"><i class="ri-edit-box-fill"></i></a>
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
                    Are you sure you want to delete this order?
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
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.js',
    ])

    <script>
        $(document).ready(function() {
            var orderIdToDelete;

            // Open delete confirmation modal
            $(document).on('click', '.btn-delete', function() {
                orderIdToDelete = $(this).data('id');
                $('#deleteModal').modal('show');
            });

            // Confirm delete
            $('#confirmDelete').on('click', function() {
                $.ajax({
                    url: '{{ route('orders.index') }}/' + orderIdToDelete,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(result) {
                        $('#deleteModal').modal('hide');
                        $('#order-' + orderIdToDelete).remove();
                        $.toast({
                            heading: 'Success',
                            text: 'Order deleted successfully.',
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
                            text: 'An error occurred while deleting the order.',
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
