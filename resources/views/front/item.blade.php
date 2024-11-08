@php use Illuminate\Support\Facades\Auth; @endphp
@extends('front.layout')

@section('title', 'Items')

@section('content')
    <main class="page-main">
        <div class="uk-grid" data-uk-grid>
            @if(isset($item))
                <div class="uk-width-2-3@l">
                    <div class="">
{{--                        <div class="widjet__head">--}}
{{--                            <h3 class="uk-text-lead">Choose the Product</h3>--}}
{{--                        </div>--}}
                        <div class="widjet__body" style="position: relative">
                            <form id="subItemForm" method="POST" action="{{ route('purchase') }}">
                                @csrf
                                <input class="uk-input light-border" id="service_id" name="service_id" type="text" placeholder="Enter User ID In Application" style="position: absolute;bottom: -80px;left: 0;background: #FFF;">
                                <div class="uk-grid uk-grid-small uk-child-width-1-5@xl uk-child-width-1-4@m uk-child-width-1-3" data-uk-grid>
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
                                                 data-price="{{ number_format($subItem->price + ($subItem->price * $config->fee / 100), 2) }}"
                                                 data-is-custom="{{ $subItem->is_custom ? '1' : '0' }}"
                                                 data-min-amount="{{ $subItem->minimum_amount ?? 0 }}"
                                                 data-max-amount="{{ $subItem->max_amount ?? 0 }}"
                                                 data-amount="{{ $subItem->amount ?? 0 }}"
                                            >
                                                @if($subItem->is_custom == 0)
                                                    <div class="uk-card-header item-crd" style="padding: 10px !important;">
                                                        <div class="uk-grid-small uk-flex-middle" data-uk-grid>
                                                            <div class="uk-width-expand item-info">
                                                                <h3 class="uk-card-title uk-margin-remove-bottom" style="text-align: center;font-size: 14px;padding-left: 15px;">
                                                                    {{ $subItem->amount }} {{ $subItem->name }}
                                                                    @if($subItem->getFirstMediaUrl('images'))

                                                                        <img src="{{ $subItem->getFirstMediaUrl('images') }}" alt="{{ $subItem->name }}" class="uk-width-1-1"
                                                                             style="height: 20px; width: 20px; border-radius: 5px">
                                                                    @endif
                                                                </h3>
                                                                <p class="uk-card-title uk-margin-remove-bottom" style="text-align: center;font-size: 14px; margin-top: 5px">{{ $subItem->description ?? "" }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="uk-card-footer" style="text-align: center;border-top: 0;padding: 10px 20px; ">
                                                        <p>
                                                        </p>
                                                        <span class="uk-text-bold" style="color: #F46119; font-size: 18px;">
                                                            {{ number_format($subItem->price + ($subItem->price * $config->fee / 100), 2) }} {{ $user->currency->currency ?? "USD" }}
                                                        </span>
                                                        <i class="fas fa-heart fa-1x heart-icon" style="color: {{ $isFavorited ? '#f46119' : '#ccc' }}; position: absolute; top: 10px; left: 10px;"></i>
                                                    </div>
                                                    <div class="selected-icon" style="display: none; position: absolute; top: 10px; right: 10px; color: #f46119;">
                                                        <i class="fas fa-check-circle fa-1x"></i>
                                                    </div>
                                                @endif

                                                @if($subItem->is_custom == 1)
                                                    <div class="uk-card-header item-crd" style="padding: 10px !important;">
                                                        <div class="uk-grid-small uk-flex-middle" data-uk-grid>
                                                            <div class="uk-width-expand item-info">
                                                                <h3 class="uk-card-title uk-margin-remove-bottom" style="text-align: center;font-size: 14px;padding-left: 15px;">
                                                                    {{ $subItem->name }}
                                                                    @if($subItem->getFirstMediaUrl('images'))

                                                                        <img src="{{ $subItem->getFirstMediaUrl('images') }}" alt="{{ $subItem->name }}" class="uk-width-1-1"
                                                                             style="height: 20px; width: 20px; border-radius: 5px">
                                                                    @endif
                                                                </h3>
                                                                <p class="uk-card-title uk-margin-remove-bottom" style="text-align: center;font-size: 14px; margin-top: 5px">{{ $subItem->description ?? "" }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="uk-card-footer" style="text-align: center;border-top: 0;padding: 10px 20px; ">
                                                        <p>
                                                        </p>
                                                        <span class="uk-text-bold" style="color: #F46119; font-size: 18px;">Custom Amount</span>
                                                        <i class="fas fa-heart fa-1x heart-icon" style="color: {{ $isFavorited ? '#f46119' : '#ccc' }}; position: absolute; top: 10px; left: 10px;"></i>
                                                    </div>
                                                    <div class="selected-icon" style="display: none; position: absolute; top: 10px; right: 10px; color: #f46119;">
                                                        <i class="fas fa-check-circle fa-1x"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <!-- Hidden Custom Amount Input (initially hidden) -->
                                <div id="customAmountContainer" class="d-flex align-items-center justify-content-center" style="display: none; margin-top: 20px;">
                                    <label for="customAmount" class="me-2" style="flex: 0 0 auto; white-space: nowrap; margin-right: 20px;font-weight: 600;">Custom Amount:</label>
                                    <input type="number" id="customAmount" class="uk-input" name="custom_amount" placeholder="Enter amount"
                                           style="flex: 1; background: #FFF;
                                           border: 1px solid #F46119; max-width: 400px;;font-weight: 600;">
                                </div>

                                <input type="hidden" name="sub_item_id" id="selectedSubItemId">
                            </form>
                        </div>
                    </div>
                    <div class="game-profile-price" style="margin-top: 110px">
                        <div class="game-profile-price__value" data-currency="{{ $user->currency->currency ?? "USD" }}">0.00 {{ $user->currency->currency ?? "USD" }}</div>
                        <button id="buyNowButton" class="uk-button uk-button-danger uk-width-1-1" type="button"><span class="ico_shopping-cart"></span><span>Buy Now</span></button>
                        <button id="addToFavouritesButton" class="uk-button uk-button-primary uk-width-1-1" type="button"><span class="ico_add-square"></span><span>Add to Favourites</span></button>
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
                    <ul class="game-profile-card__list list-inline">
                        @foreach($item->tags as $tag)
                            <li class="list-inline-item" style="width: auto">
                                <span style="background: #F46119; margin-right: 5px; color: #FFF; padding: 5px; border-radius: 7px; font-size: 12px; font-weight: 900;">
                                    {{ $tag->name }}
                                    @if($tag->getFirstMediaUrl('images'))
                                        <img src="{{ $tag->getFirstMediaUrl('images') }}" alt="Item" width="15">
                                    @endif
                                </span>
                            </li>
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

            </div>
        </div>
    </main>

    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/libs.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Find all elements with class 'item-crd'
            var maxHeight = 0;

            $('.item-crd').each(function() {
                // Update maxHeight to the tallest element
                var currentHeight = $(this).innerHeight();
                if (currentHeight > maxHeight) {
                    maxHeight = currentHeight;
                }
            });

            console.log("maxHeight", maxHeight)
            // Set all elements to the maxHeight
            $('.item-crd').height(maxHeight);
        });

    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const customAmountContainer = $('#customAmountContainer');
            const customAmountInput = $('#customAmount');
            const priceElement = $('.game-profile-price__value'); // Assuming this element shows the total price
            let basePrice = 0; // Initialize the base price
            let unitAmount = 0; // Initialize the base amount unit

            $('.selectable-card').on('click', function() {
                const isCustom = $(this).data('is-custom') === 1;
                const minAmount = $(this).data('min-amount');
                const maxAmount = $(this).data('max-amount');
                basePrice = parseFloat($(this).data('price')); // Get the base price
                unitAmount = parseFloat($(this).data('amount')); // Get the base unit amount

                // Set selected sub-item ID
                $('#selectedSubItemId').val($(this).data('id'));

                // Show or hide the custom amount input based on is_custom
                if (isCustom) {
                    customAmountContainer.show();
                    customAmountInput.attr('min', minAmount);
                    customAmountInput.attr('max', maxAmount);
                    customAmountInput.attr('placeholder', `Enter amount between ${minAmount} and ${maxAmount}`);
                    customAmountInput.val(minAmount); // Set initial amount to minAmount

                    // Calculate initial price based on minAmount
                    const initialPrice = (minAmount / unitAmount) * basePrice;
                    priceElement.text(initialPrice.toFixed(2) + " " + $(priceElement).data('currency'));
                } else {
                    customAmountContainer.hide();
                    customAmountInput.val(''); // Clear the input if hidden
                    priceElement.text(basePrice.toFixed(2) + " " + $(priceElement).data('currency'));
                }
            });

            // Update price dynamically when custom amount changes
            customAmountInput.on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                const enteredAmount = parseFloat(customAmountInput.val());
                console.log("customAmountInput", enteredAmount)

                if (enteredAmount >= customAmountInput.attr('min') && enteredAmount <= customAmountInput.attr('max')) {
                    // Calculate the updated price based on the entered custom amount
                    const updatedPrice = (enteredAmount / unitAmount) * basePrice;
                    priceElement.text(updatedPrice.toFixed(2) + " " + $(priceElement).data('currency'));
                }
            });

            const cards = document.querySelectorAll('.selectable-card');
            let currency = $(priceElement).data('currency')
            const serviceIdInput = document.getElementById('service_id');
            const AmountInput = document.getElementById('customAmount');

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
                    priceElement.textContent = this.dataset.price + " " + currency; // Update the price
                });
            });

            document.getElementById('buyNowButton').addEventListener('click', function () {
                if (!selectedSubItemId) {
                    toastr.error('Please select a product first.');
                    return;
                }

                if (!serviceIdInput.value.trim()) {
                    toastr.error('Please enter User ID In Application.');
                    return;
                }

                const buyNowButton = this;
                buyNowButton.disabled = true; // Disable the button


                const formData = new FormData();
                formData.append('sub_item_id', selectedSubItemId);
                formData.append('service_id', serviceIdInput.value);
                formData.append('custom_amount', AmountInput?.value >> null);
                formData.append('_token', document.querySelector('input[name="_token"]').value);

                fetch('{{ route('purchase_order') }}', {
                    method: 'POST',
                    body: formData
                }).then(response => response.json())
                    .then(data => {
                        // setTimeout(function () {
                        //     buyNowButton.disabled = false;
                        // }, 5000);

                        if (data.success) {
                            setTimeout(function () {
                                toastr.success(data.message);
                                setTimeout(function () {
                                    window.location.href = "{{ route('home') }}";
                                }, 2000);
                            }, 100); // Wait for 5 seconds before showing the success toast and redirecting
                        } else {
                            toastr.error(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        toastr.error('There was an error processing your request.');
                        buyNowButton.disabled = false;
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
