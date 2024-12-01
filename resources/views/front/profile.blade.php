@extends('front.layout')

@section('title', ($config->name ?? "") . "- Profile" )

@section('content')
    <main class="page-main">
        <div class="uk-grid" data-uk-grid>
            <div class="uk-width-3-3@l">
                <div class="widjet --profile">
                    <div class="widjet__head">
                        <h3 class="uk-text-lead">Profile</h3>
                    </div>
                    <div class="widjet__body">
                        <div class="user-info">
                            <div class="user-info__avatar">
                                @if($user->getFirstMediaUrl('avatars'))
                                    <img src="{{ $user->getFirstMediaUrl('avatars') }}" alt="profile">
                                @else
                                    <img src="{{ asset('assets/img/profile.png') }}" alt="profile">
                                @endif
                            </div>
                            <div class="user-info__box">
                                <div class="user-info__title user-info__id">#{{ $user->id }}</div>
                                <div class="user-info__title">{{ $user->name }}</div>
                                <div class="user-info__text">{{ $user->address }}, Member since {{ $user->created_at->format('F Y') }}</div>
                                @if($user->is_external)
                                    <div class="user-info__text" style="font-size: 16px;color: #000;font-style: italic;margin-top: 10px;"> <span class="pr-3 main-color">Domain : </span> {{ $user->domain ?? "NA" }}</div>
                                    <div class="user-info__text" style="font-size: 16px;color: #000;font-style: italic;">
                                        <span class="pr-3 main-color">Secret-Key :</span>
                                        <span id="secret-key">{{ substr($user->secret_key, 0, 3) }}****</span> <!-- Show first 3 letters and hide the rest -->

                                        <button id="toggle-secret-key" class="btn btn-link main-color" style=" font-size: 14px; padding-left: 10px;">
                                            <i class="fas fa-eye"></i> <!-- FontAwesome eye icon -->
                                        </button>

                                        <button id="copy-secret-key" class="btn btn-link main-color" style="font-size: 14px;">
                                            <i class="fas fa-copy"></i> <!-- FontAwesome copy icon -->
                                        </button>
                                    </div>

                                @endif
                            </div>
                        </div>
                        @if($feeGroup)
                            <div class="fee-group-info" style="text-align: center">
                                @if($feeGroup->getFirstMediaUrl('logos'))
                                    <img src="{{ $feeGroup->getFirstMediaUrl('logos') }}" alt="Fee Group Image" style="max-width: 100px;">
                                @endif
                                <div class="fee-group-name">{{ $feeGroup->name }}</div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="widjet --bio">
                    <div class="widjet__head">
                        <h3 class="uk-text-lead">Bio</h3>
                    </div>
                    <div class="widjet__body"><span>{{ $user->bio ?? 'No bio available.' }}</span></div>
                </div>
                <div class="widjet --update-info">
                    <div class="widjet__head" style="display: flex; justify-content: space-between; align-items: center;">
                        <h3 class="uk-text-lead">Update Your Information</h3>
                        <button id="edit-icon" class="btn btn-warning" style="border: none; background: none; padding: 0;">
                            <i class="fas fa-edit fa-lg main-color"></i>
                        </button>
                    </div>
                    <div class="widjet__body">
                        <form id="update-info-form" action="{{ route('profile-update', $user->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="uk-margin">
                                <label for="name">Name</label>
                                <input class="uk-input" id="name" name="name" type="text" value="{{ $user->name }}" disabled>
                            </div>
                            <div class="uk-margin">
                                <label for="email">Email</label>
                                <input class="uk-input" id="email" name="email" type="email" value="{{ $user->email }}" disabled>
                            </div>
                            <div class="uk-margin">
                                <label for="password">Password</label>
                                <input class="uk-input" id="password" name="password" type="password" disabled>
                            </div>
                            <div class="uk-margin">
                                <label for="confirm_password">Confirm Password</label>
                                <input class="uk-input" id="confirm_password" name="confirm_password" type="password" disabled>
                            </div>
                            <div class="uk-margin">
                                <label for="bio">Bio</label>
                                <textarea class="uk-textarea" id="bio" name="bio" disabled>{{ $user->bio }}</textarea>
                            </div>
                            <div class="uk-margin">
                                <label for="address">Country</label>
                                <select class="uk-select" id="address" name="address" disabled>
                                    <option value="">Select Country</option>
                                    <!-- Countries will be loaded here by JavaScript -->
                                </select>
                            </div>
                            <div class="uk-margin">
                                <label for="date_of_birth">Date of Birth</label>
                                <input class="uk-input" id="date_of_birth" name="date_of_birth" type="date" value="{{ $user->date_of_birth }}" disabled>
                            </div>
                            <div class="uk-margin">
                                <label for="image">Profile Image</label>
                                <input class="uk-input" id="image" name="avatar" type="file" disabled>
                            </div>
                            <div class="uk-margin">
                                <button class="uk-button uk-button-primary" type="submit" id="submit-button" disabled>Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @if(session('success'))
        <script>
            toastr.success("{{ session('success') }}");
        </script>
    @endif
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            let secretKeyFull = '{{ $user->secret_key ?? "NA" }}'; // Full secret key
            let secretKeyHidden = secretKeyFull.substr(0, 3) + '****'; // First 3 letters + ****
            let isHidden = true; // Track whether the key is hidden

            // Toggle show/hide secret key
            $('#toggle-secret-key').on('click', function(e) {
                e.preventDefault();
                let secretKey = $('#secret-key');
                let toggleIcon = $(this).find('i'); // FontAwesome icon

                if (isHidden) {
                    // Show the full secret key
                    secretKey.text(secretKeyFull);
                    toggleIcon.removeClass('fa-eye').addClass('fa-eye-slash'); // Change icon to eye-slash
                } else {
                    // Hide the secret key (show first 3 letters + ****)
                    secretKey.text(secretKeyHidden);
                    toggleIcon.removeClass('fa-eye-slash').addClass('fa-eye'); // Change icon back to eye
                }
                isHidden = !isHidden; // Toggle the state
            });

            // Copy secret key to clipboard
            $('#copy-secret-key').on('click', function() {
                // Create a temporary input element to copy text
                let tempInput = $('<input>');
                $('body').append(tempInput);
                tempInput.val(secretKeyFull).select(); // Set and select the full secret key
                document.execCommand("copy"); // Copy to clipboard
                tempInput.remove(); // Remove the temporary input

                // Show a notification or feedback to user
                toastr.success('Secret key copied to clipboard!');
            });

            // Load countries from JSON file
            $.getJSON('{{ asset("assets/countries.json") }}', function(data) {
                var $countrySelect = $('#address');
                $.each(data, function(key, entry) {
                    $countrySelect.append($('<option></option>').attr('value', entry.name).text(entry.name));
                });
                $countrySelect.val('{{ $user->address }}');
            });

            $('#edit-icon').on('click', function() {
                $('#update-info-form :input').prop('disabled', false);
                $('#submit-button').prop('disabled', false);
            });

            $('#update-info-form').on('submit', function(e) {
                e.preventDefault();
                const password = $('#password').val();
                const confirmPassword = $('#confirm_password').val();

                if (password && password !== confirmPassword) {
                    toastr.error('Passwords do not match.');
                    return;
                }

                const formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        toastr.success('Information updated successfully.');
                        $('#update-info-form :input').prop('disabled', true);
                        $('#submit-button').prop('disabled', true);
                    },
                    error: function(response) {
                        toastr.error('There was an error updating the information.');
                    }
                });
            });
        });
    </script>
@endsection

@push('scripts')
    <script>
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
@endpush
