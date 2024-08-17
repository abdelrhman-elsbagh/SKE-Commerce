@extends('layouts.vertical', ['page_title' => 'Create Client'])

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
                    <h4 class="page-title">Create Client</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form id="create-client-form" action="{{ route('clients.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>
                            <div class="mb-3">
                                <label for="currency_ids" class="form-label">Currencies</label>
                                <select class="form-control select2" id="currency_ids" name="currency_ids[]" multiple="multiple" required>
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->id }}">{{ $currency->currency }}</option>
                                    @endforeach
                                </select>
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
            $('#currency_ids').select2();

            $('#create-client-form').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(response) {
                        $.toast().reset('all');
                        $.toast({
                            heading: 'Success',
                            text: 'Client created successfully.',
                            icon: 'success',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000,
                            afterHidden: function () {
                                window.location.href = "{{ route('clients.index') }}";
                            }
                        });

                        $('#create-client-form')[0].reset();
                    },
                    error: function(response) {
                        $.toast().reset('all');
                        $.toast({
                            heading: 'Error',
                            text: 'There was an error creating the client.',
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
