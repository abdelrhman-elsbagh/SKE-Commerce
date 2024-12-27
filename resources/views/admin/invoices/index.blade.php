@extends('layouts.vertical', ['page_title' => 'Invoices'])

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
                    <h4 class="page-title">Invoices</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Invoices</h4>
                        <table id="basic-datatable" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Supplier Name</th>
                                <th>Issued In</th>
                                <th>Amount</th>
                                <th>Price</th>
                                <th>Notes</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($invoices as $invoice)
                                <tr id="invoice-{{ $invoice->id }}">
                                    <td>{{ $invoice->id }}</td>
                                    <td>{{ $invoice->subItem->name ?? "" }}</td>
                                    <td>{{ $invoice->supplier_name ?? "" }}</td>
                                    <td>{{ $invoice->issued_in ?? "" }}</td>
                                    <td>{{ $invoice->amount ?? 0 }}</td>
                                    <td>{{ $invoice->price ?? 0 }}</td>
                                    <td>{{ $invoice->notes ?? "" }}</td>
                                    <td>
                                        <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-info"><i class=" ri-eye-line"></i></a>
                                        <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-warning"><i class="ri-edit-box-fill"></i></a>
                                        <button type="button" class="btn btn-danger btn-delete" data-id="{{ $invoice->id }}"><i class="ri-delete-bin-5-line"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @vite([
        'resources/js/pages/demo.datatable-init.js',
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.js'
    ])
    <script>
        $(document).ready(function() {
            $('.btn-delete').on('click', function() {
                const id = $(this).data('id');
                if (confirm('Are you sure you want to delete this invoice?')) {
                    $.ajax({
                        url: '{{ route('invoices.index') }}/' + id,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                $(`#invoice-${id}`).remove();
                                $.toast({
                                    heading: 'Success',
                                    text: response.message,
                                    icon: 'success',
                                    loaderBg: '#f96868',
                                    position: 'top-right',
                                    hideAfter: 3000
                                });
                            } else {
                                $.toast({
                                    heading: 'Error',
                                    text: response.message,
                                    icon: 'error',
                                    loaderBg: '#f96868',
                                    position: 'top-right',
                                    hideAfter: 3000
                                });
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
