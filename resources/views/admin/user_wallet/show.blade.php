@extends('layouts.vertical', ['page_title' => 'User Wallet Details'])

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
                        <h4 class="header-title">User Wallet Details</h4>
                        <table class="table table-bordered">
                            <tr>
                                <th>ID</th>
                                <td>{{ $wallet->id }}</td>
                            </tr>
                            <tr>
                                <th>User ID</th>
                                <td>{{ $wallet->user_id }}</td>
                            </tr>
                            <tr>
                                <th>Balance</th>
                                <td>{{ $wallet->balance }}</td>
                            </tr>
                            <tr>
                                <th>Currency</th>
                                <td>{{ $wallet->user->currency->currency ?? 'USD' }}</td>
                            </tr>
                        </table>
                        <a href="{{ route('user-wallets.index') }}" class="btn btn-primary">Back</a>
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
