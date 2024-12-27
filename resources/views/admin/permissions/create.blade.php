@extends('layouts.vertical', ['page_title' => 'Create Role'])

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Create Role</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form id="create-role-form" action="{{ route('permissions.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Role Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="permissions" class="form-label">Select Permissions</label>
                                <select name="permissions[]" id="permissions" class="form-control" multiple="multiple" required>
                                    @foreach($permissions as $permission)
                                        <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Create Role</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    @vite([
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.css'
    ])
@endsection

@section('script')
    @vite([
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.js'
    ])
    <script>
        $(document).ready(function () {
            // Initialize Select2
            $('#permissions').select2({
                theme: 'default'
            });

            // Store selected options
            let hiddenOptions = [];

            // Hide options when selected
            $('#permissions').on('select2:select', function (e) {
                let selectedValue = e.params.data.id;

                // Add to hidden options
                hiddenOptions.push(selectedValue);

                // Filter out hidden options in the dropdown
                refreshSelect2();
            });

            // Show options when unselected
            $('#permissions').on('select2:unselect', function (e) {
                let unselectedValue = e.params.data.id;

                // Remove from hidden options
                hiddenOptions = hiddenOptions.filter(value => value !== unselectedValue);

                // Refresh Select2 dropdown
                refreshSelect2();
            });

            // Refresh Select2 dropdown to filter hidden options
            function refreshSelect2() {
                $('#permissions').select2('destroy').select2({
                    theme: 'default',
                    templateResult: function (data) {
                        // Return null for hidden options
                        if (hiddenOptions.includes(data.id)) {
                            return null;
                        }
                        return data.text;
                    }
                });
            }

            // Handle form submission via AJAX
            $('#create-role-form').on('submit', function (e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function (response) {
                        $.toast().reset('all');
                        $.toast({
                            heading: 'Success',
                            text: 'Role created successfully.',
                            icon: 'success',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000,
                            afterHidden: function () {
                                window.location.href = "{{ route('permissions.index') }}";
                            }
                        });
                    },
                    error: function (response) {
                        $.toast().reset('all');
                        $.toast({
                            heading: 'Error',
                            text: 'There was an error creating the role.',
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
