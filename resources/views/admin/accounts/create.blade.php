@extends('layouts.vertical', ['page_title' => 'Create Account'])

@section('css')
    @vite([
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.css',
        'node_modules/select2/dist/css/select2.min.css'
    ])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Create Account</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
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
                                <label for="client_id" class="form-label">Client</label>
                                <select class="form-control select2" id="client_id" name="client_id" required>
                                    <option value="" selected disabled>Select Client</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->id }} | {{ $client->name ?? "" }} | {{ $client->phone ?? ""  }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="currency_id" class="form-label">Currency</label>
                                <select class="form-control select2" id="currency_id" name="currency_id" required>
                                    <option value="" selected disabled>Select Currency</option>
                                    <!-- Currencies will be loaded here based on client selection -->
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Notes</label>
                                <textarea class="form-control" id="notes" name="notes"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
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
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.js',
    ])

    <script>
        $(document).ready(function() {
            $('.select2').select2();

            // Handle client selection change
            $('#client_id').on('change', function() {
                var clientId = $(this).val();

                // Clear existing options in the currency dropdown
                $('#currency_id').empty().append('<option value="" selected disabled>Select Currency</option>');

                if (clientId) {
                    $.ajax({
                        url: '{{ route('clients.currencies') }}',
                        type: 'GET',
                        data: { client_id: clientId },
                        success: function(response) {
                            if (response.currencies) {
                                $.each(response.currencies, function(id, currency) {
                                    $('#currency_id').append(new Option(currency, id));
                                });
                            }
                        }
                    });
                }
            });

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
                            text: 'Account created successfully.',
                            icon: 'success',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000,
                            afterHidden: function () {
                                window.location.href = "{{ route('accounts.index') }}";
                            }
                        });

                        $('#create-account-form')[0].reset();
                        $('#currency_id').empty().append('<option value="" selected disabled>Select Currency</option>');
                    },
                    error: function(response) {
                        $.toast().reset('all');
                        $.toast({
                            heading: 'Error',
                            text: 'There was an error creating the account.',
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
