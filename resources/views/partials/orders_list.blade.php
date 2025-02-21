<ul class="activities-list" id="orders-list" style="max-height: 400px; overflow: scroll;">
    @foreach($orders as $order)
        @php
            $orderSubItem = $order->subItems->first(); // Assuming there's at least one subItem
            $subItem = $orderSubItem->subItem ?? null;
            $item = $subItem->item ?? null;
        @endphp
        <li class="activities-item" data-id="{{ $order->id }}" data-name="{{ $item->name ?? 'Unknown Item' }}" data-status="{{ $order->status }}">
            <div class="activities-item__logo">
                @if(optional($item)->getFirstMediaUrl('front_image'))
                    <a href="{{ route('item.show', ['id' => $item->id]) }}">
                        <img src="{{ $item->getFirstMediaUrl('front_image') }}" alt="{{ $order->item_name ?? '' }}" style="height: 100%">
                    </a>
                @else
                    @if($item)
                        <a href="{{ route('item.show', ['id' => $item->id]) }}">
                            @if($item->getFirstMediaUrl('images'))
                                <img src="{{ $item->getFirstMediaUrl('images') }}" alt="{{ $order->item_name ?? '' }}" style="height: 100%">
                            @else
                                <img src="{{ asset('assets/img/default-image.jpg') }}" alt="Default Image">
                            @endif
                        </a>
                    @else
                        <img src="{{ asset('assets/img/default-image.jpg') }}" alt="Default Image">
                    @endif
                @endif
            </div>
            <div class="activities-item__info">
                @if($item)
                    <a class="activities-item__title" href="{{ route('item.show', ['id' => $item->id]) }}">
                        {{ $order->item_name ?? '' }}
                    </a>
                @else
                    <div class="activities-item__title">{{ $order->item_name ?? '' }}</div>
                @endif
                <div class="activities-item__date">@lang('messages.order_activity.order_id'): #{{ $order->id }}</div>
                <div class="activities-item__date">@lang('messages.order_activity.service_id'): #{{ $order->service_id ?? '' }}</div>
                <div class="activities-item__date">@lang('messages.order_activity.amount'): {{ $order->amount ?? '' }}</div>
                <div class="activities-item__date">{{ $order->created_at->format('d M, Y - H:i') }}</div>
                <div class="activities-item__status">
                    @if($order->status == 'canceled' || $order->status == 'refunded')
                        <span class="badge bg-danger-subtle text-danger rounded-pill item__status">
                            @lang('messages.order_activity.status.' . $order->status)
                            @if($order->created_at != $order->updated_at)
                                <span>{{ $order->updated_at ?? '' }}</span>
                            @endif
                        </span>
                    @elseif($order->status == 'active')
                        <span class="badge bg-success-subtle text-success rounded-pill item__status">
                            @lang('messages.order_activity.status.' . $order->status)
                            @if($order->created_at != $order->updated_at)
                                <span>{{ $order->updated_at ?? '' }}</span>
                            @endif
                        </span>
                    @else
                        <span class="badge bg-warning-subtle text-secondary rounded-pill item__status">
                            @lang('messages.order_activity.status.' . $order->status)
                            @if($order->created_at != $order->updated_at)
                                <span>{{ $order->updated_at ?? '' }}</span>
                            @endif
                        </span>
                    @endif
                        <button class="order-details-btn btn btn-primary" style="font-size: 10px;font-weight: 800;">Details</button>
                </div>
                <p class="reply_msg" style="display: none">{{$order?->reply_msg ?? ""}}</p>
            </div>
            <div class="activities-item__price">{{ substr($order->total ?? 0, 0, 6) }} {{ $user->currency->currency ?? 'USD' }}</div>
        </li>
    @endforeach
</ul>
