@extends('layouts.vertical', ['page_title' => 'Edit Role'])

@section('css')
    @vite([
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.css'
    ])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap4-theme/1.2.2/select2-bootstrap4.min.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Edit Role</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form id="edit-role-form" action="{{ route('permissions.update', $role->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="name" class="form-label">Role Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $role->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="permissions" class="form-label">Select Permissions</label>
                                <select name="permissions[]" id="permissions" class="form-control" multiple="multiple" required>
                                    @foreach($permissions as $permission)
                                        <option value="{{ $permission->id }}"
                                                @if($role->permissions->contains($permission->id))
                                                    selected
                                            @endif>
                                            {{ $permission->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Role</button>
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
            // Initialize Select2
            $('#permissions').select2({
                theme: 'default'
            });

            // Store pre-selected options
            let hiddenOptions = $('#permissions').val() || [];

            // Hide already selected options on page load
            $('#permissions option:selected').each(function() {
                hiddenOptions.push($(this).val());
            });

            // Refresh Select2 dropdown to apply initial hidden options
            refreshSelect2();

            // Hide options when selected
            $('#permissions').on('select2:select', function(e) {
                let selectedValue = e.params.data.id;

                // Add to hidden options
                hiddenOptions.push(selectedValue);

                // Refresh Select2 dropdown
                refreshSelect2();
            });

            // Show options when unselected
            $('#permissions').on('select2:unselect', function(e) {
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
                    templateResult: function(data) {
                        // Skip rendering for hidden options
                        if (hiddenOptions.includes(data.id)) {
                            return null;
                        }
                        return data.text;
                    },
                    templateSelection: function(data) {
                        return data.text;
                    }
                });
            }

            // Handle form submission via AJAX
            $('#edit-role-form').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(response) {
                        $.toast().reset('all'); // Reset previous toasts
                        $.toast({
                            heading: 'Success',
                            text: 'Role updated successfully.',
                            icon: 'success',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000,
                            afterHidden: function() {
                                window.location.href = "{{ route('permissions.index') }}";
                            }
                        });
                    },
                    error: function(response) {
                        $.toast().reset('all'); // Reset previous toasts
                        $.toast({
                            heading: 'Error',
                            text: 'There was an error updating the role.',
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
