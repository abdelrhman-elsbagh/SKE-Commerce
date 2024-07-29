@extends('layouts.vertical', ['page_title' => 'Assign Permissions'])

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Assign Permissions</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('assign-permissions') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="role" class="form-label">Select Role</label>
                                <select name="role" id="role" class="form-control" required>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="permissions" class="form-label">Select Permissions</label>
                                <select name="permissions[]" id="permissions" class="form-control" multiple="multiple" required>
                                    @foreach($permissions as $permission)
                                        <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Assign Permissions</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @vite([
        'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
    ])

    <script>
        $(document).ready(function() {
            $('#permissions').select2();
        });
    </script>
@endsection
