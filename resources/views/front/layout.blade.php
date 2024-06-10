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

<input id="toggle" type="checkbox">
<script type="text/javascript">
    document.getElementById("toggle").addEventListener("click", function() {
        document.getElementsByTagName('body')[0].classList.toggle("dark-theme");
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
                        purchaseRequestForm.reset();
                        imagePreview.src = '';
                        setTimeout(() => {
                            UIkit.modal('#modal-purchase-request').hide();
                        }, 2000); // Hide the modal after 2 seconds
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
                <div class="page-header__logo"><img src="{{ asset('assets/img/logo.png')}}" alt="logo"><span class="page-header__logo_text">{{$config->name}}</span></div>
            </div>
        </div>
    </header>
    <div class="page-content">
        <aside class="sidebar is-show" id="sidebar">
            <div class="sidebar-box">
                <ul class="uk-nav">
                    <li class="uk-active"><a href="{{route('home')}}"><i class="ico_store"></i><span>Home</span></a></li>
                    <li><a href="{{route('profile')}}"><i class="ico_profile"></i><span>Profile</span></a></li>
                    <li><a href="{{route('favourites')}}"><i class="ico_favourites"></i><span>Favourites</span><span class="count">{{$favoritesCount}}</span></a></li>
                    <li><a href="06_chats.html"><i class="ico_chats"></i><span>Chats</span></a></li>
                    <li><a href="07_friends.html"><i class="ico_friends"></i><span>Friends</span></a></li>
                    <li><a href="{{route('wallet')}}"><i class="ico_wallet"></i><span>Wallet</span></a></li>
                    <li><a href="{{ route('plans-page') }}"><i class="fas fa-box-open"></i><span>Plans</span></a></li>
                    <li><a href="#modal-report" data-uk-toggle><i class="ico_report"></i><span>Report</span></a></li>
                    <li><a href="#modal-purchase-request" data-uk-toggle><i class="fas fa-money-bill-wave" style="font-size: 20px;"></i><span>Purchase Request</span></a></li>
                    <li><a href="{{ route('payments-page') }}"><i class="fas fa-credit-card"></i><span>Payment Methods</span></a></li>

                    <li style="display: flex; justify-content: center; align-items: center;">
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
                </ul>


            </div>
        </aside>

        @yield('content')
    </div>
</div>
<div class="page-modals">
    <div class="uk-flex-top" id="modal-report" data-uk-modal>
        <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical"><button class="uk-modal-close-default" type="button" data-uk-close></button>
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

<script src="{{ asset('assets/js/libs.js')}}"></script>
<script src="{{ asset('assets/js/main.js')}}"></script>


</body>

</html>
