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
                                <input class="uk-input light-border" id="service_id" name="service_id" type="text"
                                       placeholder="{{ __('messages.enter_user_id_in_application') }}" style="position: absolute;bottom: -80px;left: 0;background: #FFF;">
                                <div class="uk-grid uk-grid-small uk-child-width-1-5@xl uk-child-width-1-4@m uk-child-width-1-3" data-uk-grid>
                                    @foreach($item->subItems as $subItem)
                                        @php
                                            $isFavorited = false;
                                            if(Auth::user()){

                                            $isFavorited = Auth::user()->favorites()->where('sub_item_id', $subItem->id)->exists();
                                            }
                                            $isInactive = $subItem->status == 'inactive';
                                        @endphp
                                        <div class="wrapper-item-card {{ $isInactive ? 'hidden-overflow' : '' }}"  style="position: relative; margin-bottom: 10px;">
                                            @if($isInactive)
                                                <div class="card-tag card-tag-inactive">Not Available</div>
                                            @endif
                                            <div class="uk-card uk-card-default uk-card-hover uk-margin selectable-card whole-item-card"
                                                 data-id="{{ $subItem->id }}"
                                                 data-price="{{ number_format($subItem->price + ($subItem->price * $config->fee / 100), 4) }}"
                                                 data-is-custom="{{ $subItem->is_custom ? '1' : '0' }}"
                                                 data-min-amount="{{ $subItem->minimum_amount ?? 0 }}"
                                                 data-max-amount="{{ $subItem->max_amount ?? 0 }}"
                                                 data-amount="{{ $subItem->amount ?? 0 }}"
                                                 @if($isInactive) style="pointer-events: none; opacity: 0.5;" @endif>
                                                @if($subItem->is_custom == 0)
                                                    <div class="uk-card-header item-crd" style="padding: 10px !important;">
                                                        <div class="uk-grid-small uk-flex-middle" data-uk-grid>
                                                            <div class="uk-width-expand item-info item-crd-detail">
                                                                <h3 class="uk-card-title uk-margin-remove-bottom item-detail-title" style="">
                                                                    {{ $subItem->amount }} {{ $subItem->name }}
                                                                    @if(optional($subItem)->getFirstMediaUrl('images'))

                                                                        <img src="{{ $subItem->getFirstMediaUrl('images') }}" alt="{{ $subItem->name }}" class="uk-width-1-1"
                                                                             style="height: 20px; width: 20px; border-radius: 5px">
                                                                    @endif
                                                                </h3>
                                                                <p class="uk-card-title uk-margin-remove-bottom item-detail-desc" style="display: none"> {{ $subItem->description ?? ""  }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="uk-card-footer" style="text-align: center;border-top: 0;padding: 10px 20px; ">
                                                        <p>
                                                        </p>
                                                        <span class="uk-text-bold sub-item-price">
                                                            {{ number_format($subItem->price + ($subItem->price * $config->fee / 100), 4) }} {{ $user->currency->currency ?? "USD" }}
                                                        </span>
                                                        <i id="addToFavouritesButton" class="fas fa-heart fa-1x heart-icon favourite-item" style="color: {{ $isFavorited ? 'var(--main-color)' : '#ccc' }}; position: absolute; top: 10px; left: 10px;"></i>
                                                    </div>
                                                    <div class="selected-icon main-color" style="display: none; position: absolute; top: 10px; right: 10px;">
                                                        <i class="fas fa-check-circle fa-1x"></i>
                                                    </div>
                                                @endif

                                                @if($subItem->is_custom == 1)
                                                    <div class="uk-card-header item-crd" style="padding: 10px !important;">
                                                        <div class="uk-grid-small uk-flex-middle " data-uk-grid>
                                                            <div class="uk-width-expand item-info item-crd-detail">
                                                                <h3 class="uk-card-title uk-margin-remove-bottom item-detail-title" style="text-align: center;font-size: 14px;padding-left: 15px;">
                                                                    {{ $subItem->name }}
                                                                    @if(optional($subItem)->getFirstMediaUrl('images'))

                                                                        <img src="{{ $subItem->getFirstMediaUrl('images') }}" alt="{{ $subItem->name }}" class="uk-width-1-1"
                                                                             style="height: 20px; width: 20px; border-radius: 5px">
                                                                    @endif
                                                                </h3>
                                                                <p class="uk-card-title uk-margin-remove-bottom item-detail-desc"
                                                                   style="" disabled="none">{{ $subItem->description ?? "" }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="uk-card-footer" style="text-align: center;border-top: 0;padding: 10px 20px; ">
                                                        <p>
                                                        </p>
                                                        <span class="uk-text-bold main-color" style=" font-size: 16px;">Custom Amount</span>
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
                    </div>
                </div>
            @else
                <p>No items found.</p>
            @endif
            <div class="uk-width-1-3@l desc-item-details">
                <div class="game-profile-card">
                    <div class="game-profile-card__media">
                        @if(isset($item) && $item->getFirstMediaUrl('images'))
                            <img src="{{ $item->getFirstMediaUrl('images') }}" alt="Item">
                        @endif
                    </div>
                    <div class="game-profile-card__intro">
                        <span>{{ App::getLocale() == 'ar' && $item->ar_description ? $item->ar_description : $item->name }}</span>
                        <span id="item_name_service" style="display: none">{{ App::getLocale() == 'ar' ? $item->ar_name : $item->name }}</span>
                    </div>
                    <ul class="game-profile-card__list list-inline">
                        @foreach($item->tags as $tag)
                            <li class="list-inline-item" style="width: auto">
                                <span style="background: #F46119; margin-right: 5px; color: #FFF; padding: 5px; border-radius: 7px; font-size: 12px; font-weight: 900;">
                                    {{ App::getLocale() == 'ar' && $tag->ar_name ? $tag->ar_name : $tag->name }}
                                @if(optional($tag)->getFirstMediaUrl('images'))
                                        <img src="{{ $tag->getFirstMediaUrl('images') }}" alt="Item" width="15">
                                    @endif
                                </span>
                            </li>
                        @endforeach
                    </ul>



                </div>
                <div class="game-profile-card__intro"  style="border-radius: 5px;background: #fff;padding: 10px;">
                    <ul>
                        @if(App::getLocale() == 'ar')
                            <li style="color: #079992;"><i class="fas fa-lock"></i> مدفوعات آمنة</li>
                            <li style="color: #079992;"><i class="fas fa-shield-alt"></i> تشفير متقدم</li>
                            <li style="color: #079992;"><i class="fas fa-check-circle"></i> بوابات موثوقة</li>
                        @else
                            <li style="color: #079992;"><i class="fas fa-lock"></i> Secure Payments</li>
                            <li style="color: #079992;"><i class="fas fa-shield-alt"></i> Advanced Encryption</li>
                            <li style="color: #079992;"><i class="fas fa-check-circle"></i> Trusted Gateways</li>
                        @endif

                    </ul>
                    @if(App::getLocale() == 'ar')
                        <span>أمان الدفع الخاص بك هو أولويتنا القصوى. نحن نستخدم تقنيات تشفير متقدمة لحماية بياناتك، مما يضمن معالجة جميع المعاملات بأمان عبر بوابات موثوقة. تسوق بثقة مع نظام الدفع الآمن الخاص بنا.</span>
                    @else
                        <span>Your payment security is our top priority. We use advanced encryption to protect your data, ensuring all transactions are processed safely through trusted gateways. Shop confidently with our secure payment system.</span>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/libs.js') }}"></script>
    <script>
        $(document).ready(function() {
            let maxHeight = 0;

            $('.item-crd-detail .uk-card-title').each(function() {
                // Update maxHeight to the tallest element
                let currentHeight = $(this).height();
                if (currentHeight > maxHeight) {
                    maxHeight = currentHeight;
                }
            });
            // Set all elements to the maxHeight
            $('.item-crd-detail').height(maxHeight + 25);
        });

    </script>
    <script>
        const messages = {
            enterUserId: "{{ __('messages.enter_user_id') }}",
            selectProductFirst: "{{ __('messages.select_product_first') }}",
            errorProcessingRequest: "{{ __('messages.error_processing_request') }}"

        };
        document.addEventListener('DOMContentLoaded', function () {
            const customAmountContainer = $('#customAmountContainer');
            const customAmountInput = $('#customAmount');
            const serviceInput = $('#service_id');
            const priceElement = $('.game-profile-price__value');
            let basePrice = 0; // Initialize the base price
            let unitAmount = 0; // Initialize the base amount unit

            $('.selectable-card').on('click', function() {
                if ($(this).css('pointer-events') === 'none') {
                    return;
                }
                const isCustom = $(this).data('is-custom') === 1;
                const minAmount = $(this).data('min-amount');
                const maxAmount = $(this).data('max-amount');
                basePrice = parseFloat($(this).data('price')); // Get the base price
                unitAmount = parseFloat($(this).data('amount')); // Get the base unit amount
                let desc_data = $(this).find(".item-detail-desc").html() ?? "";
                let item_name = $("#item_name_service").html() ?? "";
                // Set selected sub-item ID
                $('#selectedSubItemId').val($(this).data('id'));

                serviceInput.attr('placeholder', `Please Enter ${desc_data} For " ${item_name} "`);

                // Show or hide the custom amount input based on is_custom
                if (isCustom) {
                    console.log("it's custom")
                    customAmountContainer.show();
                    customAmountInput.attr('min', minAmount);
                    customAmountInput.attr('max', maxAmount);
                    customAmountInput.attr('placeholder', `Enter amount between ${minAmount} and ${maxAmount}`);
                    customAmountInput.val(minAmount); // Set initial amount to minAmount

                    // Calculate initial price based on minAmount
                    const initialPrice = (minAmount / unitAmount) * basePrice;
                    priceElement.text(initialPrice.toFixed(4) + " " + $(priceElement).data('currency'));
                } else {
                    customAmountContainer.hide();
                    customAmountInput.val(''); // Clear the input if hidden
                    priceElement.text(basePrice.toFixed(4) + " " + $(priceElement).data('currency'));
                }
            });

            // Update price dynamically when custom amount changes
            customAmountInput.on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                const enteredAmount = parseFloat(customAmountInput.val());
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
                    toastr.error(messages.selectProductFirst);
                    return;
                }

                if (!serviceIdInput.value.trim()) {
                    toastr.error(messages.enterUserId);
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
                        toastr.error(messages.errorProcessingRequest);
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

    <style>

        .card-tag-inactive {
            text-align: center;
            width: 190px;
            transform: rotate(-45deg);  /* Use 'transform' instead of 'rotate' */
            position: absolute;
            top: 27px;
            left: -36px;
            padding: 9px 20px;
            font-size: 14px;
            font-weight: bold;
            color: white;
            border-radius: 2px;
            max-height: 47px;
            background-color: rgb(255, 0, 0); /* Full opaque red background */
            z-index: 99;
            opacity: 1; /* This ensures the child has full opacity */
            white-space: nowrap;
        }

        .whole-item-card{
            position: relative;
        }

    </style>

@endsection
