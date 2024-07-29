<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{$config->name  ?? "SKE" }} - Home</title>
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

<main class="page-main">
    <div class="uk-width-4-5@l uk-width-3-3@m uk-width-3-3@s uk-margin-auto" style="text-align: center;margin-top: 120px">

        <h3 class="uk-text-lead text-center" style="text-align: center;color: #646464;">This site requires an admin site to browse. Please contact the administration for support</h3>

        @if($config->getFirstMediaUrl('logos'))
            <img src="{{ $config->getFirstMediaUrl('logos') }}" alt="logo" style="border-radius: 8px;max-width: 300px;margin-top: 50px;">
        @else
            <img src="{{ asset('assets/img/logo.png')}}" alt="logo" style="border-radius: 8px;max-width: 300px;margin-top: 50px;">
        @endif

        <ul>
            <li style="display: flex; justify-content: center; align-items: center;margin-top: 80px">
                <a href="https://wa.me/{{ $config->whatsapp }}?text={{ urlencode('Welcome to ' . str($config->name ?? "") ) }}" target="_blank"
                   style="text-decoration: none; margin-right: 50px;">
                    <i class="fab fa-whatsapp" style="font-size: 28px; color: #25D366;"></i>
                </a>
                <a href="{{ $config->telegram }}" target="_blank" style="text-decoration: none; margin-right: 50px;">
                    <i class="fab fa-telegram" style="font-size: 28px; color: #0088cc;"></i>
                </a>
                <a href="{{ $config->facebook }}" target="_blank" style="text-decoration: none;">
                    <i class="fab fa-facebook" style="font-size: 28px; color: #1877F2;"></i>
                </a>
            </li>
        </ul>

        <div style="text-align: center">
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="text-decoration: underline;"><span>Logout</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</main>


<script src="{{ asset('assets/js/libs.js')}}"></script>
<script src="{{ asset('assets/js/main.js')}}"></script>
</body>

</html>

