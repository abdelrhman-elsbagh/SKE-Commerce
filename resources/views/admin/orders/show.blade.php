@extends('layouts.vertical', ['page_title' => 'Order Details'])

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
                    <h4 class="page-title">Order Details</h4>
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
                                <td>{{ $order->id }}</td>
                            </tr>
                            <tr>
                                <th>User</th>
                                <td>{{ $order->user->name }}</td>
                            </tr>
                            <tr>
                                <th>Order Type</th>
                                <td>{{ $order->order_type }}</td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $order->created_at }}</td>
                            </tr>
                            <tr>
                                <th>Updated At</th>
                                <td>{{ $order->updated_at }}</td>
                            </tr>
                            <tr>
                                <th>Total</th>
                                <td>{{ $order->total }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>{{ ucfirst($order->status) }}</td>
                            </tr>
                        </table>

                        <h5>Sub Items</h5>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Price</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($order->subItems as $orderSubItem)
                                <tr>
                                    <td>{{ $orderSubItem->subItem->id }}</td>
                                    <td>{{ $orderSubItem->subItem->name }}</td>
                                    <td>{{ $orderSubItem->price }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <a href="{{ route('orders.index') }}" class="btn btn-primary">Back to Orders</a>
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
