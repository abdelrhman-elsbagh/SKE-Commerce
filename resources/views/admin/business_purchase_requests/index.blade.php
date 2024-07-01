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
                    <a href="{{ route('business-purchase-requests.create') }}" class="btn btn-primary">Create Purchase Request</a>
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
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Payment Method</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($purchaseRequests as $purchaseRequest)
                                <tr id="purchase-request-{{ $purchaseRequest->id }}">
                                    <td>{{ $purchaseRequest->id }}</td>
                                    <td>{{ $purchaseRequest->businessClient->name }}</td>
                                    <td>{{ $purchaseRequest->amount }}</td>
                                    <td style="color: @if($purchaseRequest->status == 'canceled' || $purchaseRequest->status == 'rejected') #F00 @elseif($purchaseRequest->status == 'approved') #1abc9c @endif;">{{ ucfirst($purchaseRequest->status) }}</td>
                                    <td>{{ $purchaseRequest->paymentMethod->gateway ?? "" }}</td>
                                    <td>
                                        <a href="{{ route('business-purchase-requests.show', $purchaseRequest->id) }}" class="btn btn-info">Show</a>
                                        <a href="{{ route('business-purchase-requests.edit', $purchaseRequest->id) }}" class="btn btn-warning">Edit</a>
                                        <button type="button" class="btn btn-danger btn-delete" data-id="{{ $purchaseRequest->id }}">Delete</button>
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
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
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
                    url: '{{ route('business-purchase-requests.index') }}/' + purchaseRequestIdToDelete,
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
