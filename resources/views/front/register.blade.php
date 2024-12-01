<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title> {{ $config->name ?? ""  }} - Register</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        :root {
            --main-color: {{ $mainColor ?? $config->main_color ?? '#F46119' }};
        }
    </style>

    <style>
        .js-select{
            width: 100% !important;
        }
        .custom-select-wrapper {
            position: relative;
            width: 100%;
        }



        .custom-tab li.uk-active a {
            color: var(--main-color) /* Active tab color */
        }

        .custom-tab li a:hover {
            color: var(--main-color); /* Hover effect on inactive tabs */
        }
    </style>

</head>
<body class="page-login">
<div class="page-wrapper">
    <main class="page-first-screen">
        <div class="uk-grid uk-grid-small uk-child-width-1-2@s uk-flex-middle uk-width-1-1" data-uk-grid>
            <div class="logo-big">
                @if($config->getFirstMediaUrl('logos'))
                    <img class="animation-navspinv" src="{{ $config->getFirstMediaUrl('logos') }}" alt="logo">
                @else
                    <img class="animation-navspinv" src="{{ asset('assets/img/logo.png')}}" alt="logo">
                @endif
                    <h2 class="head-login-desc">{{$config->description ?? ""}}</h2>
            </div>
            <div>
                <div class="form-login">
                    <div class="form-login__social">
                        <ul class="social">
                            <li><a href="http://www.google.com"><img src="{{ asset('assets/img/google.svg') }}" alt="google"></a></li>
                        </ul>
                    </div>
                    <div class="form-login__box">
                        <div class="uk-heading-line uk-text-center"><span>or with Email</span></div>
                        <form id="registrationForm" action="{{ route('register') }}" method="POST">
                            @csrf
                            <div class="uk-margin">
                                <input class="uk-input" type="text" name="name" placeholder="Name">
                                <div class="uk-text-danger" id="error-name"></div>
                            </div>
                            <div class="uk-margin">
                                <input class="uk-input" type="email" name="email" placeholder="Email">
                                <div class="uk-text-danger" id="error-email"></div>
                            </div>
                            <div class="uk-margin">
                                <input class="uk-input" type="text" name="phone" placeholder="Whatsapp Number">
                                <div class="uk-text-danger" id="error-phone"></div>
                            </div>
                            <div class="uk-margin">
                                <div class="custom-select-wrapper">
                                    <select class="uk-select custom-select" name="currency_id">
                                        @foreach($currencies as $currency)
                                            <option value="{{ $currency->id }}">{{ $currency->currency }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="uk-text-danger" id="error-currency_id"></div>
                            </div>

                            <div class="uk-margin">
                                <div class="custom-select-wrapper">
                                    <select class="uk-select custom-select country" name="country" id="country"></select>
                                </div>
                                <div class="uk-text-danger" id="error-country"></div>
                            </div>

                            <div class="uk-margin">
                                <input class="uk-input" type="password" name="password" placeholder="Password">
                                <div class="uk-text-danger" id="error-password"></div>
                            </div>
                            <div class="uk-margin">
                                <input class="uk-input" type="password" name="password_confirmation" placeholder="Confirm Password">
                                <div class="uk-text-danger" id="error-password_confirmation"></div>
                            </div>
                            <div class="uk-margin">
                                <button class="uk-button uk-button-danger uk-width-1-1" type="submit">Register</button>
                            </div>
                            <div class="uk-text-center">
                                <span>Already have an account?</span>
                                <a class="uk-margin-small-left" href="{{ route('sign-in') }}">Log In</a>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    $(document).ready(function() {
        // Load countries from JSON file
        $.getJSON('{{ asset("assets/countries.json") }}', function(data) {
            var $countrySelect = $('.country');

            $.each(data, function(key, entry) {
                $countrySelect.append($('<option></option>').attr('value', entry.name).text(entry.name));
            });
        });

        $('#registrationForm').submit(function(e) {
            e.preventDefault();
            $('div.uk-text-danger').empty(); // Clear previous errors

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(data) {
                    toastr.success('Registration successful!');
                    window.location.href = '/'; // Redirect to the home page
                },
                error: function(response) {
                    if (response.status === 422) { // Validation errors
                        const errors = response.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            $('#error-' + key).text(value[0]); // Set error text
                            toastr.error(value[0]);
                        });
                    } else {
                        toastr.error('An unexpected error occurred. Please try again.');
                    }
                }
            });
        });

        $('#partnerRegistrationForm').submit(function(e) {
            e.preventDefault();
            $('div.uk-text-danger').empty(); // Clear previous errors

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(data) {
                    toastr.success('Registration successful!');
                    window.location.href = '/'; // Redirect to the home page
                },
                error: function(response) {
                    if (response.status === 422) { // Validation errors
                        const errors = response.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            $('#error-' + key).text(value[0]); // Set error text
                            toastr.error(value[0]);
                        });
                    } else {
                        toastr.error('An unexpected error occurred. Please try again.');
                    }
                }
            });
        });
    });
</script>
<script src="{{ asset('assets/js/libs.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
