@extends('front.layout')

@section('title', 'Ske E-Commerce')

@section('content')
    <main class="page-main">
        <div class="uk-grid" data-uk-grid>
            <div class="uk-width-2-3@l">
                <div class="widjet --wallet">
                    <div class="widjet__head">
                        <h3 class="uk-text-lead">Wallet</h3>
                    </div>
                    <div class="widjet__body">
                        <div class="wallet-info">
                            <div class="wallet-value">{{ number_format($wallet->balance, 2) }} USD</div>
                            <div class="wallet-label">Available</div>
                        </div>
                    </div>
                </div>
                <div class="widjet --purchase-requests">
                    <div class="widjet__head">
                        <h3 class="uk-text-lead">Recent Purchase Requests</h3>
                    </div>
                    <div class="widjet__body" style="padding: 0;">
                        <ul class="activities-list" style="max-height: 600px;overflow: scroll;">
                            @foreach($purchaseRequests as $request)
                                <li class="activities-item" style="padding: 20px 30px">
                                    <div class="activities-item__logo">
                                        <img src="{{ $request->getFirstMediaUrl('purchase_documents') ?? asset('assets/img/default-image.jpg') }}" alt="image">
                                    </div>
                                    <div class="activities-item__info">
                                        <div class="activities-item__title">Request ID: #{{ $request->id }}</div>
                                        <div class="activities-item__title">Notes: {{ $request->notes }}</div>
                                        <div class="activities-item__date" style="font-weight: 900">Requested on: {{ $request->created_at->format('d M, Y') }}</div>
                                        <div class="activities-item__status" style="color: {{ $request->status == 'approved' ? '#27ae60' : ($request->status == 'rejected' ? 'red' : 'black') }};">
                                            Status: {{ ucfirst($request->status) }}
                                        </div>
                                    </div>
                                    <div class="activities-item__price">{{ number_format($request->amount, 2) }} USD</div>
                                </li>
                                <li style="width: 100%; height: 10px; background: #F5F5F5"></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="uk-width-1-3@l">
                <div class="widjet --payment-method">
                    <div class="widjet__head">
                        <h3 class="uk-text-lead">Payment Method</h3>
                    </div>
                    <div class="widjet__body">
                        <div class="payment-card">
                            <div class="payment-card__head">
                                <div class="payment-card__chip">
                                    <img src="{{ asset('assets/img/payment-card-chip.svg') }}" alt="chip">
                                </div>
                                <div class="payment-card__logo">
                                    <img src="{{ asset('assets/img/payment-card-logo.svg') }}" alt="logo">
                                </div>
                            </div>
                            <div class="payment-card__number">Direct Payment</div>
                        </div>
                    </div>
                </div>
                <div class="widjet --activities">
                    <div class="widjet__head">
                        <h3 class="uk-text-lead">Recent Orders</h3>
                    </div>
                    <input type="text" id="search-orders" class="form-control uk-input"
                           placeholder="Search orders by ID or name...">
                    <div class="widjet__body">
                        <ul class="activities-list" id="orders-list" style="max-height: 400px;overflow: scroll;">
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
                                            <div class="activities-item__date">{{ $order->created_at->format('d M, Y') }}</div>
                                            <div class="activities-item__status">{{ $order->status }}</div>
                                        </div>
                                        <div class="activities-item__price">{{ number_format($orderSubItem->price, 2) }} USD</div>
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
            $('#search-orders').on('keyup', function() {
                var query = $(this).val().toLowerCase();
                $('#orders-list .activities-item').filter(function() {
                    $(this).toggle(
                        $(this).data('id').toString().toLowerCase().includes(query) ||
                        $(this).data('name').toString().toLowerCase().includes(query)
                    );
                });
            });
        });
    </script>
@endsection
