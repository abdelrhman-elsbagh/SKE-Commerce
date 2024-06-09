@extends('layouts.vertical', ['page_title' => 'Purchase Request Details'])

@section('css')
    @vite([
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.css'
    ])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Purchase Request Details</h4>
                        <table class="table table-bordered">
                            <tr>
                                <th>ID</th>
                                <td>{{ $purchaseRequest->id }}</td>
                            </tr>
                            <tr>
                                <th>User</th>
                                <td>{{ $purchaseRequest->user->name }}</td>
                            </tr>
                            <tr>
                                <th>Notes</th>
                                <td>{{ $purchaseRequest->notes }}</td>
                            </tr>
                            <tr>
                                <th>Amount</th>
                                <td>{{ $purchaseRequest->amount }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>{{ ucfirst($purchaseRequest->status) }}</td>
                            </tr>
                            <tr>
                                <th>Document</th>
                                <td>
                                    @if($purchaseRequest->getFirstMediaUrl('purchase_documents'))
                                        <a href="{{ $purchaseRequest->getFirstMediaUrl('purchase_documents') }}" target="_blank">View Document</a>
                                    @endif
                                </td>
                            </tr>
                        </table>
                        <a href="{{ route('purchase-requests.index') }}" class="btn btn-primary">Back</a>
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
