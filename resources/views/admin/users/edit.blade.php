@extends('layouts.vertical', ['page_title' => 'Edit User'])

@section('css')
    @vite([
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.css'
    ])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Edit User</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form id="edit-user-form" action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            </div>
                            <div class="mb-3">
                                <label for="bio" class="form-label">Bio</label>
                                <textarea class="form-control" id="bio" name="bio">{{ $user->bio }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Country</label>
                                <select class="form-control" id="address" name="address">
                                    <option value="">Select Country</option>
                                    <!-- Countries will be loaded here by JavaScript -->
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ \Carbon\Carbon::parse($user->date_of_birth)->format('Y-m-d') }}">
                            </div>
                            <div class="mb-3">
                                <label for="user_status" class="form-label">Status</label>
                                <select class="form-control" id="user_status" name="status">
                                    <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="avatar" class="form-label">Avatar</label>
                                <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                                <div class="mt-2" id="avatar-preview">
                                    @if($user->getFirstMediaUrl('avatars'))
                                        <img src="{{ $user->getFirstMediaUrl('avatars') }}" alt="Avatar Preview" style="max-width: 200px;">
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-control" id="role" name="role" required>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ $user->roles->contains($role->id) ? 'selected' : '' }}>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="fee_group" class="form-label">Fee Group</label>
                                <select class="form-control" id="fee_group" name="fee_group_id">
                                    <option value="">Select Fee Group</option>
                                    @foreach($feeGroups as $group)
                                        <option value="{{ $group->id }}" {{ $user->fee_group_id == $group->id ? 'selected' : '' }}>{{ $group->name }} ( {{ $group->fee  }} % )</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="fee_group" class="form-label">Currency</label>
                                <select class="form-control" id="currency_id" name="currency_id">
                                    <option value="">Select Fee Group</option>
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->id }}" {{ $user->currency_id == $currency->id ? 'selected' : '' }}>{{ $currency->currency }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
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
                            text: 'User updated successfully.',
                            icon: 'success',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000,
                            afterHidden: function () {
                                window.location.href = "{{ route('users.index') }}";
                            }
                        });
                    },
                    error: function(response) {
                        $.toast({
                            heading: 'Error',
                            text: 'There was an error updating the user.',
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
