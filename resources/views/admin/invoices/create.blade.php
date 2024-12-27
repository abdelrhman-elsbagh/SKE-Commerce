@extends('layouts.vertical', ['page_title' => 'Create Invoice'])

@section('css')
    <!-- Include Select2 CSS from CDN or already bundled -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Create Invoice</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form id="create-invoice-form" action="{{ route('invoices.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="supplier_name" class="form-label">Supplier Name</label>
                                <input type="text" class="form-control" id="supplier_name" name="supplier_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="issued_in" class="form-label">Issued Date</label>
                                <input type="date" class="form-control" id="issued_in" name="issued_in" required>
                            </div>
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="number" class="form-control" id="amount" name="amount" step="0.01" required>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                            </div>
                            <div class="mb-3">
                                <label for="sub_item" class="form-label">Sub Item</label>
                                <select class="form-control" id="sub_item" name="sub_item_id" required>
                                    <option value="">Select Sub Item</option>
                                    @foreach($subItems as $subItem)
                                        <option value="{{ $subItem->id }}">{{ $subItem->name }}</option>
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
    <!-- Include Select2 JS from CDN or already bundled -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2 for the sub-item field
            $('#sub_item').select2({
                theme: 'default',
                placeholder: 'Search and select sub-item',
                allowClear: true
            });

            $('#create-invoice-form').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(response) {
                        $.toast({
                            heading: 'Success',
                            text: 'Invoice created successfully.',
                            icon: 'success',
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000,
                            afterHidden: function () {
                                window.location.href = "{{ route('invoices.index') }}";
                            }
                        });
                        $('#create-invoice-form')[0].reset();
                    },
                    error: function(response) {
                        $.toast({
                            heading: 'Error',
                            text: 'There was an error creating the invoice.',
                            icon: 'error',
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
