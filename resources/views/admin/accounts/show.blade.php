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
                    <h4 class="page-title">Transactions Details for <span class="text-info">( {{ $account->client->name ?? "" | $account->client->phone ?? ""  }} )</span></h4>
                    <a href="{{ route('accounts.export', ['client_id' => $account->client->id]) }}" class="btn btn-info mb-2">Export as Excel <i class="ri-download-line ms-1"></i></a>
                    <button class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#createAccountModal">Add New Transaction</button>
                </div>
            </div>
        </div>

        <!-- Display Totals for Each Currency -->
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
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->id }}</td>
                                    <td>
                                        @if($transaction->payment_status == 'satisfine')
                                            <span class="badge bg-success-subtle text-success rounded-pill">Satisfine</span>
                                        @elseif($transaction->payment_status == 'debtor')
                                            <span class="badge bg-success-subtle text-success rounded-pill">Debtor ( + )</span>
                                        @elseif($transaction->payment_status == 'creditor')
                                            <span class="badge bg-danger-subtle text-danger rounded-pill">Creditor ( - )</span>
                                        @endif
                                    </td>
                                    <td>{{ $transaction->amount }}</td>
                                    <td>{{ $transaction->currency->currency ?? 'No Currency' }}</td>
                                    <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
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
                            <input type="number" class="form-control" id="amount" name="amount" required>
                        </div>

                        <div class="mb-3">
                            <label for="currency_id" class="form-label">Currency</label>
                            <select class="form-control" id="currency_id" name="currency_id" required>
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->id }}">{{ $currency->currency }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes"></textarea>
                        </div>

                        <input type="hidden" name="client_id" value="{{ $account->client->id }}">
                        <button type="submit" class="btn btn-primary">Submit</button>
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
        });
    </script>
@endsection

