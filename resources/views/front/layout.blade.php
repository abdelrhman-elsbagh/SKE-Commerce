<!DOCTYPE html>
<html lang={{ app()->getLocale() }}>

<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Document')</title>
    <meta content="{{$config->name ?? "Ske E-Commerce"}} " name="author">
    <meta content="{{$config->seo_description ?? "Ske E-Commerce"}}" name="description">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="HandheldFriendly" content="true">
    <meta name="format-detection" content="telephone=no">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    @if($config->getFirstMediaUrl('fav_icon'))
        <link rel="shortcut icon" href="{{ $config->getFirstMediaUrl('fav_icon') }}" type="image/x-icon">
    @else
        <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png')}}" type="image/x-icon">
    @endif

    <link rel="stylesheet" href="{{ asset('assets/css/libs.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css')}}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Marcellus&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Marcellus&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Option 1: Include in HTML -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <style>
        :root {
            --main-color: {{ $mainColor ?? $config->main_color ?? '#F46119' }};
        }
    </style>

    <!-- Include Fonts Conditionally Based on Language -->
    @if(app()->getLocale() === 'ar')
        @switch($config->ar_font)
            @case('Cairo')
                <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
                <style>
                    body, h1, h2, h3, h4, h5, h6, p, div, a {
                        font-family: 'Cairo', sans-serif !important;
                    }
                </style>
                @break
            @case('Almarai')
                <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700&display=swap" rel="stylesheet">
                <style>
                    body, h1, h2, h3, h4, h5, h6, p, div, a {
                        font-family: 'Almarai', sans-serif !important;
                    }
                </style>
                @break
            @case('Amiri')
                <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap" rel="stylesheet">
                <style>
                    body, h1, h2, h3, h4, h5, h6, p, div, a {
                        font-family: 'Amiri', serif !important;
                    }
                </style>
                @break
            @case('Marhey')
                <link href="https://fonts.googleapis.com/css2?family=Marhey:wght@400;500;700&display=swap" rel="stylesheet">
                <style>
                    body, h1, h2, h3, h4, h5, h6, p, div, a {
                        font-family: 'Marhey', cursive !important;
                    }
                </style>
                @break
        @endswitch
    @else
        @switch($config->font)
            @case('Oswald')
                <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;600;700&display=swap" rel="stylesheet">
                <style>
                    body, h1, h2, h3, h4, h5, h6, p, div, a {
                        font-family: 'Oswald', sans-serif !important;
                    }
                </style>
                @break
            @case('Noto Sans')
                <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;700&display=swap" rel="stylesheet">
                <style>
                    body, h1, h2, h3, h4, h5, h6, p, div, a {
                        font-family: 'Noto Sans', sans-serif !important;
                    }
                </style>
                @break
            @case('Raleway')
                <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;500;600;700&display=swap" rel="stylesheet">
                <style>
                    body, h1, h2, h3, h4, h5, h6, p, div, a {
                        font-family: 'Raleway', sans-serif !important;
                    }
                </style>
                @break
            @case('Roboto')
                <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
                <style>
                    body, h1, h2, h3, h4, h5, h6, p, div, a {
                        font-family: 'Roboto', sans-serif !important;
                    }
                </style>
                @break
        @endswitch
    @endif

    @if(app()->getLocale() === 'ar')
        <link rel="stylesheet" href="{{ asset('assets/css/main-ar.css') }}">
    @endif
</head>

<body class="page-store">

<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> <!-- if jQuery is not already included -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    // Configuration for Toastr
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "showDuration": "400",
        "hideDuration": "1000",
        "timeOut": "7000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
