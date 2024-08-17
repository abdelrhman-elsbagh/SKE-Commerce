@extends('layouts.vertical', ['page_title' => 'Accounts'])

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Accounts</h4>
                    <a href="{{ route('accounts.export') }}" class="btn btn-info mb-2">Export as Excel <i class="ri-download-line ms-1"></i> </a>
                </div>
            </div>
        </div>

        <form method="GET" action="{{ route('accounts.index') }}">
            <div class="row mb-2">
                <div class="col-lg-3 mb-2">
                    <div>
                        <label class="form-label">Date Ranges</label>
                        <div id="reportrange" class="form-control" data-toggle="date-picker-range" data-target-display="#selectedValue" data-cancel-class="btn-light">
                            <i class="ri-calendar-2-line"></i>&nbsp;
                            <span id="selectedValue">{{ request('start_date') ? request('start_date') . ' - ' . request('end_date') : 'Select Date Range' }}</span>
                            <input type="hidden" name="start_date" id="start_date" value="{{ request('start_date') }}">
                            <input type="hidden" name="end_date" id="end_date" value="{{ request('end_date') }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Filter Status</label>
                    <select id="statusFilter" name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="creditor" {{ request('status') == 'creditor' ? 'selected' : '' }}>Creditor ( - )</option>
                        <option value="debtor" {{ request('status') == 'debtor' ? 'selected' : '' }}>Debtor ( + )</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Filter Profile</label>
                    <select id="profileFilter" name="profile" class="form-select">
                        @foreach($profiles as $profile)
                            <option value="{{ $profile }}" {{ request('profile') == $profile ? 'selected' : '' }}>{{ $profile }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label">.</label>
                    <button class="btn btn-primary col-12" id="applyFilters" style="display: block">Search</button>
                </div>
                <div class="col-md-1">
                    <label class="form-label">.</label>
                    <a class="btn btn-danger" style="display: block" id="clearFilter" href="{{ route('accounts.index') }}">Clear Filter</a>
                </div>
            </div>
        </form>

        <!-- Display Totals for Only the Filtered Client -->
        @if($totals->isNotEmpty())
            <div class="row">
                @foreach($totals as $total)
                    <div class="col-md-4">
                        <div class="card" style="border-left: 5px solid {{ $loop->index % 2 == 0 ? '#28a745' : '#007bff' }};">
                            <div class="card-body">
                                <h5 class="card-title">{{ $total->client->name }}</h5>
                                <p class="card-text">
                                    <strong>Currency:</strong> {{ $total->currency->currency ?? 'No Currency' }}<br>
                                    <strong>Total Amount:</strong> <span style="color: {{ $total->total_amount >= 0 ? '#28a745' : '#dc3545' }};">{{ $total->total_amount }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p>No results found for the selected client.</p>
        @endif

        <!-- Existing Accounts Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Accounts</h4>
                        <table id="basic-datatable" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Payment Status</th>
                                <th>Amount</th>
                                <th>Client Name</th>
                                <th>Client Phone</th>
                                <th>Currency</th>
                                <th>Notes</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($accounts as $account)
                                <tr id="account-{{ $account->id }}">
                                    <td>{{ $account->id }}</td>
                                    <td>
                                        @if($account->payment_status == 'satisfine')
                                            <span class="badge bg-success-subtle text-success rounded-pill">Satisfine</span>
                                        @elseif($account->payment_status == 'debtor')
                                            <span class="badge bg-success-subtle text-success rounded-pill">Debtor ( + )</span>
                                        @elseif($account->payment_status == 'creditor')
                                            <span class="badge bg-danger-subtle text-danger rounded-pill">Creditor ( - )</span>
                                        @endif
                                    </td>
                                    <td>{{ $account->amount }}</td>
                                    <td>{{ $account->client->name  }}</td>
                                    <td>{{ $account->client->phone  }}</td>
                                    <td>
                                        @if($account->currency)
                                            {{ $account->currency->currency }}
                                        @else
                                            No Currency
                                        @endif
                                    </td>
                                    <td>{{ \Illuminate\Support\Str::limit($account->notes, 70, '...') }}</td>
                                    <td>
                                        <a href="{{ route('accounts.show', $account->id) }}" class="btn btn-info"><i class="ri-eye-line"></i></a>
                                        <a href="{{ route('accounts.edit', $account->id) }}" class="btn btn-warning"><i class="ri-edit-box-fill"></i></a>
                                        <button class="btn btn-danger btn-delete" data-id="{{ $account->id }}"><i class="ri-delete-bin-5-line"></i></button>
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
                    Are you sure you want to delete this account?
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
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.js',
        'resources/js/pages/demo.form-advanced.js'
    ])
@endsection

@section('admin-script')
    <script>
        $(document).ready(function() {
            var accountIdToDelete;

            // Trigger the delete confirmation modal
            $(document).on('click', '.btn-delete', function() {
                accountIdToDelete = $(this).data('id');
                $('#deleteModal').modal('show');
            });

            // Handle the delete action once confirmed
            $('#confirmDelete').on('click', function() {
                $.ajax({
                    url: '{{ route('accounts.index') }}/' + accountIdToDelete,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(result) {
                        $('#deleteModal').modal('hide');
                        $('#account-' + accountIdToDelete).remove();
                        $.toast().reset('all');
                        $.toast({
                            heading: 'Success',
                            text: 'Account deleted successfully.',
                            icon: 'success',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000
                        });
                    },
                    error: function(err) {
                        $.toast().reset('all');
                        $.toast({
                            heading: 'Error',
                            text: 'An error occurred while deleting the account.',
                            icon: 'error',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000
                        });
                    }
                });
            });

            // Initialize date range picker
            $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
                $('#start_date').val(picker.startDate.format('YYYY-MM-DD'));
                $('#end_date').val(picker.endDate.format('YYYY-MM-DD'));
            });
        });
    </script>
@endsection
