@extends('layouts.vertical', ['page_title' => 'Create Diamond Rate'])

@section('css')
    @vite([
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.css'
    ])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Create Diamond Rate</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form id="create-diamond-rate-form" action="{{ route('diamond_rates.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="diamonds" class="form-label">Diamonds</label>
                                <input type="number" class="form-control" id="diamonds" name="diamonds" required>
                            </div>
                            <div class="mb-3">
                                <label for="usd" class="form-label">USD</label>
                                <input type="number" step="0.01" class="form-control" id="usd" name="usd" required>
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
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.js'
    ])
@endsection

@section('admin-script')
    <script>
        $(document).ready(function() {
            $('#create-diamond-rate-form').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(response) {
                        $.toast({
                            heading: 'Success',
                            text: 'Diamond rate created successfully.',
                            icon: 'success',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000
                        });

                        // Optionally, reset the form fields
                        $('#create-diamond-rate-form')[0].reset();
                    },
                    error: function(response) {
                        $.toast({
                            heading: 'Error',
                            text: 'There was an error creating the diamond rate.',
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
