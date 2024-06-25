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
                                        <img src="{{ $request->getFirstMediaUrl('purchase_documents') ?? asset('assets/img/default-image.jpg') }}" alt="image">
                                    </div>
                                    <div class="activities-item__info">
                                        <div class="activities-item__title">Request ID: #{{ $request->id }}</div>
                                        <div class="activities-item__title">Notes: {{ $request->notes }}</div>
                                        <div class="activities-item__date" style="font-weight: 900">Requested on: {{ $request->created_at->format('d M, Y') }}</div>
                                        <div class="activities-item__status">Status: {{ ucfirst($request->status) }}</div>
                                    </div>
                                    <div class="activities-item__price">{{ number_format($request->amount, 2) }} USD</div>
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
                        <h3 class="uk-text-lead">Recent Subscriptions</h3>
                    </div>
                    <div class="widjet__body">
                        <ul class="activities-list">
                            @foreach($subscriptions as $subscription)
                                <li class="activities-item">
                                    <div class="activities-item__logo">
                                        <a href="{{ route('plan.show', ['id' => $subscription->plan->id]) }}">
                                            @if($subscription->plan->getFirstMediaUrl('images'))
                                                <img src="{{ $subscription->plan->getFirstMediaUrl('images') }}" alt="{{ $subscription->plan->name }}" style="height: 100%">
                                            @else
                                                <img src="{{ asset('assets/img/default-image.jpg') }}" alt="Default Image">
                                            @endif
                                        </a>
                                    </div>
                                    <div class="activities-item__info">
                                        <a class="activities-item__title" href="{{ route('plan.show', ['id' => $subscription->plan->id]) }}">
                                            {{ $subscription->plan->name }}
                                        </a>
                                        <div class="activities-item__date">Subscription ID: #{{ $subscription->id }}</div>
                                        <div class="activities-item__date">Start Date: {{ $subscription->start_date->format('d M, Y') }}</div>
                                        <div class="activities-item__date">End Date: {{ $subscription->end_date->format('d M, Y') }}</div>
                                    </div>
                                    <div class="activities-item__price">{{ number_format($subscription->plan->price, 2) }} USD</div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
