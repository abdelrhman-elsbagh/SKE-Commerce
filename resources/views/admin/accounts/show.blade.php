@extends('layouts.vertical', ['page_title' => 'Account Details'])

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
                    <h4 class="page-title">Transactions Details for <span class="text-info">{{ $client->name ?? "" }} ( #{{ $client->id ?? "" }} )</span></h4>
                    <a href="{{ route('accounts.export', ['client_id' => $client->id]) }}" class="btn btn-info mb-2">Export as Excel <i class="ri-download-line ms-1"></i></a>
                    <button class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#createAccountModal">Add New Transaction</button>
                </div>
            </div>
        </div>

        <!-- Display Totals for Each Currency -->
        <div class="row">
            @foreach($totals as $total)
                @php
                    $cardColor = $total->total_amount < 0 ? 'danger' : 'success';
                @endphp
                <div class="col-md-4">
                    <div class="card border-left-{{ $cardColor }}" style="border-left: 5px solid {{ $total->total_amount > 0 ? '#28a745' : '#dc3545' }};">
                        <div class="card-body">
                            <h5 class="card-title">{{ $client->name }}</h5>
                            <p class="card-text">
                                <strong>Currency:</strong> {{ $total->currency->currency ?? 'No Currency' }}<br>
                                <strong>Total Amount:</strong> <span style="color: {{ $total->total_amount >= 0 ? '#28a745' : '#dc3545' }};">{{ number_format($total->total_amount, 2) }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Transactions Table -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Transactions</h4>
                        <table id="basic-datatable" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Payment Status</th>
                                <th>Amount</th>
                                <th>Currency</th>
                                <th>Notes</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($transactions as $transaction)
                                <tr id="transaction-{{ $transaction->id }}">
                                    <td>{{ $transaction->mask ?? "" }}</td>
                                    <td>
                                        @if($transaction->payment_status == 'satisfine')
                                            <span class="badge bg-success-subtle text-success rounded-pill">Satisfine</span>
                                        @elseif($transaction->payment_status == 'debtor')
                                            <span class="badge bg-success-subtle text-success rounded-pill">Debtor ( + )</span>
                                        @elseif($transaction->payment_status == 'creditor')
                                            <span class="badge bg-danger-subtle text-danger rounded-pill">Creditor ( - )</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($transaction->amount, 2) }}</td>
                                    <td>{{ $transaction->currency->currency ?? 'No Currency' }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($transaction->notes, 70, '...') }}</td>
                                    <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm btn-edit" data-id="{{ $transaction->id }}" data-bs-toggle="modal" data-bs-target="#editAccountModal">Edit</button>
                                        <button class="btn btn-danger btn-sm btn-delete" data-id="{{ $transaction->id }}">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div> <!-- end col -->
        </div> <!-- end row -->

        <div class="row">
            <div class="col-12 mb-4">
                <a href="{{ route('accounts.index') }}" class="btn btn-secondary mt-3">Back</a>
            </div>
        </div>
    </div>

    <!-- Create Account Modal -->
    <div class="modal fade" id="createAccountModal" tabindex="-1" aria-labelledby="createAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createAccountModalLabel">Add New Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="create-account-form" action="{{ route('accounts.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="payment_status" class="form-label">Payment Status</label>
                            <select class="form-control" id="payment_status" name="payment_status" required>
                                <option value="creditor">Creditor ( - )</option>
                                <option value="debtor">Debtor ( + )</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" step="0.1" class="form-control" id="amount" name="amount" required>
                        </div>

                        <div class="mb-3">
                            <label for="currency_id" class="form-label">Currency</label>
                            <select class="form-control" id="currency_id" name="currency_id" required>
                                @foreach($client->currencies as $currency)
                                    <option value="{{ $currency->id }}">{{ $currency->currency }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes"></textarea>
                        </div>

                        <input type="hidden" name="client_id" value="{{ $client->id }}">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Account Modal -->
    <div class="modal fade" id="editAccountModal" tabindex="-1" aria-labelledby="editAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAccountModalLabel">Edit Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-account-form" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="edit_payment_status" class="form-label">Payment Status</label>
                            <select class="form-control" id="edit_payment_status" name="payment_status" required>
                                <option value="creditor">Creditor ( - )</option>
                                <option value="debtor">Debtor ( + )</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="edit_amount" class="form-label">Amount</label>
                            <input type="number" step="1" class="form-control" id="edit_amount" name="amount" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_currency_id" class="form-label">Currency</label>
                            <select class="form-control" id="edit_currency_id" name="currency_id" required>
                                @foreach($client->currencies as $currency)
                                    <option value="{{ $currency->id }}">{{ $currency->currency }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="edit_notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="edit_notes" name="notes"></textarea>
                        </div>

                        <input type="hidden" name="client_id" value="{{ $client->id }}">
                        <input type="hidden" id="transaction_id" name="transaction_id">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
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
            // Handle Create Transaction
            $('#create-account-form').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(response) {
                        $.toast().reset('all');
                        $.toast({
                            heading: 'Success',
                            text: 'Transaction created successfully.',
                            icon: 'success',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000,
                            afterHidden: function () {
                                location.reload(); // Reload the page to reflect the new transaction
                            }
                        });

                        $('#createAccountModal').modal('hide');
                        $('#create-account-form')[0].reset();
                    },
                    error: function(response) {
                        $.toast().reset('all');
                        $.toast({
                            heading: 'Error',
                            text: 'There was an error creating the transaction.',
                            icon: 'error',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000
                        });
                    }
                });
            });

            // Handle Edit Transaction
            $('.btn-edit').on('click', function() {
                const transactionId = $(this).data('id');

                // Fetch the transaction data using AJAX
                $.ajax({
                    url: '{{ route("accounts.getData", ":id") }}'.replace(':id', transactionId), // Use the new route correctly
                    method: 'GET',
                    success: function(response) {
                        console.log(response); // For debugging, check if the response data is correct

                        // Populate the form fields with the data
                        $('#edit_payment_status').val(response.payment_status);
                        $('#edit_amount').val(response.amount);
                        $('#edit_currency_id').val(response.currency_id);
                        $('#edit_notes').val(response.notes);
                        $('#transaction_id').val(transactionId);
                        $('#edit-account-form').attr('action', '{{ url("/admin/accounts") }}/' + transactionId);
                    },
                    error: function(xhr) {
                        console.error("An error occurred: ", xhr.responseText);
                    }
                });
            });

            // Handle Edit Transaction Form Submission
            $('#edit-account-form').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(response) {
                        $.toast().reset('all');
                        $.toast({
                            heading: 'Success',
                            text: 'Transaction updated successfully.',
                            icon: 'success',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000,
                            afterHidden: function () {
                                location.reload(); // Reload the page to reflect the updated transaction
                            }
                        });

                        $('#editAccountModal').modal('hide');
                    },
                    error: function(response) {
                        $.toast().reset('all');
                        $.toast({
                            heading: 'Error',
                            text: 'There was an error updating the transaction.',
                            icon: 'error',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000
                        });
                    }
                });
            });

            // Handle Delete Transaction
            $('.btn-delete').on('click', function() {
                const transactionId = $(this).data('id');
                if (confirm('Are you sure you want to delete this transaction?')) {
                    $.ajax({
                        url: '/admin/accounts/' + transactionId,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            $.toast().reset('all');
                            $.toast({
                                heading: 'Success',
                                text: 'Transaction deleted successfully.',
                                icon: 'success',
                                loader: true,
                                loaderBg: '#f96868',
                                position: 'top-right',
                                hideAfter: 3000,
                                afterHidden: function () {
                                    location.reload(); // Reload the page to reflect the deletion
                                }
                            });
                        },
                        error: function(response) {
                            $.toast().reset('all');
                            $.toast({
                                heading: 'Error',
                                text: 'There was an error deleting the transaction.',
                                icon: 'error',
                                loader: true,
                                loaderBg: '#f96868',
                                position: 'top-right',
                                hideAfter: 3000
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection
