@extends('layouts.vertical', ['page_title' => 'Orders'])

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
                <div class="page-title-box">
                    <h4 class="page-title">Orders</h4>
                    <a href="{{ route('orders.export') }}" class="btn btn-info mb-2">Export as Excel <i class="ri-download-line ms-1"></i> </a>
                </div>
            </div>
        </div>

        <!-- Filter by Status -->
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
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
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
                <div class="card res-table-card">
                    <div class="card-body">
                        <table id="basic-datatable" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>User</th>
                                <th>Product</th>
                                <th>User ID In Application</th>
                                <th>Amount</th>
                                <th>Created At</th>
                                <th>Order Type</th>
                                <th>Domain</th>
                                <th>Currency</th>
                                <th>Price</th>
                                <th>Fee</th>
                                <th>Total</th>
                                <th>Balance</th>
                                <th>Debit balance</th>
                                <th>Updated At</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody id="order-table-body" class="text-center">
                            @foreach($orders as $order)
                                @php
                                    $feeGroup = $order->user->feeGroup;
                                    $feePercentage = $feeGroup->fee ?? 10; // Assuming 10% as default fee
                                    $feeAmount = ($feePercentage / 100) * $order->item_price;
                                @endphp

                                <tr id="order-{{ $order->id }}" class="text-center">


                                    <td class="text-center">{{ $order->id }}</td>
                                    <td class="text-center">
                                        <span>{{ $order->user->name }}</span>
                                        <span class="badge bg-info-subtle text-info rounded-pill"># {{ $order->user->id }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success-subtle text-success rounded-pill">{{ $order->item_name ?? "" }}</span> -
                                        <span class="badge bg-info-subtle text-info rounded-pill">{{ $order->sub_item_name ?? "" }}</span>

                                    </td>
                                    <td class="text-center">{{ $order->service_id ?? "" }}</td>


                                    <td class="text-center">
                                        {{ $order->amount ?? "" }}
                                    </td>
                                    <td>{{ $order->created_at->format('Y-m-d H:i:s') }}</td>

                                    <td>{{ $order->order_type }}</td>

                                    <td>{{ $order->subItem->clientStore->domain ?? "-" }}</td>

                                    <td>{{ $order->user->currency->currency ?? "USD" }}</td>
                                    <td>{{ $order->item_price ?? "" }}</td>
                                    <td>
                                        @if($order->user->feeGroup)
                                            <span class="badge bg-info-subtle text-info rounded-pill">{{ $order->fee_name ?? "" }} , {{ $order->item_fee . "%" ?? ""}}</span>
                                        @endif
                                            <span class="badge bg-success-subtle text-success rounded-pill">{{ number_format($feeAmount, 2) }} {{ $order->user->currency->currency ?? "USD" }}</span>


                                    </td>
                                    <td>{{ $order->total }}</td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success rounded-pill">{{ $order->wallet_before ?? "" }} {{ $order->user->currency->currency ?? "USD" }}</span>

                                    </td>
                                    <td>
                                        <span class="badge bg-danger-subtle text-danger rounded-pill">{{ $order->wallet_after ?? ""}} {{ $order->user->currency->currency ?? "USD" }}</span>

                                    </td>
                                    <td>{{ $order->updated_at->format('Y-m-d H:i:s') }}</td>
                                    <td>
                                        @if($order->status == 'canceled' || $order->status == 'refunded')
                                            <span class="badge bg-danger-subtle text-danger rounded-pill">
                                                {{ ucfirst($order->status) }}
                                                <i class="ri-close-circle-line ms-1 fs-14"></i>
                                            </span>
                                        @elseif($order->status == 'active')
                                            <span class="badge bg-success-subtle text-success rounded-pill">
                                                {{ ucfirst($order->status) }}
                                                <i class="bi bi-check2-circle ms-1 fs-14"></i>
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-warning rounded-pill">
                                                {{ ucfirst($order->status) }}
                                                <i class="bi bi-clock-history ms-1 fs-14"></i>
                                            </span>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
        'resources/js/pages/demo.form-advanced.js'
    ])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
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

            function applyFilters() {
                var status = $('#statusFilter').val();
                var dateRange = $('#reportrange').data('daterangepicker');
                var startDate = dateRange.startDate.format('YYYY-MM-DD');
                var endDate = dateRange.endDate.format('YYYY-MM-DD');
                var query = '?status=' + status + '&start_date=' + startDate + '&end_date=' + endDate;
                window.location.href = '{{ route('orders.index') }}' + query;
            }

            // $('#statusFilter').on('change', function() {
            //     applyFilters();
            // });

            $('#applyFilters').on('click', function() {
                applyFilters();
            });

            $('#reportrange').daterangepicker({
                startDate: moment().startOf('month'),
                endDate: moment().endOf('month'),
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
            }, function(start, end) {
                $('#selectedValue').html(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
                applyFilters();
            });

            $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
                applyFilters();
            });

            $('#clearFilter').on('click', function() {
                window.location.href = '{{ route('orders.index') }}';
            });
        });
    </script>
@endsection
