<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Document')</title>
    <meta content="Ske E-Commerce" name="author">
    <meta content="Ske E-Commerce" name="description">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="HandheldFriendly" content="true">
    <meta name="format-detection" content="telephone=no">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png')}}" type="image/x-icon">
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

    <!-- Conditionally include Oswald font if selected -->
    @if($config->font == 'Oswald')
        <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <style>
            body, h1,h2,h3,h4,h5,h6, p, div, a {
                font-family: 'Oswald', sans-serif !important;
            }
        </style>
    @elseif($config->font == 'Noto Sans')
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;700&display=swap" rel="stylesheet">
        <style>
            body, h1,h2,h3,h4,h5,h6, p, div, a {
                font-family: 'Noto Sans', sans-serif !important;
            }
        </style>
    @elseif($config->font == 'Raleway')
        <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <style>
            body, h1,h2,h3,h4,h5,h6, p, div, a {
                font-family: 'Raleway', sans-serif !important;
            }
        </style>
    @elseif($config->font == 'Roboto')
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
        <style>
            body, h1,h2,h3,h4,h5,h6, p, div, a {
                font-family: 'Roboto', sans-serif !important;
            }
        </style>
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
                <div class="page-header__menu-btn"><button class="menu-btn ico_menu is-active"></button></div>
                <div class="page-header__logo">
                    <a href="{{route('home')}}">
                        @if($config->getFirstMediaUrl('logos'))
                            <img src="{{ $config->getFirstMediaUrl('logos') }}" alt="logo" style="">
                        @else
                            <img src="{{ asset('assets/img/logo.png')}}" alt="logo">
                        @endif
{{--                        <span class="page-header__logo_text">{{$config->name}}</span>--}}
                    </a>
                </div>
            </div>
            <div class="page-header__content">
                <div class="page-header__search1"></div>
                <div class="page-header__action">
                    @auth('business_client')
                        <a class="profile" href="{{route('business-wallet')}}" style="margin-right: 40px;">
                            <div class="activities-item__price">
                                {{\Illuminate\Support\Facades\Auth::user()->wallet->balance}} {{ $user->currency->currency ?? "USD" }}
                            </div>
                        </a>
                        <a class="profile" href="{{route('business-profile')}}" style="margin-right: 40px;">
                            <img src="{{ asset('assets/img/profile.png')}}" alt="profile">
                        </a>
                    @elseauth('web')
                        <a class="profile" href="{{route('wallet')}}" style="margin-right: 40px;">
                            <div class="activities-item__price">
                                {{\Illuminate\Support\Facades\Auth::user()->wallet->balance}} {{ $user->currency->currency ?? "USD" }}
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
                </div>
            </div>
        </div>
    </header>
    <div class="page-content">

        <aside class="sidebar is-show" id="sidebar">
            <input id="toggle" type="checkbox">
            <div class="sidebar-box">
                <ul class="uk-nav">
                    @if(Auth::guard('web')->guest() && Auth::guard('business_client')->guest())
                        <li id="login"><a href="{{ route('sign-in') }}"><i class="fas fa-sign-in-alt"></i><span>Login</span></a></li>
                        <li id="register"><a href="{{ route('register-page') }}"><i class="fas fa-user-plus"></i><span>Register</span></a></li>
{{--                        <li id="admin-login"><a href="{{ route('business-sign-in') }}"><i class="fas fa-sign-in-alt"></i><span>Admin Login</span></a></li>--}}
{{--                        <li id="admin-register"><a href="{{ route('register-business') }}"><i class="fas fa-user-plus"></i><span>Register as Admin</span></a></li>--}}
                    @endif

                    @auth('business_client')
                        <li id="business-wallet"><a href="{{route('business-wallet')}}"><i class="ico_wallet"></i><span>Wallet</span></a></li>
                        <li id="business-profile"><a href="{{route('business-profile')}}"><i class="ico_profile"></i><span>Profile</span></a></li>
                        <li id="plans"><a href="{{ route('plans-page') }}"><i class="fas fa-box-open"></i><span>Plans</span></a></li>
                        <li>
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form-business').submit();">
                                <i class="fas fa-sign-out-alt"></i><span>Logout</span>
                            </a>
                            <form id="logout-form-business" action="{{ route('business-logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    @elseauth('web')
                        @if(Auth::user()->hasRole('Admin'))
                            <li id="dashboard"><a href="{{route('dashboard')}}"><i class="ico_profile"></i><span>Admin Dashboard</span></a></li>
                        @else
                            <li id="home"><a href="{{route('home')}}"><i class="ico_store"></i><span>Home</span></a></li>
                            <li id="profile"><a href="{{route('profile')}}"><i class="ico_profile"></i><span>Profile</span></a></li>
                            <li id="favourites"><a href="{{route('favourites')}}"><i class="ico_favourites"></i><span>Favourites</span><span class="count">{{$favoritesCount}}</span></a></li>
                            <li id="wallet"><a href="{{route('wallet')}}"><i class="ico_wallet"></i><span>Wallet</span></a></li>
                            <li id="posts"><a href="{{route('posts')}}"><i class="fa fa-tag pr8"></i><span>Posts</span></a></li>
                            <li id="purchase-request"><a href="#modal-purchase-request" data-uk-toggle><i class="fas fa-money-bill-wave pr8" style="font-size: 16px;"></i><span>Purchase Request</span></a></li>
                            <li id="payment-methods"><a href="{{ route('payments-page') }}"><i class="fas fa-credit-card pr8"></i><span>Payment Methods</span></a></li>
                        @endif
                        <li>
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt pr8"></i><span>Logout</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                        <hr />
                        <li style="display: flex; justify-content: center; align-items: center;margin-top: 80px">
                            <a href="https://wa.me/{{ $config->whatsapp }}?text={{ urlencode('Welcome to ' . str($config->name ?? "") ) }}" target="_blank" style="text-decoration: none; margin-right: 15px;">
                                <i class="fab fa-whatsapp" style="font-size: 22px; color: #25D366;"></i>
                            </a>
                            <a href="{{ $config->telegram }}" target="_blank" style="text-decoration: none; margin-right: 15px;">
                                <i class="fab fa-telegram" style="font-size: 22px; color: #0088cc;"></i>
                            </a>
                            <a href="{{ $config->facebook }}" target="_blank" style="text-decoration: none;">
                                <i class="fab fa-facebook" style="font-size: 22px; color: #1877F2;"></i>
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
            <h2 class="uk-modal-title">Purchase Request</h2>
            <form id="purchaseRequestForm" method="POST" action="{{ route('purchase.request') }}" enctype="multipart/form-data">
                @csrf
                <div class="uk-margin">
                    <label class="uk-form-label" for="payment_method_id">Payment Method</label>
                    <div class="uk-form-controls">
                        <select class="uk-select" id="payment_method_id" name="payment_method_id">
                            @foreach($paymentMethods as $paymentMethod)
                                <option value="{{ $paymentMethod->id }}">{{ $paymentMethod->gateway }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="uk-margin">
                    <label class="uk-form-label" for="amount">Amount</label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="amount" name="amount" type="number" step="0.01" placeholder="Enter amount">
                    </div>
                </div>
                <div class="uk-margin">
                    <label class="uk-form-label" for="notes">Notes</label>
                    <div class="uk-form-controls">
                        <textarea class="uk-textarea" id="notes" name="notes" rows="3" placeholder="Enter notes"></textarea>
                    </div>
                </div>
                <div class="uk-margin" style="text-align: center">
                    <label class="uk-form-label" for="image">Upload Image</label>
                    <input class="" id="image" name="image" type="file" accept="image/*">
                </div>
                <div class="uk-margin">
                    <button type="submit" class="uk-button uk-button-primary uk-width-1-1">Submit</button>
                </div>
            </form>
            <div id="form-messages"></div>
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

<footer class="page-footer" style="padding: 20px 0 5px 0;text-align: center">
    <div class="container text-center">
        <p class="mt-2" style="margin-bottom: 5px">&copy;
            <script>document.write(new Date().getFullYear())</script>
            {{ $config->name }} All Rights Reserved.</p>
        <a href="{{ route('terms-page') }}">Terms & Conditions</a>
    </div>
</footer>

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
</script>

@yield('scripts')

</body>

</html>
