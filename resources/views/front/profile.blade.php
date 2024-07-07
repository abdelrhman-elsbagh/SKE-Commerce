@extends('front.layout')

@section('title', 'Ske E-Commerce')

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
                                <div class="user-info__title">#{{ $user->id }}</div>
                                <div class="user-info__title">{{ $user->name }}</div>
                                <div class="user-info__text">{{ $user->address }}, Member since {{ $user->created_at->format('F Y') }}</div>
                            </div>
                        </div>
                        @if($feeGroup)
                            <div class="fee-group-info" style="text-align: center">
                                @if($feeGroup->getFirstMediaUrl('images'))
                                    <img src="{{ $feeGroup->getFirstMediaUrl('images') }}" alt="Fee Group Image" style="max-width: 50px;">
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
                            <i class="fas fa-edit fa-lg" style="color: #27ae60;"></i>
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
                                <label for="address">Address</label>
                                <input class="uk-input" id="address" name="address" type="text" value="{{ $user->address }}" disabled>
                            </div>
                            <div class="uk-margin">
                                <label for="date_of_birth">Date of Birth</label>
                                <input class="uk-input" id="date_of_birth" name="date_of_birth" type="date" value="{{ $user->date_of_birth }}" disabled>
                            </div>
                            <div class="uk-margin">
                                <label for="image">Profile Image</label>
                                <input class="uk-input" id="image" name="image" type="file" disabled>
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
