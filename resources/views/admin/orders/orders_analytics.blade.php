@extends('layouts.vertical', ['page_title' => 'Order Analytics'])

@section('css')
    @vite([
        'node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css',
        'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css',
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.css'
    ])
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Order Statistics -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Order Statistics</h4>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Statistic</th>
                                <th>Value</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Total Orders</td>
                                <td>{{ $totalOrdersCount }}</td>
                            </tr>
                            <tr>
                                <td>Total Revenue</td>
                                <td>${{ number_format($totalOrdersRevenue, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Manual Orders</td>
                                <td>{{ $manualOrdersCount }}</td>
                            </tr>
                            <tr>
                                <td>Manual Orders Revenue</td>
                                <td>${{ number_format($manualOrdersRevenue, 2) }}</td>
                            </tr>
                            <tr>
                                <td>API Orders</td>
                                <td>{{ $apiOrdersCount }}</td>
                            </tr>
                            <tr>
                                <td>API Orders Revenue</td>
                                <td>${{ number_format($apiOrdersRevenue, 2) }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Grouped by SubItem -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Orders Analytics</h4>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>SubItem ID</th>
                                <th>Name</th>
                                <th>Order Count</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($ordersGroupedBySubItem as $subItem)
                                <tr>
                                    <td>{{ $subItem->sub_item_id }}</td>
                                    <td>{{ $subItem->name }}</td>
                                    <td>{{ $subItem->order_count }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <!-- SubItems Breakdown -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">SubItems Breakdown</h4>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Type</th>
                                <th>Count</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($subItemsCountByExternalId as $subItem)
                                <tr>
                                    <td>{{ ucfirst($subItem->external_type) }}</td>
                                    <td>{{ $subItem->count }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Manual SubItems -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Top 3 Manual Items</h4>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($topManualOrders as $subItem)
                                <tr>
                                    <td>{{ $subItem->name }}</td>
                                    <td>${{ number_format($subItem->amount, 2) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top API SubItems -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Top 3 API Items</h4>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($topApiOrders as $subItem)
                                <tr>
                                    <td>{{ $subItem->name }}</td>
                                    <td>${{ number_format($subItem->amount, 2) }}</td>
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
@endsection
