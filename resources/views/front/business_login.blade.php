<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title> {{ $config->name ?? ""  }} - Login</title>
    <meta name="author" content="Templines">
    <meta name="description" content="TeamHost">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="HandheldFriendly" content="true">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/css/libs.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Marcellus&display=swap" rel="stylesheet">
</head>
<body class="page-login">
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

<div class="page-wrapper">
    <main class="page-first-screen">
        <div class="uk-grid uk-grid-small uk-child-width-1-2@s uk-flex-middle uk-width-1-1" data-uk-grid>
            <div class="logo-big">
                @if($config->getFirstMediaUrl('logos'))
                    <img class="animation-navspinv" src="{{ $config->getFirstMediaUrl('logos') }}" alt="logo">
                @else
                    <img class="animation-navspinv" src="{{ asset('assets/img/logo.png')}}" alt="logo">
                @endif
                <span>{{$config->name ?? "Company Name"}}</span>
                <h1>Join now and play mighty games!</h1>
            </div>
            <div>
                <div class="form-login">


                    <div class="form-login__box">
                        <form action="{{ route('business-login') }}" method="POST">
                            @csrf
                            <div class="uk-margin">
                                <input class="uk-input" type="text" name="email" placeholder="Email" value="{{ old('email') }}">
                                @error('email')
                                <div class="uk-text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="uk-margin">
                                <input class="uk-input" type="password" name="password" placeholder="Password">
                                @error('password')
                                <div class="uk-text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="uk-margin">
                                <button class="uk-button uk-button-danger uk-width-1-1" type="submit">Log In</button>
                            </div>
                            <hr>
                            <div class="uk-text-center">
                                <span>Don’t have an account?</span>
                                <a class="uk-margin-small-left" href="{{ route('register-business') }}">Register</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="{{ asset('assets/js/libs.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
</body>
</html>
