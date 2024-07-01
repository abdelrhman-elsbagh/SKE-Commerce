@extends('layouts.vertical', ['page_title' => 'Show Business Purchase Request'])

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
                    <h4 class="page-title">Show Business Purchase Request</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>ID</th>
                                <td>{{ $purchaseRequest->id }}</td>
                            </tr>
                            <tr>
                                <th>Business Client</th>
                                <td>{{ $purchaseRequest->businessClient->name }}</td>
                            </tr>
                            <tr>
                                <th>Amount</th>
                                <td>{{ $purchaseRequest->amount }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td style="color: @if($purchaseRequest->status == 'canceled' || $purchaseRequest->status == 'rejected') #F00 @elseif($purchaseRequest->status == 'approved') #1abc9c @endif;">
                                    {{ ucfirst($purchaseRequest->status) }}
                                </td>
                            </tr>
                            <tr>
                                <th>Notes</th>
                                <td>{{ $purchaseRequest->notes }}</td>
                            </tr>
                            <tr>
                                <th>Document</th>
                                <td>
                                    @if($purchaseRequest->getFirstMediaUrl('business_purchase_documents'))
                                        <img src="{{ $purchaseRequest->getFirstMediaUrl('business_purchase_documents') }}" alt="Document" style="max-width: 200px;">
                                    @endif
                                </td>
                            </tr>
                        </table>
                        <a href="{{ route('business-purchase-requests.index') }}" class="btn btn-primary">Back</a>
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
