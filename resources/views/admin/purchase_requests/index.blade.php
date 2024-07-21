@extends('layouts.vertical', ['page_title' => 'Purchase Requests'])

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
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="page-title">Purchase Requests</h4>
                    <a href="{{ route('purchase-requests.create') }}" class="btn btn-primary">Create Purchase Request</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body  res-table-card">
                        <table id="basic-datatable" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>Request ID</th>
                                <th>User</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Payment Method</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($purchaseRequests as $purchaseRequest)
                                <tr id="purchase-request-{{ $purchaseRequest->id }}">
                                    <td>{{ $purchaseRequest->id }}</td>
                                    <td>{{ $purchaseRequest->user->name }}</td>
                                    <td>{{ $purchaseRequest->amount }}</td>
                                    <td>
                                        <span class="badge
                                            @if($purchaseRequest->status == 'pending') bg-warning-subtle text-warning
                                            @elseif($purchaseRequest->status == 'approved') bg-success-subtle text-success
                                            @elseif($purchaseRequest->status == 'rejected') bg-danger-subtle text-danger
                                            @else bg-secondary-subtle text-secondary
                                            @endif">
                                            {{ ucfirst($purchaseRequest->status) }}

                                            @if($purchaseRequest->status == 'pending')
                                                <i class="bi bi-clock-history ms-1 fs-14"></i>
                                            @elseif($purchaseRequest->status == 'approved')
                                                <i class="bi bi-check2-circle ms-1 fs-14"></i>
                                            @elseif($purchaseRequest->status == 'rejected')
                                                <i class="ri-close-circle-line ms-1 fs-14"></i>
                                            @else
                                            @endif
                                        </span>
                                    </td>
                                    <td>{{ $purchaseRequest->paymentMethod->gateway ?? "" }}</td>
                                    <td>{{ $purchaseRequest->created_at ?? "" }}</td>
                                    <td>{{ $purchaseRequest->updated_at ?? "" }}</td>
                                    <td>
                                        <a href="{{ route('purchase-requests.show', $purchaseRequest->id) }}" class="btn btn-info"><i class=" ri-eye-line"></i></a>
                                        @if($purchaseRequest->created_at == $purchaseRequest->updated_at)
                                            <a href="{{ route('purchase-requests.edit', $purchaseRequest->id) }}" class="btn btn-warning"><i class="ri-edit-box-fill"></i></a>
                                        @endif
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this purchase request?
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
            var purchaseRequestIdToDelete;

            // Open delete confirmation modal
            $(document).on('click', '.btn-delete', function() {
                purchaseRequestIdToDelete = $(this).data('id');
                $('#deleteModal').modal('show');
            });

            // Confirm delete
            $('#confirmDelete').on('click', function() {
                $.ajax({
                    url: '{{ route('purchase-requests.index') }}/' + purchaseRequestIdToDelete,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(result) {
                        $('#deleteModal').modal('hide');
                        $('#purchase-request-' + purchaseRequestIdToDelete).remove();
                        $.toast({
                            heading: 'Success',
                            text: 'Purchase request deleted successfully.',
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
                            text: 'An error occurred while deleting the purchase request.',
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
