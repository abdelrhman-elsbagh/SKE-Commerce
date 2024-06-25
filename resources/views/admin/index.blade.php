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
                    <div class="page-title-right">
                        <form class="d-flex">
                            <div class="input-group">
                                <input type="text" class="form-control shadow border-0" id="dash-daterange">
                                <span class="input-group-text bg-primary border-primary text-white">
                                    <i class="ri-calendar-todo-fill fs-13"></i>
                                </span>
                            </div>
                            <a href="javascript: void(0);" class="btn btn-primary ms-2">
                                <i class="ri-refresh-line"></i>
                            </a>
                        </form>
                    </div>
                    <h4 class="page-title">Dashboard</h4>
                </div>
            </div>
        </div>

        <!-- Dashboard stats -->
        <div class="row row-cols-1 row-cols-xxl-5 row-cols-lg-3 row-cols-md-2">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="flex-grow-1 overflow-hidden">
                                <h5 class="text-muted fw-normal mt-0" title="Number of Customers">Customers</h5>
                                <h3 class="my-3">{{ $customersCount }}</h3>
                                <p class="mb-0 text-muted text-truncate">
                                    <span class="badge bg-success me-1"><i class="ri-arrow-up-line"></i> 2,541</span>
                                    <span>Since last month</span>
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <div id="widget-customers" class="apex-charts" data-colors="#47ad77,#e3e9ee"></div>
                            </div>
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->

            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="flex-grow-1 overflow-hidden">
                                <h5 class="text-muted fw-normal mt-0" title="Number of Orders">Orders</h5>
                                <h3 class="my-3">{{ $ordersCount }}</h3>
                                <p class="mb-0 text-muted text-truncate">
                                    <span class="badge bg-danger me-1"><i class="ri-arrow-down-line"></i> 1.08%</span>
                                    <span>Since last month</span>
                                </p>
                            </div>
                            <div id="widget-orders" class="apex-charts" data-colors="#3e60d5,#e3e9ee"></div>
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->

            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="flex-grow-1 overflow-hidden">
                                <h5 class="text-muted fw-normal mt-0" title="Average Revenue">Revenue</h5>
                                <h3 class="my-3">${{ number_format($revenue, 2) }}</h3>
                                <p class="mb-0 text-muted text-truncate">
                                    <span class="badge bg-danger me-1"><i class="ri-arrow-down-line"></i> 7.00%</span>
                                    <span>Since last month</span>
                                </p>
                            </div>
                            <div id="widget-revenue" class="apex-charts" data-colors="#16a7e9,#e3e9ee"></div>
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->

            <div class="col col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="flex-grow-1 overflow-hidden">
                                <h5 class="text-muted fw-normal mt-0" title="Growth">Growth</h5>
                                <h3 class="my-3">+ {{ $growth }}%</h3>
                                <p class="mb-0 text-muted text-truncate">
                                    <span class="badge bg-success me-1"><i class="ri-arrow-up-line"></i> 4.87%</span>
                                    <span>Since last month</span>
                                </p>
                            </div>
                            <div id="widget-growth" class="apex-charts" data-colors="#ffc35a,#e3e9ee"></div>
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->

            <div class="col col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="flex-grow-1 overflow-hidden">
                                <h5 class="text-muted fw-normal mt-0" title="Conversation Rate">Conversation</h5>
                                <h3 class="my-3">{{ $conversationRate }}%</h3>
                                <p class="mb-0 text-muted text-truncate">
                                    <span class="badge bg-success me-1"><i class="ri-arrow-up-line"></i> 3.07%</span>
                                    <span>Since last month</span>
                                </p>
                            </div>
                            <div id="widget-conversation" class="apex-charts" data-colors="#f15776,#e3e9ee"></div>
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div> <!-- end row -->

        <!-- Additional rows for other stats like Revenue and Total Sales -->
        <div class="row">
            <div class="col-xl-5">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <h4 class="header-title">Top Selling Products</h4>
                        <a href="javascript:void(0);" class="btn btn-sm btn-info">Export <i class="ri-download-line ms-1"></i></a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-borderless table-hover table-nowrap table-centered m-0">
                                <thead class="border-top border-bottom bg-light-subtle border-light">
                                <tr>
                                    <th class="py-1">Product</th>
                                    <th class="py-1">Price</th>
                                    <th class="py-1">Orders</th>
                                    <th class="py-1">Avl. Quantity</th>
                                    <th class="py-1">Seller</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($topSellingProducts as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>${{ number_format($product->price, 2) }}</td>
                                        <td>{{ $product->order_items_sum_quantity }}</td>
                                        <td>{{ $product->available_quantity }}</td>
                                        <td>{{ $product->seller->name ?? 'N/A' }}</td> <!-- Adjust as per your relationships -->
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <a href="#!" class="text-primary text-decoration-underline fw-bold btn mb-2">View All</a>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->

            <div class="col-xl-7">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <h4 class="header-title">Revenue By Locations</h4>
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-more-2-fill"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Sales Report</a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Profit</a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Action</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-8">
                                <div id="world-map-markers" class="mt-3 mb-3" style="height: 298px">
                                </div>
                            </div>
                            <div class="col-lg-4" >
                                <div id="country-chart" class="apex-charts" data-colors="#47ad77"></div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>
        <!-- end row -->

    </div>
    <!-- container -->
@endsection

@section('script')
    @vite(['resources/js/pages/demo.dashboard.js'])
@endsection
