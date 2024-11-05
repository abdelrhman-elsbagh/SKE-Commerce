@extends('front.layout')

@section('title', 'API - Credentials')

@section('content')
    <main class="page-main">
        <div class="uk-container uk-margin-large-top">
            <div class="uk-card uk-card-default uk-card-body uk-width-1-2@m uk-align-center">
                <h3 class="uk-card-title">API Credentials</h3>
                <form id="api-form">
                    <div class="uk-margin">
                        <input class="uk-input uk-width-1-1" id="domain" name="domain" type="text" placeholder="Domain">
                    </div>
                    <div class="uk-margin">
                        <div class="uk-inline uk-width-1-1">
                            <a class="uk-form-icon uk-form-icon-flip" id="toggle-secret-key" href="#" uk-icon="icon: eye"></a>
                            <input class="uk-input uk-width-1-1" id="secret_key" name="secret_key" type="password" placeholder="Secret Key">
                        </div>
                    </div>
                    <div class="uk-margin">
                        <textarea class="uk-textarea uk-width-1-1" id="result" rows="5" placeholder="Result will appear here..."></textarea>
                    </div>
                    <div class="uk-margin">
                        <button class="uk-button uk-button-primary uk-width-1-1" type="button" id="submit-button">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        $(document).ready(function() {
            let isHidden = true;

            // Toggle show/hide secret key
            $('#toggle-secret-key').on('click', function(e) {
                e.preventDefault();
                let secretKeyInput = $('#secret_key');

                if (isHidden) {
                    // Show secret key
                    secretKeyInput.attr('type', 'text');
                    $(this).attr('uk-icon', 'icon: eye-slash'); // Change icon to eye-slash
                } else {
                    // Hide secret key
                    secretKeyInput.attr('type', 'password');
                    $(this).attr('uk-icon', 'icon: eye'); // Change icon back to eye
                }
                isHidden = !isHidden; // Toggle state
            });

            // Handle form submission via Axios
            $('#submit-button').on('click', function() {
                const domain = $('#domain').val();
                const secretKey = $('#secret_key').val();

                // Axios POST request
                axios.post('http://localhost:8002/api/fetch-items', {
                    source_key: secretKey,
                    domain: domain
                }, {
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                    .then(function (response) {
                        // Format and display the JSON result in the textarea
                        $('#result').val(JSON.stringify(response.data, null, 2));
                    })
                    .catch(function (error) {
                        // Handle errors, print in textarea
                        $('#result').val('Error: ' + error.message);
                        console.log(error);
                    });
            });
        });
    </script>
@endsection
