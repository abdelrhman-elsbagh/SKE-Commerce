<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <title>{{ $config->name ?? '' }} - @lang('messages.auth.login_title')</title>
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

    <style>
        :root {
            --main-color: {{ $mainColor ?? $config->main_color ?? '#F46119' }};
        }
    </style>

    @if(app()->getLocale() === 'ar')
        <link rel="stylesheet" href="{{ asset('assets/css/main-ar.css') }}">
    @endif
</head>
<body class="page-login">
<div id="page-preloader">
    <div class="preloader-1">
        <div class="loader-text">@lang('messages.loading')</div>
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
                <h2 class="head-login-desc">{{ $config->description ?? '' }}</h2>
            </div>
            <div>
                <div class="form-login">
                    <div class="form-login__social">
                        <ul class="social">
                            <li>
                                <a id="google-login" href="{{ route('google.login') }}">
                                    <img src="{{ asset('assets/img/google.svg') }}" alt="Login with Google">
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="form-login__box">
                        <div class="uk-heading-line uk-text-center"><span>@lang('messages.auth.or_with_email')</span></div>
                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="uk-margin">
                                <input class="uk-input" type="text" name="email" placeholder="@lang('messages.auth.email_placeholder')" value="{{ old('email') }}">
                                @error('email')
                                <div class="uk-text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="uk-margin">
                                <input class="uk-input" type="password" name="password" placeholder="@lang('messages.auth.password_placeholder')">
                                @error('password')
                                <div class="uk-text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="uk-margin">
                                <button class="uk-button uk-button-danger uk-width-1-1" type="submit">@lang('messages.auth.login_button')</button>
                            </div>
                            <hr>
                            <div class="uk-text-center">
                                <span>@lang('messages.auth.dont_have_account')</span>
                                <a class="uk-margin-small-left" href="{{ route('register-page') }}">@lang('messages.auth.register')</a>
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
