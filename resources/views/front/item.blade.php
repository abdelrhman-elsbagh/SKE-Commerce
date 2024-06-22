@php use Illuminate\Support\Facades\Auth; @endphp
@extends('front.layout')

@section('title', 'Ske E-Commerce')

@section('content')
    <main class="page-main">
        <div class="uk-grid" data-uk-grid>
            @if(isset($item))
                <div class="uk-width-2-3@l">
                    <div class="">
                        <div class="widjet__head">
                            <h3 class="uk-text-lead">Choose the Product</h3>
                        </div>
                        <div class="widjet__body" style="position: relative">
                            <form id="subItemForm" method="POST" action="{{ route('purchase') }}">
                                @csrf
                                <input class="uk-input" id="service_id" name="service_id" type="text" placeholder="Enter service ID" style="position: absolute;bottom: -80px;left: 0;background: #FFF;">
                                <div class="uk-grid uk-child-width-1-5@s uk-grid-small" data-uk-grid>
                                    @foreach($item->subItems as $subItem)
                                        @php
                                                $isFavorited = false;
                                                if(Auth::user()){

                                                $isFavorited = Auth::user()->favorites()->where('sub_item_id', $subItem->id)->exists();
                                                }
                                        @endphp
                                        <div>
                                            <div class="uk-card uk-card-default uk-card-hover uk-margin selectable-card"
                                                 style="cursor: pointer; position: relative;border-radius: 7px;"
                                                 data-id="{{ $subItem->id }}"
                                                 data-price="{{ number_format($subItem->price, 2) }}">
                                                <div class="uk-card-header item-crd">
                                                    <div class="uk-grid-small uk-flex-middle" data-uk-grid>
                                                        <div class="uk-width-expand">
                                                            <h3 class="uk-card-title uk-margin-remove-bottom" style="text-align: center;font-size: 16px"> <span class="" style="margin: 0;font-size: 20px"></span> {{ $subItem->amount }} {{ $subItem->name }} </h3>
                                                            <p class="uk-card-title uk-margin-remove-bottom" style="text-align: center;font-size: 15px; margin-top: 5px">{{ $subItem->description ?? "" }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($subItem->getFirstMediaUrl('images'))
                                                    <div class="uk-card-body" style="padding: 10px;text-align: center">

                                                            <img src="{{ $subItem->getFirstMediaUrl('images') }}" alt="{{ $subItem->name }}" class="uk-width-1-1"
                                                                 style="height: 150px">
                                                    </div>
                                                @endif
                                                <div class="uk-card-footer" style="text-align: center; ">
                                                    <span class="uk-text-bold" style="color: #F46119; font-size: 18px;">
                                                        {{ number_format($subItem->price + ($subItem->price * $config->fee / 100), 2) }} USD
                                                    </span>
                                                    <i class="fas fa-heart fa-1x heart-icon" style="color: {{ $isFavorited ? '#f46119' : '#ccc' }}; position: absolute; top: 10px; left: 10px;"></i>
                                                </div>
                                                <div class="selected-icon" style="display: none; position: absolute; top: 10px; right: 10px; color: #f46119;">
                                                    <i class="fas fa-check-circle fa-2x"></i>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <input type="hidden" name="sub_item_id" id="selectedSubItemId">
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <p>No items found.</p>
            @endif
            <div class="uk-width-1-3@l">
                <div class="game-profile-card">
                    <div class="game-profile-card__media">
                        @if(isset($item) && $item->getFirstMediaUrl('images'))
                            <img src="{{ $item->getFirstMediaUrl('images') }}" alt="Item">
                        @endif
                    </div>
                    <div class="game-profile-card__intro">
                        <span>{{$item->description}}</span>
                    </div>
                    <ul class="game-profile-card__list">
                        <li>
                            <div>Sell Count:</div>
                            <div class="game-card__rating"><span>15</span></div>
                        </li>
                        <li>
                            <div>Tags:</div>
                            @foreach($item->tags as $tag)
                                <span class="" style="background: #F46119;margin-right: 5px;color: #FFF;padding: 5px;border-radius: 7px;font-size: 12px;font-weight: 900;">{{ $tag->name }}</span>
                            @endforeach
                        </li>
                    </ul>
                    <ul class="game-profile-card__type">
                        @foreach($item->tags as $tag)
                            <li><span>{{ $tag->name }}</span></li>
                        @endforeach
                    </ul>
                </div>

                <div class="game-profile-card__intro"  style="border-radius: 5px;background: #fff;padding: 10px;">
                    <ul>
                        <li style="color: #079992;"><i class="fas fa-lock"></i> Secure Payments</li>
                        <li style="color: #079992;"><i class="fas fa-shield-alt"></i> Advanced Encryption</li>
                        <li style="color: #079992;"><i class="fas fa-check-circle"></i> Trusted Gateways</li>
                    </ul>
                    <span>Your payment security is our top priority. We use advanced encryption to protect your data, ensuring all transactions are processed safely through trusted gateways. Shop confidently with our secure payment system.</span>
                </div>
                <div class="game-profile-price" style="margin-top: 20px">
                    <div class="game-profile-price__value">$0.00 USD</div>
                    <button id="buyNowButton" class="uk-button uk-button-danger uk-width-1-1" type="button"><span class="ico_shopping-cart"></span><span>Buy Now</span></button>
                    <button id="addToFavouritesButton" class="uk-button uk-button-primary uk-width-1-1" type="button"><span class="ico_add-square"></span><span>Add to Favourites</span></button>
                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('assets/js/libs.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Find all elements with class 'item-crd'
            var maxHeight = 0;

            $('.item-crd').each(function() {
                // Update maxHeight to the tallest element
                var currentHeight = $(this).height();
                if (currentHeight > maxHeight) {
                    maxHeight = currentHeight;
                }
            });

            // Set all elements to the maxHeight
            $('.item-crd').height(maxHeight);
        });

    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cards = document.querySelectorAll('.selectable-card');
            const priceElement = document.querySelector('.game-profile-price__value');
            const serviceIdInput = document.getElementById('service_id');
            let selectedSubItemId = null;

            cards.forEach(card => {
                card.addEventListener('click', function () {
                    cards.forEach(c => {
                        c.style.border = 'none';
                        c.querySelector('.selected-icon').style.display = 'none';
                    });
                    this.style.border = '2px solid #f46119';
                    this.querySelector('.selected-icon').style.display = 'block';
                    selectedSubItemId = this.dataset.id;
                    document.getElementById('selectedSubItemId').value = this.dataset.id; // Set the selected sub-item ID in the hidden input
                    priceElement.textContent = '$' + this.dataset.price; // Update the price
                });
            });

            document.getElementById('buyNowButton').addEventListener('click', function () {
                if (!selectedSubItemId) {
                    toastr.error('Please select a product first.');
                    return;
                }

                if (!serviceIdInput.value.trim()) {
                    toastr.error('Please enter a service ID.');
                    return;
                }

                const formData = new FormData();
                formData.append('sub_item_id', selectedSubItemId);
                formData.append('service_id', serviceIdInput.value);
                formData.append('_token', document.querySelector('input[name="_token"]').value);

                fetch('{{ route('purchase_order') }}', {
                    method: 'POST',
                    body: formData
                }).then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            toastr.success(data.message);
                        } else {
                            toastr.error(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        toastr.error('There was an error processing your request.');
                    });
            });

            document.getElementById('addToFavouritesButton').addEventListener('click', function () {
                if (!selectedSubItemId) {
                    toastr.error('Please select a product first.');
                    return;
                }

                const formData = new FormData();
                formData.append('sub_item_id', selectedSubItemId);
                formData.append('_token', document.querySelector('input[name="_token"]').value);

                fetch('{{ route('favorites.add') }}', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            toastr.success(data.message);
                            // Change heart icon color on success
                            cards.forEach(card => {
                                if (card.dataset.id == selectedSubItemId) {
                                    card.querySelector('.heart-icon').style.color = '#f46119';
                                }
                            });
                        } else {
                            toastr.error(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        toastr.error('There was an error processing your request.');
                    });
            });
        });
    </script>
@endsection
