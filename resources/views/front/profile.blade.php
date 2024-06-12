@extends('front.layout')

@section('title', 'Ske E-Commerce')

@section('content')
    <main class="page-main" style="width: 100%;">
        <div class="uk-grid" data-uk-grid>
            <div class="uk-width-2-3@l">
                <div class="widjet --profile">
                    <div class="widjet__head">
                        <h3 class="uk-text-lead">Profile</h3>
                    </div>
                    <div class="widjet__body">
                        <div class="user-info">
                            <div class="user-info__avatar">
                                @if($user->getFirstMediaUrl('avatars'))
                                    <img src="{{ $user->getFirstMediaUrl('avatars') }}" alt="profile">
                                @else
                                    <img src="{{ asset('assets/img/profile.png') }}" alt="profile">
                                @endif
                            </div>
                            <div class="user-info__box">
                                <div class="user-info__title">{{ $user->name }}</div>
                                <div class="user-info__text">{{ $user->address }}, Member since {{ $user->created_at->format('F Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="widjet --bio">
                    <div class="widjet__head">
                        <h3 class="uk-text-lead">Bio</h3>
                    </div>
                    <div class="widjet__body"><span>{{ $user->bio ?? 'No bio available.' }}</span></div>
                </div>
                <div class="widjet --activity">
                    <div class="widjet__head">
                        <h3 class="uk-text-lead">Recent Activity</h3>
                    </div>
                    <div class="widjet__body">
                        @foreach($orders as $order)
                            @foreach($order->subItems as $subItem)
                                <div class="widjet-game" style="margin-bottom: 20px;">
                                    <div class="widjet-game__media">
                                        @if($subItem->subItem->getFirstMediaUrl('images'))
                                            <a href="{{ route('item.show', ['id' => $subItem->subItem->item->id]) }}">
                                                <img src="{{ $subItem->subItem->getFirstMediaUrl('images') }}" alt="{{ $subItem->subItem->name }}">
                                            </a>
                                        @endif
                                    </div>
                                    <div class="widjet-game__info">
                                        <a class="widjet-game__title" href="{{ route('item.show', ['id' => $subItem->subItem->item->id]) }}"> {{ $subItem->subItem->name }}</a>
                                        <div class="widjet-game__record">{{ $subItem->price }} USD</div>
                                        <div class="widjet-game__last-played">Purchased on {{ $order->created_at->format('d M, Y') }}</div>
                                    </div>
                                </div>
                            @endforeach
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
                                    <div class="widjet-game__record">{{ $request->amount }} USD</div>
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
