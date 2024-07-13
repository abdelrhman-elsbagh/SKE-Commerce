@extends('front.layout')

@section('title', 'Ske E-Commerce')

@section('content')
    <main class="page-main">
        <div class="uk-grid" data-uk-grid>
            <div class="uk-width-2-3@l">
                <div class="uk-grid uk-child-width-1-3@m uk-grid-small" data-uk-grid>
                    <div class="card-height">
                        <div class="uk-card uk-card-default uk-card-body" style="text-align: center;">
                            <h3 class="uk-card-title static-head">Wallet Balance</h3>
                            <p class="wallet-value">{{ number_format($wallet->balance, 2) }} {{ $user->currency->currency ?? "USD" }}</p>
                        </div>
                    </div>
                    <div class="card-height">
                        <div class="uk-card uk-card-default uk-card-body" style="text-align: center;">
                            <h3 class="uk-card-title static-head">Total Order Money</h3>
                            <p class="wallet-value">{{ number_format($totalOrderMoney, 2) }} {{ $user->currency->currency ?? "USD" }}</p>
                        </div>
                    </div>
                    <div class="card-height">
                        <div class="uk-card uk-card-default uk-card-body" style="text-align: center;">
                            <h3 class="uk-card-title static-head">Total Purchase Requests</h3>
                            <p class="wallet-value">{{ number_format($totalPurchaseRequestAmount, 2) }} {{ $user->currency->currency ?? "USD" }}</p>
                        </div>
                    </div>
                </div>

                <div class="widjet --purchase-requests" style="margin-top: 20px;">
                    <div class="widjet__head">
                        <h3 class="uk-text-lead">Recent Purchase Requests</h3>
                    </div>
                    <input type="text" id="search-purchase-requests" class="form-control uk-input" placeholder="Search purchase requests by ID or notes...">
                    <div class="widjet__body" style="padding: 0;">
                        <ul class="activities-list" id="purchase-requests-list" style="">
                            @foreach($purchaseRequests as $request)
                                <li class="activities-item" style="padding: 20px 30px" data-id="{{ $request->id }}" data-notes="{{ $request->notes }}">
                                    <div class="activities-item__logo">
                                        <img src="{{ $request->getFirstMediaUrl('purchase_documents') ?? asset('assets/img/default-image.jpg') }}" alt="image">
                                    </div>
                                    <div class="activities-item__info">
                                        <div class="activities-item__title">Request ID: #{{ $request->id }}</div>
                                        <div class="activities-item__title">Notes: {{ $request->notes }}</div>
                                        <div class="activities-item__date" style="font-weight: 900">Requested on: {{ $request->created_at->format('d M, Y - H:i') }}</div>

                                        <div class="activities-item__status">
                                            @if($request->status == 'canceled' || $request->status == 'refunded')
                                                <span class="badge bg-danger-subtle text-danger rounded-pill item__status">{{ ucfirst($request->status) }}</span>
                                            @elseif($request->status == 'approved')
                                                <span class="badge bg-success-subtle text-success rounded-pill item__status">{{ ucfirst($request->status) }}</span>
                                            @else
                                                <span class="badge bg-warning-subtle text-secondary rounded-pill item__status">{{ ucfirst($request->status) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="activities-item__price">{{ number_format($request->amount, 2) }} {{ $user->currency->currency ?? "USD" }}</div>
                                </li>
{{--                                <li style="width: 100%; height: 10px; background: #F5F5F5"></li>--}}
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="uk-width-1-3@l">

{{--                <div class="widjet --payment-method">--}}
{{--                    <div class="widjet__head">--}}
{{--                        <h3 class="uk-text-lead">Payment Method</h3>--}}
{{--                    </div>--}}
{{--                    <div class="widjet__body">--}}
{{--                        <div class="payment-card">--}}
{{--                            <div class="payment-card__head">--}}
{{--                                <div class="payment-card__chip">--}}
{{--                                    <img src="{{ asset('assets/img/payment-card-chip.svg') }}" alt="chip">--}}
{{--                                </div>--}}
{{--                                <div class="payment-card__logo">--}}
{{--                                    <img src="{{ asset('assets/img/payment-card-logo.svg') }}" alt="logo">--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="payment-card__number">Direct Payment</div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
                <div id="" style="">
                    <div id="gateway-card" class="widjet__body uk-card">
                        <h3 class="uk-text-lead">{{ $user->feeGroup->name ?? ""  }}</h3>
                        <div class="">
                            @if($feeGroup)
                                @if($feeGroup->getFirstMediaUrl('images'))
                                    <img src="{{ $feeGroup->getFirstMediaUrl('images') }}" alt="Fee Group Image" style="max-width: 150px;">
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <div class="widjet --activities">
                    <div class="widjet__head" style="margin-top: 20px">
                        <h3 class="uk-text-lead">Recent Orders</h3>
                    </div>
                    <input type="text" id="search-orders" class="form-control uk-input" placeholder="Search orders by ID or name...">
                    <div class="widjet__body">
                        <ul class="activities-list" id="orders-list" style="max-height: 400px; overflow: scroll;">
                            @foreach($orders as $order)
                                @foreach($order->subItems as $orderSubItem)
                                    @php
                                        $subItem = $orderSubItem->subItem;
                                        $item = $subItem->item;
                                    @endphp
                                    <li class="activities-item" data-id="{{ $order->id }}" data-name="{{ $item->name }}">
                                        <div class="activities-item__logo">
                                            <a href="{{ route('item.show', ['id' => $item->id]) }}">
                                                @if($item->getFirstMediaUrl('images'))
                                                    <img src="{{ $item->getFirstMediaUrl('images') }}" alt="{{ $item->name }}" style="height: 100%">
                                                @else
                                                    <img src="{{ asset('assets/img/default-image.jpg') }}" alt="Default Image">
                                                @endif
                                            </a>
                                        </div>
                                        <div class="activities-item__info">
                                            <a class="activities-item__title" href="{{ route('item.show', ['id' => $item->id]) }}">
                                                {{ $item->name }}
                                            </a>
                                            <div class="activities-item__date">Order ID: #{{ $order->id }}</div>
                                            <div class="activities-item__date">Price {{ $order->total ?? "0" }} {{ $user->currency->currency ?? "USD" }}</div>
                                            <div class="activities-item__date">Service ID: #{{ $orderSubItem->service_id ?? "" }}</div> <!-- Display the service_id -->
                                            <div class="activities-item__date">Amount: {{ $orderSubItem->subItem->amount ?? "" }}</div>
                                            <div class="activities-item__date">{{ $order->created_at->format('d M, Y - H:i') }}</div>
                                            <div class="activities-item__status">
                                                @if($order->status == 'canceled' || $order->status == 'refunded')
                                                    <span class="badge bg-danger-subtle text-danger rounded-pill item__status">{{ ucfirst($order->status) }}</span>
                                                @elseif($order->status == 'active')
                                                    <span class="badge bg-success-subtle text-success rounded-pill item__status">{{ ucfirst($order->status) }}</span>
                                                @else
                                                    <span class="badge bg-warning-subtle text-secondary rounded-pill item__status">{{ ucfirst($order->status) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="activities-item__price">{{ number_format($orderSubItem->price, 2) }} {{ $user->currency->currency ?? "USD" }}</div>
                                    </li>
                                @endforeach
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Equal height for cards
            function setEqualHeight() {
                var maxHeight = 0;
                $('.card-height .uk-card').each(function() {
                    var height = $(this).height();
                    if (height > maxHeight) {
                        maxHeight = height;
                    }
                });
                $('.card-height .uk-card').height(maxHeight);
                $('#gateway-card').height(maxHeight + 40);
            }

            setEqualHeight();
            $(window).resize(setEqualHeight);

            // Search functionality for orders
            $('#search-orders').on('keyup', function() {
                var query = $(this).val().toLowerCase();
                $('#orders-list .activities-item').filter(function() {
                    $(this).toggle(
                        $(this).data('id').toString().toLowerCase().includes(query) ||
                        $(this).data('name').toString().toLowerCase().includes(query)
                    );
                });
            });

            // Search functionality for purchase requests
            $('#search-purchase-requests').on('keyup', function() {
                var query = $(this).val().toLowerCase();
                $('#purchase-requests-list .activities-item').filter(function() {
                    $(this).toggle(
                        $(this).data('id').toString().toLowerCase().includes(query) ||
                        $(this).data('notes').toString().toLowerCase().includes(query)
                    );
                });
            });
        });
    </script>
@endsection
