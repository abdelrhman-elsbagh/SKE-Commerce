@extends('front.layout')

@section('title',  ($config->name ?? "") . "- Profile" )

@section('content')
    <main class="page-main">
        <div class="uk-grid" data-uk-grid>
            <div class="uk-width-3-3@l">
                <div class="widjet --profile">
                    <div class="widjet__head">
                        <h3 class="uk-text-lead">Profile</h3>
                    </div>
                    <div class="widjet__body">
                        <div class="user-info">
                            <div class="user-info__avatar">
                                <img src="{{ asset('assets/img/profile.png') }}" alt="profile">
                            </div>
                            <div class="user-info__box">
                                <div class="user-info__title">{{ $businessClient->name }}</div>
                                <div class="user-info__text">{{ $businessClient->address }}, Member since {{ $businessClient->created_at->format('F Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="widjet --bio">
                    <div class="widjet__head">
                        <h3 class="uk-text-lead">Bio</h3>
                    </div>
                    <div class="widjet__body"><span>{{ $businessClient->bio ?? 'No bio available.' }}</span></div>
                </div>
                <div class="widjet --activity">
                    <div class="widjet__head">
                        <h3 class="uk-text-lead">Recent Activity</h3>
                    </div>
                    <div class="widjet__body">
                        @foreach($subscriptions as $subscription)
                            <div class="widjet-game" style="margin-bottom: 20px;">
                                <div class="widjet-game__media">
                                    @if($subscription->plan->getFirstMediaUrl('images'))
                                        <a href="{{ route('plan.show', ['id' => $subscription->plan->id]) }}">
                                            <img src="{{ $subscription->plan->getFirstMediaUrl('images') }}" alt="{{ $subscription->plan->name }}">
                                        </a>
                                    @endif
                                </div>
                                <div class="widjet-game__info">
                                    <a class="widjet-game__title" href="{{ route('plan.show', ['id' => $subscription->plan->id]) }}"> {{ $subscription->plan->name }}</a>
                                    <div class="widjet-game__record">Subscription ID: #{{ $subscription->id }}</div>
                                    <div class="widjet-game__record">Price: {{ $subscription->plan->price }} {{ $user->currency->currency ?? "USD" }}</div>
                                    <div class="widjet-game__last-played">Subscribed on {{ $subscription->start_date->format('d M, Y') }}</div>
                                    <div class="widjet-game__record">Ends on: {{ $subscription->end_date->format('d M, Y') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="widjet --purchase-requests">
                    <div class="widjet__head">
                        <h3 class="uk-text-lead">Purchase Requests</h3>
                    </div>
                    <div class="widjet__body">
                        @foreach($purchaseRequests as $request)
                            <div class="widjet-game" style="margin-bottom: 20px;">
                                <div class="widjet-game__media">
                                    @if($request->getFirstMediaUrl('images'))
                                        <a href="#">
                                            <img src="{{ $request->getFirstMediaUrl('images') }}" alt="Purchase Request Image">
                                        </a>
                                    @endif
                                </div>
                                <div class="widjet-game__info">
                                    <div class="widjet-game__title">Request ID: {{ $request->id }}</div>
                                    <div class="widjet-game__record">{{ $request->amount }} {{ $user->currency->currency ?? "USD" }}</div>
                                    <div class="widjet-game__last-played">Status: {{ ucfirst($request->status) }}</div>
                                    <div class="widjet-game__last-played">Requested on {{ $request->created_at->format('d M, Y') }}</div>
                                    <div class="widjet-game__description">{{ $request->notes }}</div>
                                </div>
                            </div>
                            <hr>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </main>
    @if(session('success'))
        @push('scripts')
            <script>
                toastr.success("{{ session('success') }}");
            </script>
        @endpush
    @endif
@endsection
