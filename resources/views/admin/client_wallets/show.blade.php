@extends('layouts.vertical', ['page_title' => 'Business Client Wallet Details'])

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
                        <h4 class="header-title">Business Client Wallet Details</h4>
                        <table class="table table-bordered">
                            <tr>
                                <th>ID</th>
                                <td>{{ $wallet->id }}</td>
                            </tr>
                            <tr>
                                <th>Business Client Name</th>
                                <td>{{ $wallet->businessClient->name }}</td>
                            </tr>
                            <tr>
                                <th>Balance</th>
                                <td>{{ $wallet->balance }}</td>
                            </tr>
                        </table>
                        <a href="{{ route('business-client-wallets.index') }}" class="btn btn-primary">Back</a>
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
