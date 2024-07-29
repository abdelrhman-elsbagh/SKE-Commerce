@extends('layouts.vertical', ['page_title' => 'Purchase Requests'])

@section('css')
    @vite([
        'node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css',
        'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css',
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.css',
        'node_modules/select2/dist/css/select2.min.css',
        'node_modules/daterangepicker/daterangepicker.css',
        'node_modules/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css',
        'node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
        'node_modules/bootstrap-timepicker/css/bootstrap-timepicker.min.css',
        'node_modules/flatpickr/dist/flatpickr.min.css'
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

        <!-- Filter by Status and Date -->
        <div class="row mb-2">
            <div class="col-lg-3 mb-2">
                <div>
                    <label class="form-label">Date Ranges</label>
                    <div id="reportrange" class="form-control" data-toggle="date-picker-range" data-target-display="#selectedValue" data-cancel-class="btn-light">
                        <i class="ri-calendar-2-line"></i>&nbsp;
                        <span id="selectedValue">{{ request('start_date') ? request('start_date') . ' - ' . request('end_date') : 'Select Date Range' }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">Filter Status</label>
                <select id="statusFilter" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">.</label>
                <button class="btn btn-primary col-12" id="applyFilters" style="display: block">Search</button>
            </div>

            <div class="col-md-3">
                <label class="form-label">.</label>
                <a class="btn btn-danger" style="display: block" id="clearFilter">Clear Filter</a>
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
                                <th>Email</th>
                                <th>Country</th>
                                <th>Created At</th>
                                <th>Amount</th>
                                <th>Currency</th>
                                <th>Payment Method</th>
                                <th>Updated At</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($purchaseRequests as $purchaseRequest)
                                <tr id="purchase-request-{{ $purchaseRequest->id }}">
                                    <td>{{ $purchaseRequest->id }}</td>
                                    <td>{{ $purchaseRequest->user->name ?? "" }}</td>
                                    <td>{{ $purchaseRequest->user->email ?? "" }}</td>
                                    <td>{{ $purchaseRequest->user->address ?? "" }}</td>
                                    <td>{{ $purchaseRequest->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td>{{ $purchaseRequest->amount ?? 0 }}</td>
                                    <td>{{ $purchaseRequest->user->currency->currency ?? "USD" }}</td>
                                    <td>{{ $purchaseRequest->paymentMethod->gateway ?? "" }}</td>
                                    <td>{{ $purchaseRequest->updated_at->format('Y-m-d H:i:s') }}</td>
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
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.js',
        'resources/js/pages/demo.form-advanced.js'
    ])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
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

            function applyFilters() {
                var status = $('#statusFilter').val();
                var dateRange = $('#reportrange').data('daterangepicker');
                var startDate = dateRange.startDate.format('YYYY-MM-DD');
                var endDate = dateRange.endDate.format('YYYY-MM-DD');
                var query = '?status=' + status + '&start_date=' + startDate + '&end_date=' + endDate;
                window.location.href = '{{ route('purchase-requests.index') }}' + query;
            }

            $('#applyFilters').on('click', function() {
                applyFilters();
            });

            $('#reportrange').daterangepicker({
                startDate: moment('{{ request('start_date') ?? now()->startOf('month') }}'),
                endDate: moment('{{ request('end_date') ?? now()->endOf('month') }}'),
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });

            $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
                applyFilters();
            });

            $('#clearFilter').on('click', function() {
                window.location.href = '{{ route('purchase-requests.index') }}';
            });
        });
    </script>
@endsection
