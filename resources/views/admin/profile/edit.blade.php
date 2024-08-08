@extends('layouts.vertical', ['page_title' => 'Edit Profile'])

@section('css')
    @vite([
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.css'
    ])
    <style>
        .custom-select-wrapper {
            position: relative;
        }
        .custom-select-wrapper::after {
            content: '\f078'; /* FontAwesome down arrow */
            font-family: 'FontAwesome';
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            pointer-events: none;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <img src="{{ $user->getFirstMediaUrl('avatars') ?: '/images/users/user.png' }}" class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">

                            <h4 class="mb-1 mt-2">{{ $user->name }}</h4>
                            <p class="text-muted">{{ $user->roles->first()->name ?? 'User' }}</p>

{{--                            <button type="button" class="btn btn-success btn-sm mb-2">Follow</button>--}}
{{--                            <button type="button" class="btn btn-danger btn-sm mb-2">Message</button>--}}

{{--                            <ul class="social-list list-inline mt-3 mb-0 text-center">--}}
{{--                                <li class="list-inline-item">--}}
{{--                                    <a href="javascript: void(0);" class="social-list-item border-primary text-primary"><i class="ri-facebook-circle-fill"></i></a>--}}
{{--                                </li>--}}
{{--                                <li class="list-inline-item">--}}
{{--                                    <a href="javascript: void(0);" class="social-list-item border-danger text-danger"><i class="ri-google-fill"></i></a>--}}
{{--                                </li>--}}
{{--                                <li class="list-inline-item">--}}
{{--                                    <a href="javascript: void(0);" class="social-list-item border-info text-info"><i class="ri-twitter-fill"></i></a>--}}
{{--                                </li>--}}
{{--                                <li class="list-inline-item">--}}
{{--                                    <a href="javascript: void(0);" class="social-list-item border-secondary text-secondary"><i class="ri-github-fill"></i></a>--}}
{{--                                </li>--}}
{{--                            </ul>--}}
                        </div>



                        <form id="edit-user-form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-lg-6 col-md-12 mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                                </div>
                                <div class="col-lg-6 col-md-12 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                                </div>
                                <div class="col-lg-6 col-md-12 mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                                <div class="col-lg-6 col-md-12 mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                </div>
                                <div class="col-lg-6 col-md-12 mb-3">
                                    <label for="address" class="form-label">Country</label>
                                    <div class="custom-select-wrapper">
                                        <select class="form-control custom-select" id="address" name="address">
                                            <option value="">Select Country</option>
                                            <!-- Countries will be loaded here by JavaScript -->
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12 mb-3">
                                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ \Carbon\Carbon::parse($user->date_of_birth)->format('Y-m-d') }}">
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="avatar" class="form-label">Avatar</label>
                                    <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                                    <div class="mt-2" id="avatar-preview">
                                        @if($user->getFirstMediaUrl('avatars'))
                                            <img src="{{ $user->getFirstMediaUrl('avatars') }}" alt="Avatar Preview" style="max-width: 200px;">
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div> <!-- end card-body -->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @vite([
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.js'
    ])

    <script>
        $(document).ready(function() {
            // Load countries from JSON file
            $.getJSON('{{ asset("assets/countries.json") }}', function(data) {
                var $countrySelect = $('#address');
                $.each(data, function(key, entry) {
                    $countrySelect.append($('<option></option>').attr('value', entry.name).text(entry.name));
                });
                $countrySelect.val('{{ $user->address }}');
            });

            $('#avatar').change(function() {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#avatar-preview').html('<img src="' + e.target.result + '" alt="Avatar Preview" style="max-width: 200px;">');
                }
                reader.readAsDataURL(this.files[0]);
            });

            $('#edit-user-form').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $.toast({
                            heading: 'Success',
                            text: 'Profile updated successfully.',
                            icon: 'success',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000,
                            afterHidden: function () {
                                window.location.href = "{{ route('profile.edit') }}";
                            }
                        });
                    },
                    error: function(response) {
                        $.toast({
                            heading: 'Error',
                            text: 'There was an error updating the profile.',
                            icon: 'error',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000
                        });
                    }
                });
            });
        });
    </script>
@endsection
