@extends('front.layout')

@section('title', ($config->name ?? "") . "- Wallet" )

@section('content')
    <main class="page-main">
        <div class="uk-grid" data-uk-grid>
            <div class="uk-width-2-3@l">
                <div class="uk-grid uk-child-width-1-3@m uk-grid-small wallet-container" data-uk-grid>
                    <div class="card-height">
                        <div class="uk-card uk-card-default uk-card-body wallet-card" style="text-align: center;">
                            <h3 class="uk-card-title static-head wallet-card-head">@lang('messages.wallet_balance')</h3>
                            <p class="wallet-value">{{ number_format($wallet->balance, 2) }} {{ $user->currency->currency ?? "USD" }}</p>
                        </div>
                    </div>
                    <div class="card-height">
                        <div class="uk-card uk-card-default uk-card-body wallet-card" style="text-align: center;">
                            <h3 class="uk-card-title static-head wallet-card-head">@lang('messages.total_orders')</h3>
                            <p class="wallet-value">{{ number_format($totalOrderMoney, 2) }} {{ $user->currency->currency ?? "USD" }}</p>
                        </div>
                    </div>
                    <div class="card-height">
                        <div class="uk-card uk-card-default uk-card-body wallet-card" style="">
                            <h3 class="uk-card-title static-head wallet-card-head">@lang('messages.total_deposit')</h3>
                            <p class="wallet-value">{{ number_format($totalPurchaseRequestAmount, 2) }} {{ $user->currency->currency ?? "USD" }}</p>
                        </div>
                    </div>
                </div>
                <div class="widjet --purchase-requests" style="margin-top: 20px;">
                    <div class="widjet__head">
                        <h3 class="uk-text-lead">@lang('messages.recent_purchase_requests')</h3>
                    </div>
                    <input type="text" id="search-purchase-requests" class="form-control uk-input" placeholder="@lang('messages.search_purchase_requests_placeholder')">
                    <div class="d-flex justify-content-around my-3" style="margin-bottom: 10px">
                        <button class="btn btn-outline-primary filter-pr-btn wallet-filter-all" data-status="all">@lang('messages.all')</button>
                        <button class="btn btn-outline-primary filter-pr-btn" data-status="approved">
                            @lang('messages.approved')
                            <i class="bi bi-check2-circle ms-1 fs-16 icon-search"></i>
                        </button>
                        <button class="btn btn-outline-primary filter-pr-btn" data-status="rejected">
                            @lang('messages.rejected')
                            <i class="bi bi-x-circle fs-16 icon-search"></i>
                        </button>
                    </div>
                    <div class="widjet__body" style="padding: 0;">
                        <ul class="activities-list" id="purchase-requests-list" style="">
                            @foreach($purchaseRequests as $request)
                                <li class="activities-item" style="padding: 20px 30px" data-id="{{ $request->id }}" data-notes="{{ $request->notes }}" data-status="{{ $request->status }}">
                                    <div class="activities-item__logo">
                                        <img src="{{ $request->paymentMethod->getFirstMediaUrl('payment_method_images') ?? asset('assets/img/default-image.jpg') }}" alt="image">
                                    </div>
                                    <div class="activities-item__info">
                                        <div class="activities-item__title">@lang('messages.wallet_activity.request_id', ['id' => $request->id])</div>
                                        <div class="activities-item__title">@lang('messages.wallet_activity.applied_via', ['gateway' => $request->paymentMethod->gateway ?? ''])</div>
                                    @if($request->notes)
                                            <div class="activities-item__title">
                                                @lang('messages.wallet_activity.notes', ['notes' => $request->notes])
                                            </div>

                                        @endif
                                        <div class="activities-item__date" style="font-weight: 900">
                                            @lang('messages.wallet_activity.requested_on', ['date' => $request->created_at->format('d M, Y - H:i')])
                                        </div>
                                        @if($request->created_at != $request->updated_at)
                                            <div class="activities-item__date" style="font-weight: 900">
                                                @lang('messages.wallet_activity.updated_on', ['date' => $request->updated_at->format('d M, Y - H:i')])
                                            </div>
                                        @endif

                                        <div class="activities-item__status">
                                            @if($request->status == 'rejected')
                                                <span class="badge bg-danger-subtle text-danger rounded-pill item__status">
                                                    @lang('messages.wallet_activity.status.rejected')
                                                </span>
                                            @elseif($request->status == 'approved')
                                                <span class="badge bg-success-subtle text-success rounded-pill item__status">
                                                    @lang('messages.wallet_activity.status.approved')
                                                </span>
                                            @else
                                                <span class="badge bg-warning-subtle text-secondary rounded-pill item__status">
                                                    @lang('messages.wallet_activity.status.pending')
                                                </span>
                                            @endif
                                        </div>

                                    </div>
                                    <div class="activities-item__price">{{ number_format($request->amount, 2) }} {{ $user->currency->currency ?? "USD" }}</div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="uk-width-1-3@l">
                <div id="parent-div" style="position: relative;">
                    <div id="gateway-card" class="widjet__body uk-card" style="padding: 10px 25px;border-radius: 5px;">
                        <h3 class="uk-text-lead" style="padding-bottom: 0;margin-bottom: 5px;font-size: 22px">{{ $user->feeGroup->name ?? "" }}</h3>
                        <div class="image-container">
                            @if($feeGroup)
                                @if($feeGroup->getFirstMediaUrl('images'))
                                    <img src="{{ $feeGroup->getFirstMediaUrl('images') }}" alt="Fee Group Image" class="fit-image">
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <div class="widjet --activities">
                    <div class="widjet__head" style="margin-top: 20px">
                        <h3 class="uk-text-lead">@lang('messages.recent_orders')</h3>
                    </div>
                    <div class="d-flex justify-content-around my-3" style="margin-bottom: 10px">
                        <input type="date" id="start-date" value="{{$startDate}}" class="form-control" placeholder="@lang('messages.wallet_filter.start_date')">
                        <input type="date" id="end-date" value="{{$endDate}}" class="form-control" placeholder="@lang('messages.wallet_filter.end_date')">
                        <button class="btn btn-primary" id="filter-date-btn" style="font-size: 12px">@lang('messages.wallet_filter.apply_date_filter')</button>
                    </div>
                    <input type="text" id="search-orders" class="form-control uk-input" placeholder="@lang('messages.search_orders_placeholder')">
                    <div class="d-flex justify-content-around my-3" style="margin-bottom: 10px">
                        <button class="btn btn-outline-primary filter-btn wallet-filter-all" data-status="all">
                            @lang('messages.wallet_filter.all')
                        </button>
                        <button class="btn btn-outline-primary filter-btn" data-status="active">
                            @lang('messages.wallet_filter.active')
                            <i class="bi bi-check2-circle ms-1 fs-16 icon-search"></i>
                        </button>
                        <button class="btn btn-outline-primary filter-btn" data-status="pending">
                            @lang('messages.wallet_filter.pending')
                            <i class="bi bi-clock-history fs-16 icon-search"></i>
                        </button>
                        <button class="btn btn-outline-primary filter-btn" data-status="refunded">
                            @lang('messages.wallet_filter.refunded')
                            <i class="bi bi-x-circle fs-16 icon-search"></i>
                        </button>
                    </div>

                    <div class="widjet__body" id="orders-list-container">
                        @include('partials.orders_list', ['orders' => $orders])
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            document.getElementById('filter-date-btn').addEventListener('click', function () {
                const startDate = document.getElementById('start-date').value;
                const endDate = document.getElementById('end-date').value;

                if (!startDate || !endDate) {
                    toastr.error('Please select both start and end dates.');
                    return;
                }

                $.ajax({
                    url: '{{ route("wallet") }}',
                    method: 'GET',
                    data: { start_date: startDate, end_date: endDate },
                    success: function (response) {
                        $('#orders-list-container').html(response.html);
                        toastr.success('Filter applied successfully.');
                    },
                    error: function () {
                        toastr.error('An error occurred while applying the filter.');
                    }
                });
            });

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
                // $('#gateway-card').height(maxHeight + 40);
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

            // Filter orders by status
            $('.filter-btn').on('click', function() {
                var status = $(this).data('status');
                if (status === 'all') {
                    $('#orders-list .activities-item').show();
                } else {
                    $('#orders-list .activities-item').each(function() {
                        $(this).toggle($(this).data('status') === status);
                    });
                }
            });

            // Filter purchase requests by status
            $('.filter-pr-btn').on('click', function() {
                var status = $(this).data('status');
                if (status === 'all') {
                    $('#purchase-requests-list .activities-item').show();
                } else {
                    $('#purchase-requests-list .activities-item').each(function() {
                        $(this).toggle($(this).data('status') === status);
                    });
                }
            });
        });
    </script>
@endsection
