@extends('layouts.vertical', ['page_title' => 'Tag Details'])

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Invoice Details</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <p id="name">{{ $invoice->subItem->name ?? "" }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Supplier Name</label>
                            <p id="name">{{ $invoice->supplier_name ?? "" }}</p>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Issued In</label>
                            <p id="name">{{ $invoice->issued_in ?? "" }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Amount</label>
                            <p id="name">{{ $invoice->amount ?? 0 }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Price</label>
                            <p id="name">{{ $invoice->price ?? 0 }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Notes</label>
                            <p id="name">{{ $invoice->notes ?? "" }}</p>
                        </div>

                        </div>
                    </div>
                <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Back</a>
            </div>
            </div>
        </div>
    </div>
@endsection
