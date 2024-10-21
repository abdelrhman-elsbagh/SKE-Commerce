@extends('layouts.vertical', ['page_title' => 'Edit Account'])

@section('css')
    @vite([
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.css',
        'node_modules/select2/dist/css/select2.min.css',
         'node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css',
        'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css',
    ])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Edit Account</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form id="edit-account-form" action="{{ route('accounts.update', $account->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="payment_status" class="form-label">Payment Status</label>
                                <select class="form-control" id="payment_status" name="payment_status" required>
                                    <option value="creditor" {{ $account->payment_status == 'creditor' ? 'selected' : '' }}>Creditor ( - )</option>
                                    <option value="debtor" {{ $account->payment_status == 'debtor' ? 'selected' : '' }}>Debtor ( + )</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="number" step="0.1" class="form-control" id="amount" name="amount" value="{{ $account->amount }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="client_id" class="form-label">Client</label>
                                <select class="form-control select2" id="client_id" name="client_id" disabled>
                                    <option value="{{ $account->client->id }}">{{ $account->client->name ?? "" }} | {{ $account->client->phone ?? "" }}</option>
                                </select>
                                <input type="hidden" name="client_id" value="{{ $account->client->id }}">
                            </div>

                            <div class="mb-3">
                                <label for="currency_id" class="form-label">Currency</label>
                                <select class="form-control select2" id="currency_id" name="currency_id" required>
                                    <!-- Currencies will be loaded here based on client selection -->
                                    @foreach($account->client->currencies as $currency)
                                        <option value="{{ $currency->id }}" {{ $account->currency_id == $currency->id ? 'selected' : '' }}>
                                            {{ $currency->currency }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control" id="notes" name="notes">{{ $account->notes ?? '' }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @vite([
        'resources/js/pages/demo.form-advanced.js',
         'resources/js/pages/demo.datatable-init.js',
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.js',
//        'node_modules/select2/dist/js/select2.min.js',

    ])

    <script>
        $(document).ready(function() {
            $('.select2').select2();

            // Load currencies for the selected client on page load
            var clientId = $('#client_id').val();
            if (clientId) {
                loadClientCurrencies(clientId);
            }

            // Function to load currencies based on client ID
            function loadClientCurrencies(clientId) {
                $.ajax({
                    url: '{{ route('clients.currencies') }}',
                    type: 'GET',
                    data: { client_id: clientId },
                    success: function(response) {
                        if (response.currencies) {
                            $('#currency_id').empty();
                            $.each(response.currencies, function(id, currency) {
                                $('#currency_id').append(new Option(currency, id));
                            });

                            // Select the existing currency in the account
                            $('#currency_id').val('{{ $account->currency_id }}').trigger('change');
                        }
                    }
                });
            }

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
                            text: 'Account updated successfully.',
                            icon: 'success',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000,
                            afterHidden: function () {
                                window.location.href = "{{ route('accounts.index') }}";
                            }
                        });
                    },
                    error: function(response) {
                        $.toast().reset('all');
                        $.toast({
                            heading: 'Error',
                            text: 'There was an error updating the account.',
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
