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
                    <li><a href="03_home.html"><i class="ico_home"></i><span>Home</span></a></li>
                    <li><a href="04_profile.html"><i class="ico_profile"></i><span>Profile</span></a></li>
                    <li><a href="05_favourites.html"><i class="ico_favourites"></i><span>Favourites</span><span class="count">15</span></a></li>
                    <li><a href="06_chats.html"><i class="ico_chats"></i><span>Chats</span></a></li>
                    <li><a href="07_friends.html"><i class="ico_friends"></i><span>Friends</span></a></li>
                    <li><a href="08_wallet.html"><i class="ico_wallet"></i><span>Wallet</span></a></li>
                    <li class="uk-active"><a href="09_games-store.html"><i class="ico_store"></i><span>Store</span></a></li>
                    <li><a href="11_market.html"><i class="ico_market"></i><span>Market</span></a></li>
                    <li><a href="12_streams.html"><i class="ico_streams"></i><span>Streams</span></a></li>
                    <li><a href="13_community.html"><i class="ico_community"></i><span>Community</span></a></li>
                    <li><a href="#modal-report" data-uk-toggle><i class="ico_report"></i><span>Report</span></a></li>
                    <li><a href="#modal-help" data-uk-toggle><i class="ico_help"></i><span>Help</span></a></li>
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
    <div class="uk-flex-top" id="modal-help" data-uk-modal>
        <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical"><button class="uk-modal-close-default" type="button" data-uk-close></button>
            <h2 class="uk-modal-title">Help</h2>
            <div class="search">
                <div class="search__input"><i class="ico_search"></i><input type="search" name="search" placeholder="Search"></div>
                <div class="search__btn"><button type="button"><i class="ico_microphone"></i></button></div>
            </div>
            <div class="uk-margin-small-left uk-margin-small-bottom uk-margin-medium-top">
                <h4>Popular Q&A</h4>
                <ul>
                    <li><img src="{{ asset('assets/img/svgico/clipboard-text.svg')}}" alt="icon"><span>How to Upload Your Developed Game</span></li>
                    <li><img src="{{ asset('assets/img/svgico/clipboard-text.svg')}}" alt="icon"><span>How to Go Live Stream</span></li>
                    <li><img src="{{ asset('assets/img/svgico/clipboard-text.svg')}}" alt="icon"><span>Get in touch with the Creator Support Team</span></li>
                </ul>
                <ul>
                    <li><a href="#!">browse all articles</a></li>
                    <li><a href="#!">Send Feedback</a></li>k
                </ul>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/libs.js')}}"></script>
<script src="{{ asset('assets/js/main.js')}}"></script>
</body>

</html>
