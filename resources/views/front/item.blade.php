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
                        <div class="widjet__body">
                            <form id="subItemForm" method="POST" action="{{ route('purchase') }}">
                                @csrf
                                <div class="uk-grid uk-child-width-1-3@s uk-grid-small" data-uk-grid>
                                    @foreach($item->subItems as $subItem)
                                        @php
                                            $isFavorited = Auth::user()->favorites()->where('sub_item_id', $subItem->id)->exists();
                                        @endphp
                                        <div>
                                            <div class="uk-card uk-card-default uk-card-hover uk-margin selectable-card"
                                                 style="cursor: pointer; position: relative;border-radius: 7px;"
                                                 data-id="{{ $subItem->id }}"
                                                 data-price="{{ number_format($subItem->price, 2) }}">
                                                <div class="uk-card-header">
                                                    <div class="uk-grid-small uk-flex-middle" data-uk-grid>
                                                        <div class="uk-width-expand">
                                                            <h3 class="uk-card-title uk-margin-remove-bottom" style="text-align: center;font-size: 20px"> <span class=""
                                                                                                                                                                style="margin: 0;font-size: 20px"></span> {{ $subItem->amount }} {{ $subItem->name }} </h3>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-card-body" style="padding: 10px;text-align: center">
                                                    @if($subItem->getFirstMediaUrl('images'))
                                                        <img src="{{ $subItem->getFirstMediaUrl('images') }}" alt="{{ $subItem->name }}" class="uk-width-1-1"
                                                             style="height: 150px">
                                                    @else
                                                        <img src="{{ asset('assets/img/default-image.jpg') }}" alt="Default Image" class="uk-width-1-1">
                                                    @endif
                                                </div>
                                                <div class="uk-card-footer">
                                                    <span class="uk-text-bold">${{ number_format($subItem->price, 2) }}</span>
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
{{--                                <button type="submit" class="uk-button uk-button-primary uk-margin-top">Purchase</button>--}}
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
                    <div class="game-profile-card__intro"><span>TeamHost is a simulation and strategy game of managing a city struggling to survive after apocalyptic global cooling.</span></div>
                    <ul class="game-profile-card__list">
                        <li>
                            <div>Reviews:</div>
                            <div class="game-card__rating"><span>4.7</span><i class="ico_star"></i><span class="rating-vote">(433)</span></div>
                        </li>
                        <li>
                            <div>Release date:</div>
                            <div>24 Apr, 2018</div>
                        </li>
                        <li>
                            <div>Developer:</div>
                            <div>11 bit studios</div>
                        </li>
                        <li>
                            <div>Platforms:</div>
                            <div class="game-card__platform"><i class="ico_windows"></i><i class="ico_apple"></i></div>
                        </li>
                    </ul>
                    <ul class="game-profile-card__type">
                        <li><span>Strategy</span></li>
                        <li><span>Survival</span></li>
                        <li><span>City Builder</span></li>
                        <li><span>Dark</span></li>
                    </ul>
                </div>

                <div class="game-profile-card__intro"><span>TeamHost is a simulation and strategy game of managing a city struggling to survive after apocalyptic global cooling.</span></div>
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
        document.addEventListener('DOMContentLoaded', function () {
            const cards = document.querySelectorAll('.selectable-card');
            const priceElement = document.querySelector('.game-profile-price__value');
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

                const formData = new FormData();
                formData.append('sub_item_id', selectedSubItemId);
                formData.append('_token', document.querySelector('input[name="_token"]').value);

                fetch('{{ route('purchase') }}', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
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
