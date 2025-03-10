@extends('layouts.vertical', ['page_title' => 'Dashboard'])

@section('css')
    @vite(['node_modules/daterangepicker/daterangepicker.css', 'node_modules/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css'])
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- Page title and date range picker -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Dashboard</h4>
                </div>
            </div>
        </div>

        <!-- Dashboard stats -->
        <div class="row row-cols-1 row-cols-xxl-5 row-cols-lg-3 row-cols-md-2">
            <!-- Add your card elements here -->
        </div> <!-- end row -->

        <!-- Currency stats -->
        <div class="row row-cols-1 row-cols-xxl-5 row-cols-lg-3 row-cols-md-2">
            <div class="col">
                <div class="card">
                    <div class="card-body statstic-card">
                        <div class="d-flex justify-content-between">
                            <div class="flex-grow-1 overflow-hidden">
                                <h4 class="text-muted fw-normal mt-0" title="Total Customers">Customers</h4>
                                <h2 class="mt-0" title="Number of Customers">{{ $customersCount }}</h2>
                                <h5 class=""> <span class="badge bg-success-subtle text-success rounded-pill">Active:</span>  {{ $activeUsersCount }}</h5>
                                <h5 class="mb-0"><span class="badge bg-danger-subtle text-danger rounded-pill">Inactive:</span> {{ $inactiveUsersCount }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card">
                    <div class="card-body statstic-card">
                        <div class="d-flex justify-content-between">
                            <div class="flex-grow-1 overflow-hidden">
                                <h4 class="text-muted fw-normal mt-0" title="Total Partners">Partners</h4>
                                <h2 class="mt-0" title="Number of Partners">{{ $partnersCount }}</h2>
                                <h5 class=""> <span class="badge bg-success-subtle text-success rounded-pill">Active:</span>  {{ $activePartnersCount }}</h5>
                                <h5 class="mb-0"><span class="badge bg-danger-subtle text-danger rounded-pill">Inactive:</span> {{ $inactivePartnersCount }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card">
                    <div class="card-body statstic-card">
                        <div class="d-flex justify-content-between">
                            <div class="flex-grow-1 overflow-hidden">
                                <h4 class="text-muted fw-normal mt-0" title="Total Orders">Orders</h4>
                                <h2 class="mt-0" title="Number of Orders">{{ $ordersCount }}</h2>
                                <h5 class=""><span class="badge bg-success-subtle text-success rounded-pill">Active:</span> {{ $activeOrdersCount }}</h5>
                                <h5 class=""><span class="badge bg-danger-subtle text-danger rounded-pill">Refunded:</span> {{ $refundedOrdersCount }}</h5>
                                <h5 class="mb-0"><span class="badge bg-warning-subtle text-warning rounded-pill">Pending:</span> {{ $pendingOrdersCount }}</h5>
                            </div>
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div>
            @foreach ($currencyData as $data)
                <div class="col">
                    <div class="card">
                        <div class="card-body pb-2 statstic-card">
                            <div class="d-flex justify-content-between">
                                <div class="flex-grow-1 overflow-hidden">
                                    <h4 class="text-muted fw-normal mt-0" title="Total Balance in {{ $data['currency'] }}">{{ $data['currency'] }}</h4>
                                    <h3 class="my-1">{{ number_format($data['total_balance'], 2) }} </h3>
                                    <p class="mb-0 text-muted text-truncate">
                                        @if ($data['percentage_change'] > 0)
                                            <span class="badge bg-success me-1"><i class="ri-arrow-up-line"></i> Up {{ number_format($data['percentage_change'], 2) }}%</span>
                                        @elseif ($data['percentage_change'] < 0)
                                            <span class="badge bg-danger me-1"><i class="ri-arrow-down-line"></i> Down {{ number_format($data['percentage_change'], 2) }}%</span>
                                        @else
                                            <span class="badge bg-info me-1"><i class="ri-arrow-right-line"></i> 0.00%</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div>
                                <p class="my-0">Orders: {{ number_format($data['total_orders'], 2) }}</p>
                                <p class="my-0">Fee: {{ number_format($data['revenue'], 2) }}</p>
                                <p class="my-0">Deposit: {{ number_format($data['approved_purchase_requests'], 2) }}</p>
                            </div>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col-->
            @endforeach
        </div> <!-- end row -->

        <!-- Additional rows for other stats like Revenue and Total Sales -->
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <h4 class="header-title">Top Selling Products</h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="min-height: 340px;">
                            <table class="table table-borderless table-hover table-nowrap table-centered m-0">
                                <thead class="border-top border-bottom bg-light-subtle border-light">
                                <tr>
                                    <th class="py-1">Item</th>
                                    <th class="py-1">Sub Item</th>
                                    <th class="py-1">Price (USD)</th>
                                    <th class="py-1">Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($topSellingSubItems as $subItem)
                                    <tr>
                                        <td>{{ $subItem->item->name ?? "" }}</td>
                                        <td>{{ $subItem->name ?? "" }}</td>
                                        <td>USD {{ number_format($subItem->price, 2) }}</td>

                                        <td>
                                            @if($subItem->item->status == 'canceled' || $subItem->item->status == 'refunded')
                                                <span class="badge bg-danger-subtle text-danger rounded-pill">
                                                {{ ucfirst($subItem->item->status) }}
                                                <i class="ri-close-circle-line ms-1 fs-14"></i>
                                            </span>
                                            @elseif($subItem->item->status == 'active')
                                                <span class="badge bg-success-subtle text-success rounded-pill">
                                                {{ ucfirst($subItem->item->status) }}
                                                <i class="bi bi-check2-circle ms-1 fs-14"></i>
                                            </span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-warning rounded-pill">
                                                {{ ucfirst($subItem->item->status) }}
                                                <i class="bi bi-clock-history ms-1 fs-14"></i>
                                            </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('items.index') }}" class="text-primary text-decoration-underline fw-bold btn mb-2">View All</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <h4 class="header-title">Top Selling External Items</h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="min-height: 340px;">
                            <table class="table table-borderless table-hover table-nowrap table-centered m-0" >
                                <thead class="border-top border-bottom bg-light-subtle border-light">
                                <tr>
                                    <th class="py-1">Item</th>
                                    <th class="py-1">Sub Item</th>
                                    <th class="py-1">Price (USD)</th>
                                    <th class="py-1">Type</th>
                                    <th class="py-1">Client / Store</th>
                                    <th class="py-1">Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($topSellingApiSubItems as $subItem)
                                    <tr>
                                        <td>{{ $subItem->item->name ?? "" }}</td>
                                        <td>{{ $subItem->name ?? "" }}</td>
                                        <td>USD {{ number_format($subItem->price, 2) }}</td>
                                        <td>API</td>
                                        <td>{{ $subItem->clientStore->domain ?? "" }}</td>
                                        <td>
                                            @if($subItem->item->status == 'canceled' || $subItem->item->status == 'refunded')
                                                <span class="badge bg-danger-subtle text-danger rounded-pill">
                                            {{ ucfirst($subItem->item->status) }}
                                            <i class="ri-close-circle-line ms-1 fs-14"></i>
                                        </span>
                                            @elseif($subItem->item->status == 'active')
                                                <span class="badge bg-success-subtle text-success rounded-pill">
                                            {{ ucfirst($subItem->item->status) }}
                                            <i class="bi bi-check2-circle ms-1 fs-14"></i>
                                        </span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-warning rounded-pill">
                                            {{ ucfirst($subItem->item->status) }}
                                            <i class="bi bi-clock-history ms-1 fs-14"></i>
                                        </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('items.index') }}" class="text-primary text-decoration-underline fw-bold btn mb-2">View All</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

        <!-- Recent Orders -->
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <h4 class="header-title">Revenue By Locations</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <div id="world-map-markers" class="mt-3 mb-3" style="height: 298px"></div>
                            </div>
                            <div class="col-lg-8">
                                <div class="table-responsive mt-4">
                                    <table class="table table-borderless table-hover table-nowrap table-centered m-0">
                                        <thead class="border-top border-bottom bg-light-subtle border-light">
                                        <tr>
                                            <th class="py-1">Country</th>
                                            <th>Currency</th>
                                            <th class="py-1">Total Revenue</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($revenueByLocations as $location)
                                            <tr>
                                                <td>{{ $location->address }}</td>
                                                <td>{{ $location->currency ?? "USD" }}</td>
                                                <td>{{ number_format($location->total_revenue, 2) }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end card-->
            </div> <!-- end col -->

            <div class="col-xl-6">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <h4 class="header-title">Recent Orders</h4>
                        <a href="{{ route('orders.export') }}" class="btn btn-sm btn-info">Export <i class="ri-download-line ms-1"></i></a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-borderless table-hover table-nowrap table-centered m-0">
                                <thead class="border-top border-bottom bg-light-subtle border-light">
                                <tr>
                                    <th class="py-1">Order ID</th>
                                    <th class="py-1">Item</th>
                                    <th class="py-1">User</th>
                                    <th class="py-1">Total</th>
                                    <th class="py-1">Status</th>
                                    <th class="py-1">Created At</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($recentOrders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->sub_item_name }}</td>
                                        <td>{{ $order->user->name }}</td>
                                        <td>{{ number_format($order->total, 2) }} {{ $order->currency->currency }}</td>

                                        <td>
                                            @if($order->status == 'canceled' || $order->status == 'refunded')
                                                <span class="badge bg-danger-subtle text-danger rounded-pill">
                                                {{ ucfirst($order->status) }}
                                                <i class="ri-close-circle-line ms-1 fs-14"></i>
                                            </span>
                                            @elseif($order->status == 'active')
                                                <span class="badge bg-success-subtle text-success rounded-pill">
                                                {{ ucfirst($order->status) }}
                                                <i class="bi bi-check2-circle ms-1 fs-14"></i>
                                            </span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-warning rounded-pill">
                                                {{ ucfirst($order->status) }}
                                                <i class="bi bi-clock-history ms-1 fs-14"></i>
                                            </span>
                                            @endif
                                        </td>
                                        <td>{{ $order->created_at->format('d M, Y - H:i') }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('orders.index') }}" class="text-primary text-decoration-underline fw-bold btn mb-2">View All</a>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div>
    <!-- container -->
@endsection

@section('script')
    @vite(['resources/js/pages/demo.dashboard.js'])
    <script>
        $(document).ready(function () {
            // Set all card-body elements to the height of the tallest one
            let maxHeight = 0;
            $('.statstic-card').each(function() {
                maxHeight = Math.max(maxHeight, $(this).outerHeight());
            });
            $('.statstic-card').css('height', maxHeight + 'px');
        });
    </script>
@endsection
