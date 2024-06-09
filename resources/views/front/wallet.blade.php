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
                    <div class="widjet__body">
                        <ul class="activities-list">
                            @foreach($purchaseRequests as $request)
                                <li class="activities-item">
                                    <div class="activities-item__logo">
                                        <img src="{{ $request->getFirstMediaUrl('images') ?? asset('assets/img/default-image.jpg') }}" alt="image">
                                    </div>
                                    <div class="activities-item__info">
                                        <div class="activities-item__title">{{ $request->notes }}</div>
                                        <div class="activities-item__date">{{ $request->created_at->format('d M, Y') }}</div>
                                    </div>
                                    <div class="activities-item__price">${{ number_format($request->amount, 2) }}</div>
                                </li>
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
                    <div class="widjet__body">
                        <ul class="activities-list">
                            @foreach($orders as $order)
                                @foreach($order->subItems as $orderSubItem)
                                    @php
                                        $subItem = $orderSubItem->subItem;
                                        $item = $subItem->item;
                                    @endphp
                                    <li class="activities-item">
                                        <div class="activities-item__logo">
                                            <a href="{{ route('item.show', ['id' => $item->id]) }}">
                                                @if($item->getFirstMediaUrl('images'))
                                                    <img src="{{ $item->getFirstMediaUrl('images') }}" alt="{{ $item->name }}">
                                                @else
                                                    <img src="{{ asset('assets/img/default-image.jpg') }}" alt="Default Image">
                                                @endif
                                            </a>
                                        </div>
                                        <div class="activities-item__info">
                                            <a class="activities-item__title" href="{{ route('item.show', ['id' => $item->id]) }}">
                                                {{ $item->name }}
                                            </a>
                                            <div class="activities-item__date">{{ $order->created_at->format('d M, Y') }}</div>
                                        </div>
                                        <div class="activities-item__price">${{ number_format($orderSubItem->price, 2) }}</div>
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