</script>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        const darkThemeLink = document.createElement('link');
        darkThemeLink.rel = 'stylesheet';
        darkThemeLink.href = '{{ asset('assets/css/dark.css') }}';
        darkThemeLink.id = 'dark-theme-css';

        // Check if the cookie exists and set the checkbox state and theme accordingly
        const darkThemeCookie = document.cookie.split('; ').find(row => row.startsWith('darkTheme='));
        if (darkThemeCookie && darkThemeCookie.split('=')[1] === 'true') {
            document.getElementById("toggle").checked = true;
            document.getElementsByTagName('body')[0].classList.add("dark-theme");
            document.head.appendChild(darkThemeLink);
        }

        document.getElementById("toggle").addEventListener("change", function() {
            if (this.checked) {
                document.getElementsByTagName('body')[0].classList.add("dark-theme");
                document.cookie = "darkTheme=true; path=/; max-age=" + 60 * 60 * 24 * 365;
                document.head.appendChild(darkThemeLink);
            } else {
                document.getElementsByTagName('body')[0].classList.remove("dark-theme");
                document.cookie = "darkTheme=false; path=/; max-age=" + 60 * 60 * 24 * 365;
                const darkThemeCss = document.getElementById('dark-theme-css');
                if (darkThemeCss) {
                    document.head.removeChild(darkThemeCss);
                }
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const purchaseRequestForm = document.getElementById('purchaseRequestForm');
        const imageInput = document.getElementById('image');
        const formMessages = document.getElementById('form-messages');
        const imagePreview = document.createElement('img');
        imagePreview.style.maxWidth = '100%';
        imagePreview.style.maxHeight = '200px';
        imagePreview.style.marginTop = '10px';
        imageInput.insertAdjacentElement('afterend', imagePreview);

        purchaseRequestForm.addEventListener('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            fetch('{{ route('purchase.request') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => { throw new Error(text) });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        toastr.success(data.message);
                        UIkit.modal('#modal-purchase-request').hide();
                        purchaseRequestForm.reset();
                        imagePreview.src = '';
                        setTimeout(() => {
                            window.location.href = "{{ route('home') }}";
                        }, 1500); // Hide the modal after 2 seconds
                    } else {
                        toastr.error(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error.message);
                    toastr.error('There was an error processing your request: ' + error.message);
                });
        });

        imageInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imagePreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>

<!-- Loader-->
<div id="page-preloader">
    <div class="preloader-1">
        <div class="loader-text">Loading</div>
        <span class="line line-1"></span>
        <span class="line line-2"></span>
        <span class="line line-3"></span>
        <span class="line line-4"></span>
        <span class="line line-5"></span>
        <span class="line line-6"></span>
        <span class="line line-7"></span>
        <span class="line line-8"></span>
        <span class="line line-9"></span>
    </div>
</div>
<!-- Loader end-->
<div class="page-wrapper">
    <header class="page-header">
        <div class="page-header__inner">
            <div class="page-header__sidebar">
                <div class="page-header__menu-btn"><button class="menu-btn ico_menu"></button></div>
                <div class="page-header__logo">
                    <a href="{{ route('home') }}">
                        @if(isset($_COOKIE['darkTheme']) && $_COOKIE['darkTheme']=== 'true' && $config->getFirstMediaUrl('dark_logos'))
                            <img src="{{ $config->getFirstMediaUrl('dark_logos') }}" alt="dark logo" style="">
                        @elseif($config->getFirstMediaUrl('logos'))
                            <img src="{{ $config->getFirstMediaUrl('logos') }}" alt="logo" style="">
                        @else
                            <img src="{{ asset('assets/img/logo.png') }}" alt="default logo">
                        @endif
                    </a>
                </div>
            </div>
            <div class="page-header__content">
                <div class="page-header__search1"></div>
                <div class="page-header__action">
                    @auth('business_client')
                        <a class="profile head-balance-icon" href="{{route('business-wallet')}}">
                            <div class="activities-item__price">
                                {{\Illuminate\Support\Facades\Auth::user()->wallet->balance ?? 0}} {{ $user->currency->currency ?? "USD" }}
                            </div>
                        </a>
                        <a class="profile head-balance-icon" href="{{route('business-profile')}}">
                            <img src="{{ asset('assets/img/profile.png')}}" alt="profile">
                        </a>
                    @elseauth('web')
                        <div class="fav-head-icon">
                            <a href="{{route('favourites')}}">
{{--                                <i class="fas fa-heart"></i><span class="count">{{$favoritesCount}}</span>--}}
                                <i class="fas fa-heart"></i>
                            </a>
                        </div>
                        <a class="profile head-wallet-icon" href="{{route('wallet')}}">
                            <div class="activities-item__price">
                                {{\Illuminate\Support\Facades\Auth::user()->wallet->balance ?? 0}} {{ $user->currency->currency ?? "USD" }}

                            </div>
                        </a>
                        <a class="profile" href="{{route('profile')}}">
                            @if(\Illuminate\Support\Facades\Auth::user()->getFirstMediaUrl('avatars'))
                                <img src="{{ \Illuminate\Support\Facades\Auth::user()->getFirstMediaUrl('avatars') }}" alt="profile" style="margin-left: 50px; border-radius: 50%">
                            @else
                                <img src="{{ asset('assets/img/profile.png') }}" alt="profile" style="margin-left: 50px;">
                            @endif
                        </a>
                    @endauth

                        <!-- Language Switcher -->
                        <div class="language-switcher" style="margin-left: 20px; display: flex; align-items: center;">
                            @if(app()->getLocale() === 'en')
                                <!-- Show Arabic Language Icon -->
                                <a href="{{ route('change-language', ['lang' => 'ar']) }}" title="Switch to Arabic" style="margin-left: 10px;">
                                    <img src="{{ asset('assets/img/uae-flag.png') }}" alt="Arabic" style="width: 24px; height: 24px; border-radius: 50%;">
                                </a>
                            @elseif(app()->getLocale() === 'ar')
                                <!-- Show English Language Icon -->
                                <a href="{{ route('change-language', ['lang' => 'en']) }}" title="Switch to English" style="margin-left: 10px;">
                                    <img src="{{ asset('assets/img/us.jpg') }}" alt="English" style="width: 24px; height: 24px; border-radius: 50%;">
                                </a>
                            @endif
                        </div>

                </div>
            </div>
        </div>
    </header>
    <div class="page-content">

        <aside class="sidebar" id="sidebar">
            <input id="toggle" type="checkbox">
            <div class="sidebar-box">
                <ul class="uk-nav">
                    @if(Auth::guard('web')->guest() && Auth::guard('business_client')->guest())
                        <li id="login"><a href="{{ route('sign-in') }}"><i class="fas fa-sign-in-alt"></i><span>@lang('messages.login')</span></a></li>
                        <li id="register"><a href="{{ route('register-page') }}"><i class="fas fa-user-plus"></i><span>@lang('messages.register')</span></a></li>
                    @endif

                    @auth('business_client')
                        <li id="business-profile"><a href="{{route('business-profile')}}"><i class="fas fa-user"></i><span>@lang('messages.profile')</span></a></li>
                        <li id="business-wallet"><a href="{{route('business-wallet')}}"><i class="fas fa-wallet"></i><span>@lang('messages.wallet')</span></a></li>
                        <li id="plans"><a href="{{ route('plans-page') }}"><i class="fas fa-box-open"></i><span>@lang('messages.plans')</span></a></li>
                        <li>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-business').submit();">
                                <i class="fas fa-sign-out-alt"></i><span>@lang('messages.logout')</span>
                            </a>
                            <form id="logout-form-business" action="{{ route('business-logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    @elseauth('web')
                        @if(Auth::user()->hasPermissionTo('admin'))
                            <li id="dashboard"><a href="{{route('dashboard')}}"><i class="fas fa-tachometer-alt"></i><span>@lang('messages.admin_dashboard')</span></a></li>
                        @else
                            <li id="home"><a href="{{route('home')}}"><i class="fas fa-home"></i><span>@lang('messages.home')</span></a></li>
                            <li id="wallet"><a href="{{route('wallet')}}"><i class="fas fa-wallet"></i><span>@lang('messages.wallet')</span></a></li>

                            <li id="purchase-request"><a href="#modal-purchase-request" data-uk-toggle>
                                    <i class="fas fa-money-bill-wave"></i><span>@lang('messages.purchase_request')</span></a></li>
{{--                            <li id="payment-methods"><a href="{{ route('payments-page') }}"><i class="fas fa-credit-card"></i><span>@lang('messages.payment_methods')</span></a></li>--}}

                                <!-- Menu item to open the Create Ticket modal -->
                                <li id="create-ticket">
                                    <a href="#createTicketModal" data-uk-toggle>
                                        <i class="fas fa-ticket-alt"></i>
                                        <span>Tickets</span>
                                    </a>
                                </li>

                                <li id="partners"><a href="{{route('partners')}}"><i class="fas fa-id-badge"></i><span>@lang('messages.partners')</span></a></li>
                            <li id="posts"><a href="{{route('posts')}}"><i class="fas fa-tag"></i><span>@lang('messages.posts')</span></a></li>
                            @endif
                        @if(Auth::user()->is_external)
                            <li id="favourites"><a href="{{route('api')}}"><i class="fas fa-code"></i><span>@lang('messages.api.api')</span></a></li>
                        @endif
                        <li>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i><span>@lang('messages.logout')</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                        <hr />
                        <li class="social-container">
                            <a class="social-whats" href="https://wa.me/{{ $config->whatsapp }}?text={{ urlencode('Welcome to ' . str($config->name ?? "") ) }}" target="_blank">
                                <i class="fab fa-whatsapp" style="color: #25D366;"></i>
                            </a>
                            <a class="social-telegram" href="{{ $config->telegram }}" target="_blank">
                                <i class="fab fa-telegram" style="color: #0088cc;"></i>
                            </a>
                            <a  class="social-facebook" href="{{ $config->facebook }}" target="_blank">
                                <i class="fab fa-facebook" style="color: #1877F2;"></i>
                            </a>
                        </li>
                    @endauth
                </ul>

            </div>
        </aside>

        @yield('content')
    </div>
</div>

<div class="page-modals">
    <div class="uk-flex-top" id="modal-report" data-uk-modal>
        <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical">
            <button class="uk-modal-close-default" type="button" data-uk-close></button>
            <h2 class="uk-modal-title">Report</h2>
            <form class="uk-form-stacked" action="#">
                <div class="uk-margin">
                    <div class="uk-form-label">Subject</div>
                    <div class="uk-form-controls"><select class="js-select">
                            <option value="">Choose Subject</option>
                            <option value="Subject 1">Subject 1</option>
                            <option value="Subject 2">Subject 2</option>
                            <option value="Subject 3">Subject 3</option>
                        </select></div>
                </div>
                <div class="uk-margin">
                    <div class="uk-form-label">Details</div>
                    <div class="uk-form-controls"><textarea class="uk-textarea" name="details" placeholder="Try to include all details..."></textarea></div>
                    <div class="uk-form-controls uk-margin-small-top">
                        <div data-uk-form-custom><input type="file"><button class="uk-button uk-button-default" type="button" tabindex="-1"><i class="ico_attach-circle"></i><span>Attach File</span></button></div>
                    </div>
                </div>
                <div class="uk-margin">
                    <div class="uk-grid uk-flex-right" data-uk-grid>
                        <div><button class="uk-button uk-button-small uk-button-link">Cancel</button></div>
                        <div><button class="uk-button uk-button-small uk-button-danger">Submit</button></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="uk-flex-top" id="modal-purchase-request" data-uk-modal>
        <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical">
            <button class="uk-modal-close-default" type="button" data-uk-close></button>
            <h2 class="uk-modal-title">@lang('messages.purchase_request_modal.purchase_request')</h2>
            <form id="purchaseRequestForm" method="POST" action="{{ route('purchase.request') }}" enctype="multipart/form-data">
                @csrf
                <div class="uk-margin">
                    <label class="uk-form-label" for="payment_method_id">@lang('messages.purchase_request_modal.payment_method')</label>
                    <div class="uk-form-controls">
                        <select class="uk-select" id="payment_method_id" name="payment_method_id">
                            @foreach($paymentMethods as $paymentMethod)
                                <option value="{{ $paymentMethod->id }}"
                                        data-image="{{ $paymentMethod->getFirstMediaUrl('payment_method_images') }}"
                                        data-description="{{ App::getLocale() == 'ar' ? $paymentMethod->ar_description : $paymentMethod->description }}">
                                    @if(App::getLocale() == 'ar')
                                        {{ $paymentMethod->ar_gateway ?? $paymentMethod->gateway }}
                                    @else
                                        {{ $paymentMethod->gateway }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <!-- Area to display selected payment method's media and description -->
                        <div id="payment_method_details" style="display: none;margin-top: 10px">
                            <div id="payment_method_image"></div>
                            <div style="position: relative;">
                                <textarea id="payment_method_description" readonly class="uk-textarea" style=""></textarea>
                                <button id="copy_description" type="button" class="uk-button uk-button-default uk-button-small" title="Copy">
                                    <span uk-icon="copy" id="copy_payment_icon"></span>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="uk-margin">
                    <label class="uk-form-label" for="amount">@lang('messages.purchase_request_modal.amount')</label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="amount" name="amount" type="number" step="0.01" placeholder="@lang('messages.purchase_request_modal.enter_amount')">
                    </div>
                </div>
                <div class="uk-margin">
                    <label class="uk-form-label" for="notes">@lang('messages.purchase_request_modal.notes')</label>
                    <div class="uk-form-controls">
                        <textarea class="uk-textarea" id="notes" name="notes" rows="3" placeholder="@lang('messages.purchase_request_modal.enter_notes')"></textarea>
                    </div>
                </div>
                <div class="uk-margin" style="text-align: center">
                    <label class="uk-form-label" for="image">@lang('messages.purchase_request_modal.upload_image')</label>
                    <input class="" id="image" name="image" type="file" accept="image/*">
                </div>
                <div class="uk-margin">
                    <button type="submit" class="uk-button uk-button-primary uk-width-1-1">@lang('messages.purchase_request_modal.submit')</button>
                </div>
            </form>
            <div id="form-messages"></div>
        </div>
    </div>




    <!-- Create Ticket Modal -->
    <div id="createTicketModal" class="uk-flex-top" uk-modal>
        <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="uk-modal-title">Tickets</h2>

            <!-- Tabs for Create Ticket and List Tickets -->
            <ul class="uk-tab custom-tab-links" data-uk-tab>
                <li class="active"><a href="#" data-tab="create-ticket-tab">Create Ticket</a></li>
                <li><a href="#" data-tab="my-tickets-tab">My Tickets</a></li>
            </ul>

            <!-- Tab Content -->
            <div class="uk-tab-content custom-tab-content" style="padding: 0">
                <!-- Create Ticket Tab Content -->
                <div id="create-ticket-tab" class="tab-content active">
                    <form id="create-ticket-form" action="{{ route('user-create-ticket') }}" method="POST" enctype="multipart/form-data" >
                        @csrf

                        <div class="uk-margin">
                            <label for="ticket_category_id" class="uk-form-label">Category</label>
                            <div class="uk-form-controls">
                                <select class="uk-select" name="ticket_category_id" id="ticket_category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="uk-margin">
                            <label for="message" class="uk-form-label">Message</label>
                            <div class="uk-form-controls">
                                <textarea class="uk-textarea" id="message" name="message" rows="3" required></textarea>
                            </div>
                        </div>

                        <div class="uk-margin">
                            <label for="image" class="uk-form-label">Upload Image</label>
                            <div class="uk-form-controls">
                                <input class="uk-input" id="image" name="image" type="file" accept="image/*">
                                <div id="image-preview" class="mt-3"></div> <!-- Preview image -->
                            </div>
                        </div>

                        <input type="hidden" name="user_id" value="{{ \Illuminate\Support\Facades\Auth::user()?->id }}">

                        <div class="uk-margin">
                            <button type="submit" class="uk-button uk-button-primary uk-width-1-1">Create Ticket</button>
                        </div>
                    </form>
                </div>

                <!-- My Tickets Tab Content -->
                <div id="my-tickets-tab" class="tab-content">
                    <div id="tickets-list">
                        <p>Loading tickets...</p> <!-- Tickets will be dynamically loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="page-modals">
    <!-- Purchase Request Modal -->
    <div class="uk-flex-top" id="modal-purchase-request" data-uk-modal>
        <!-- Your existing modal content -->
    </div>

    <!-- Notification Modal -->
    @if(isset($latestUnreadNotification))
        <div class="uk-flex-top" id="modal-notification" data-uk-modal>
            <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical" style="text-align: center">
                <button class="uk-modal-close-default" type="button" data-uk-close></button>
                <h2 class="uk-modal-title">Notifications</h2>
                <h4 style="margin-top: 10px;font-style: italic;">{{ $latestUnreadNotification->title }}</h4>
                <p>{{ $latestUnreadNotification->description }}</p>
                @if($latestUnreadNotification->getFirstMediaUrl('attachments'))
                    <img src="{{ $latestUnreadNotification->getFirstMediaUrl('attachments') }}" alt="Notification Image" style="max-width: 200px;">
                @endif
                <div class="uk-margin">
                    <button class="uk-button uk-button-danger uk-width-1-1" id="markAsRead" data-notification-id="{{ $latestUnreadNotification->id }}">Mark as Read</button>
                </div>
            </div>
        </div>
    @endif
</div>

<script src="{{ asset('assets/js/libs.js')}}"></script>
<script src="{{ asset('assets/js/main.js')}}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get current URL path
        const currentPath = window.location.pathname;

        // Map routes to their corresponding list item ids
        const routeMap = {
            '/': 'home',
            '/profile': 'profile',
            '/favourites': 'favourites',
            '/wallet': 'wallet',
            '/posts': 'posts',
            '/partners': 'partners',
            '/plans-page': 'plans',
            '/payments-page': 'payment-methods',
            '/register-page': 'register',
            '/register-business': 'admin-register',
            '/sign-in': 'login',
            '/business-sign-in': 'admin-login',
            '/business-wallet': 'business-wallet',
            '/business-profile': 'business-profile',
            '/dashboard': 'dashboard'
        };

        // Find the corresponding list item id for the current path
        const activeItemId = routeMap[currentPath];

        // If a corresponding list item id is found, add the uk-active class to it
        if (activeItemId) {
            document.getElementById(activeItemId).classList.add('uk-active');
        }
    });
