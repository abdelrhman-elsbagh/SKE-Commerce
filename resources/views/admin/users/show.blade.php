@extends('layouts.vertical', ['page_title' => 'User Details'])

@section('css')
    @vite([
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.css'
    ])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">User Details</h4>
                        <table class="table table-bordered">
                            <tr>
                                <th>ID</th>
                                <td>{{ $user->id }}</td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>Whatsapp Number</th>
                                <td>{{ $user->phone ?? "" }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Bio</th>
                                <td>{{ $user->bio ?? "" }}</td>
                            </tr>
                            <tr>
                                <th>Country</th>
                                <td>{{ $user->address ?? "" }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>{{ ucfirst($user->status) }}</td>
                            </tr>
                            <tr>
                                <th>Role</th>
                                <td>{{ $user->roles->first()->name }}</td>
                            </tr>
                            <tr>
                                <th>Avatar</th>
                                <td>
                                    @if($user->getFirstMediaUrl('avatars'))
                                        <img src="{{ $user->getFirstMediaUrl('avatars') }}" alt="Avatar" style="max-width: 200px;">
                                    @endif
                                </td>
                            </tr>
                        </table>
                        <a href="{{ route('users.index') }}" class="btn btn-primary">Back</a>
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
@endsection
