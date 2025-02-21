<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <title>{{ $config->name ?? '' }} - @lang('messages.auth.register')</title>
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
    @if(app()->getLocale() === 'ar')
        <link rel="stylesheet" href="{{ asset('assets/css/main-ar.css') }}">
    @endif
</head>
<body class="page-login">
<!-- Modal -->
<div id="googleModal" class="uk-modal" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title">Enter Your Details</h2>
        <form id="googleRegisterForm">
            <div class="uk-margin">
                <label for="modal_currency">Currency</label>
                <select class="uk-select" id="modal_currency" name="currency">
                    @foreach($currencies as $currency)
                        <option value="{{ $currency->id }}">{{ $currency->currency }}</option>
                    @endforeach
                </select>
            </div>

            <div class="uk-margin">
                <label for="modal_country">Country</label>
                <select class="uk-select" id="modal_country"></select>
            </div>

            <div class="uk-margin">
                <label for="modal_whatsapp">WhatsApp Number</label>
                <input type="text" class="uk-input" id="modal_whatsapp" name="whatsapp" placeholder="Enter your WhatsApp number">
            </div>

            <div class="uk-margin uk-text-right">
                <button type="button" class="uk-button uk-button-default uk-modal-close">Cancel</button>
                <button type="button" class="uk-button uk-button-primary" id="confirmGoogleLogin">Confirm</button>
            </div>
        </form>
    </div>
</div>

<div class="page-wrapper">
    <main class="page-first-screen">
        <div class="uk-grid uk-grid-small uk-child-width-1-2@s uk-flex-middle uk-width-1-1" data-uk-grid>
            <div class="logo-big">
                @if($config->getFirstMediaUrl('logos'))
                    <img class="animation-navspinv" src="{{ $config->getFirstMediaUrl('logos') }}" alt="logo">
                @else
                    <img class="animation-navspinv" src="{{ asset('assets/img/logo.png') }}" alt="logo">
                @endif
                <h2 class="head-login-desc">{{ $config->description ?? '' }}</h2>
            </div>
            <div>
                <div class="form-login">
                    <div class="form-login__social">
                        <ul class="social">
                            <li><a id="google-register" href="{{ route('google.login') }}"><img src="{{ asset('assets/img/google.svg') }}" alt="google"></a></li>
                        </ul>
                    </div>
                    <div class="form-login__box">
                        <div class="uk-heading-line uk-text-center"><span>@lang('messages.auth.or_with_email')</span></div>
                        <form id="registrationForm" action="{{ route('register') }}" method="POST">
                            @csrf
                            <div class="uk-margin">
                                <input class="uk-input" type="text" name="name" placeholder="@lang('messages.auth.name_placeholder')">
                                <div class="uk-text-danger" id="error-name"></div>
                            </div>
                            <div class="uk-margin">
                                <input class="uk-input" type="email" name="email" placeholder="@lang('messages.auth.email_placeholder')">
                                <div class="uk-text-danger" id="error-email"></div>
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
                                    <select class="uk-select custom-select" name="country" id="country"></select>
                                </div>
                                <div class="uk-text-danger" id="error-country"></div>
                            </div>
                            <div class="uk-margin">
                                <input class="uk-input" type="text" name="phone" placeholder="@lang('messages.auth.phone_placeholder')">
                                <div class="uk-text-danger" id="error-phone"></div>
                            </div>
                            <div class="uk-margin">
                                <input class="uk-input" type="password" name="password" placeholder="@lang('messages.auth.password_placeholder')">
                                <div class="uk-text-danger" id="error-password"></div>
                            </div>
                            <div class="uk-margin">
                                <input class="uk-input" type="password" name="password_confirmation" placeholder="@lang('messages.auth.confirm_password_placeholder')">
                                <div class="uk-text-danger" id="error-password_confirmation"></div>
                            </div>
                            <div class="uk-margin">
                                <button class="uk-button uk-button-danger uk-width-1-1" type="submit">@lang('messages.auth.register_button')</button>
                            </div>
                            <div class="uk-text-center">
                                <span>@lang('messages.auth.already_have_account')</span>
                                <a class="uk-margin-small-left" href="{{ route('sign-in') }}">@lang('messages.auth.login_button')</a>
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
            var $countrySelect = $('#country');

            $.each(data, function(key, entry) {
                $countrySelect.append($('<option></option>').attr('value', entry.name).text(entry.name));
            });

            var $countryModSelect = $('#modal_country');
            $.each(data, function(key, entry) {
                $countryModSelect.append($('<option></option>').attr('value', entry.name).text(entry.name));
            });
        });

        // Show modal on Google Register click
        $('#google-register').click(function(event) {
            event.preventDefault(); // Prevent immediate redirect
            UIkit.modal('#googleModal').show();
        });

        /*
        // Handle confirm button inside modal
        $('#confirmGoogleLogin').click(function() {
            let currency = $('#modal_currency').val();
            let country = $('#modal_country').val();
            let phone = $('#modal_whatsapp').val();

            console.log("phone", phone)
            console.log("currency", currency)
            console.log("country", country)

            if (!currency || !country || !phone) {
                toastr.error("Please fill in all fields before continuing.");
                return;
            }

            // Redirect to Google login with query parameters
            let googleUrl = "{{ route('google.login') }}" +
                `?currency=${encodeURIComponent(currency)}&country=${encodeURIComponent(country)}&phone=${encodeURIComponent(phone)}`;

            window.location.href = googleUrl;
        });*/

        $('#confirmGoogleLogin').click(function() {
            let currency = $('#modal_currency').val();
            let country = $('#modal_country').val();
            let phone = $('#modal_whatsapp').val();

            if (!currency || !country || !phone) {
                toastr.error("Please fill in all fields before continuing.");
                return;
            }

            // Redirect to Google login with query parameters for registration
            let googleRegisterUrl = "{{ route('google.register') }}" +
                `?currency=${encodeURIComponent(currency)}&country=${encodeURIComponent(country)}&phone=${encodeURIComponent(phone)}`;

            window.location.href = googleRegisterUrl;
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
    });
</script>
<script src="{{ asset('assets/js/libs.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>

</body>
</html>