</script>


<script>
    @if(isset($latestUnreadNotification))
    UIkit.modal('#modal-notification').show();
    @endif

        try {
        document.getElementById('markAsRead').addEventListener('click', function () {
            const notificationId = this.getAttribute('data-notification-id');
            fetch('{{ route('notifications.markAsRead', '') }}/' + notificationId, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ notification_id: notificationId })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        UIkit.modal('#modal-notification').hide();
                        toastr.success('Notification marked as read.');
                    } else {
                        toastr.error('Failed to mark notification as read.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error.message);
                    toastr.error('There was an error processing your request: ' + error.message);
                });
        });
    }
    catch (err){
            console.log(err.message)
        }

</script>

<script>
    $(document).ready(function() {
        // Function to update the payment method details
        function updatePaymentMethodDetails() {
            var selectedOption = $('#payment_method_id').find('option:selected');
            var imageUrl = selectedOption.data('image');
            var description = selectedOption.data('description');

            // Show the details section
            $('#payment_method_details').show();

            // Update the image
            if (imageUrl) {
                $('#payment_method_image').html('<img src="' + imageUrl + '" alt="Payment Method Image" style="max-width: 70px;border-radius: 50%">');
            } else {
                $('#payment_method_image').html('');
            }

            // Update the description
            $('#payment_method_description').val(description ? description : 'No description available');

        }

        // Trigger on page load to show the details of the initially selected option
        updatePaymentMethodDetails();

        // Update the details when a different payment method is selected
        $('#payment_method_id').on('change', function() {
            updatePaymentMethodDetails();
        });

        $('#copy_description').on('click', function () {
            const messages = {
                copy_clipboard: "{{ __('messages.copy_clipboard') }}",
            };
            const textArea = document.getElementById('payment_method_description');
            textArea.select(); // Select the text
            document.execCommand('copy');
            toastr.success(messages.copy_clipboard);
        });
    });
