@extends('layouts.vertical', ['page_title' => 'Show Diamond Rate'])

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
                    <h4 class="page-title">Diamond Rate Details</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">Diamond Rate Information</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <th>ID</th>
                                    <td>{{ $diamondRate->id }}</td>
                                </tr>
                                <tr>
                                    <th>Diamonds</th>
                                    <td>{{ $diamondRate->diamonds }}</td>
                                </tr>
                                <tr>
                                    <th>USD</th>
                                    <td>{{ $diamondRate->usd }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <a href="{{ route('diamond_rates.index') }}" class="btn btn-secondary">Back to List</a>
                        <a href="{{ route('diamond_rates.edit', $diamondRate->id) }}" class="btn btn-warning"><i class="ri-edit-box-fill"></i></a>
                        <button class="btn btn-danger btn-delete" data-id="{{ $diamondRate->id }}"><i class="ri-delete-bin-5-line"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this diamond rate?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete"><i class="ri-delete-bin-5-line"></i></button>
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

@section('admin-script')
    <script>
        $(document).ready(function() {
            var diamondRateIdToDelete;

            // Ensure click event handler is attached only once using event delegation
            $(document).on('click', '.btn-delete', function() {
                diamondRateIdToDelete = $(this).data('id');
                $('#deleteModal').modal('show');
            });

            $('#confirmDelete').on('click', function() {
                $.ajax({
                    url: '{{ route('diamond_rates.index') }}/' + diamondRateIdToDelete,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(result) {
                        $('#deleteModal').modal('hide');
                        window.location.href = '{{ route('diamond_rates.index') }}';
                        // Show success message
                        $.toast().reset('all'); // Reset previous toasts
                        $.toast({
                            heading: 'Success',
                            text: 'Diamond rate deleted successfully.',
                            icon: 'success',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000
                        });
                    },
                    error: function(err) {
                        // Show error message
                        $.toast().reset('all'); // Reset previous toasts
                        $.toast({
                            heading: 'Error',
                            text: 'An error occurred while deleting the diamond rate.',
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