</script>

<script>
    $(document).ready(function() {
        // Switch tab content manually
        $('.custom-tab-links a').click(function(e) {
            e.preventDefault();

            // Remove 'active' class from all tabs and tab contents
            $('.custom-tab-links li').removeClass('active');
            $('.tab-content').removeClass('active');

            // Add 'active' class to the clicked tab and the corresponding tab content
            $(this).parent().addClass('active');
            var tabContent = $(this).data('tab');
            $('#' + tabContent).addClass('active');
        });

        // Image preview before uploading
        $('#image').change(function() {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#image-preview').html('<img src="' + e.target.result + '" alt="Image Preview" style="max-width: 200px; max-height: 150px;">');
            }
            reader.readAsDataURL(this.files[0]);
        });

        // Handle form submission with AJAX for creating ticket
        $('#create-ticket-form').on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            var formData = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    UIkit.modal('#createTicketModal').hide();
                    $('#create-ticket-form')[0].reset(); // Reset the form
                    $('#image-preview').html(''); // Reset image preview
                    toastr.success('Your Ticket Sent Successfully');
                },
                error: function(response) {
                    UIkit.modal('#createTicketModal').hide();
                    $('#create-ticket-form')[0].reset(); // Reset the form
                    $('#image-preview').html(''); // Reset image preview
                    toastr.error('There was an error creating the ticket')
                }
            });
        });

        // When the "My Tickets" tab is clicked, fetch and display tickets
        $('ul.custom-tab-links li:nth-child(2) a').on('click', function(e) {
            e.preventDefault();

            // Clear the loading message
            $('#tickets-list').html('<p>Loading tickets...</p>');

            // Fetch tickets via AJAX (GET request)
            $.ajax({
                url: '{{ route('user-tickets') }}',
                method: 'GET', // Use GET request to fetch tickets
                success: function(response) {
                    // Clear the loading message
                    $('#tickets-list').html('');

                    if (response.length > 0) {
                        // Iterate through the tickets and display them
                        response.forEach(function(ticket) {
                            var ticketHtml = '<div class="ticket">';
                            ticketHtml += '<h4>Ticket #'+ ticket.id +'</h4>';
                            ticketHtml += '<p><strong>Category:</strong> ' + ticket.category.name + '</p>'; // Category ID
                            ticketHtml += '<p><strong>Message:</strong> ' + ticket.message + '</p>';
                            ticketHtml += '<p><strong>Status:</strong> ' + ticket.status + '</p>';
                            ticketHtml += '<p><strong>Response:</strong> ' + (ticket.response ? ticket.response : 'No response yet.') + '</p>';
                            ticketHtml += '</div>';

                            $('#tickets-list').append(ticketHtml);
                        });
                    } else {
                        $('#tickets-list').html('<p>No tickets found.</p>');
                    }
                },
                error: function() {
                    $('#tickets-list').html('<p>Error loading tickets.</p>');
                }
            });
        });
    });
</script>

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
@endsection


@yield('scripts')

</body>

</html>
