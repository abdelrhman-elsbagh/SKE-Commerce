@extends('layouts.vertical', ['page_title' => 'Plans'])

@section('css')
    @vite([
        'node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css',
        'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css',
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.css'
    ])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Plans</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('plans.create') }}" class="btn btn-primary mb-3">Create New Plan</a>
                        <table id="basic-datatable" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Duration</th>
                                <th>Description</th>
                                <th>Features</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($plans as $plan)
                                <tr id="plan-{{ $plan->id }}">
                                    <td>{{ $plan->id }}</td>
                                    <td>{{ $plan->name }}</td>
                                    <td>{{ $plan->price }}</td>
                                    <td>{{ $plan->duration }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($plan->description, 50, '...') }}</td>
                                    <td>
                                        <ul>
                                            @foreach($plan->features as $feature)
                                                <li>{{ $feature->name }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        <a href="{{ route('plans.edit', $plan->id) }}" class="btn btn-warning">Edit</a>
                                        <button type="button" class="btn btn-danger btn-delete" data-id="{{ $plan->id }}">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div> <!-- container -->

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
                    Are you sure you want to delete this plan?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @vite([
        'resources/js/pages/demo.datatable-init.js',
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.js'
    ])

    <script>
        $(document).ready(function() {
            var planIdToDelete;

            // Open delete confirmation modal
            $(document).on('click', '.btn-delete', function() {
                planIdToDelete = $(this).data('id');
                $('#deleteModal').modal('show');
            });

            // Confirm delete
            $('#confirmDelete').on('click', function() {
                $.ajax({
                    url: '{{ route('plans.index') }}/' + planIdToDelete,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(result) {
                        $('#deleteModal').modal('hide');
                        $('#plan-' + planIdToDelete).remove();
                        $.toast({
                            heading: 'Success',
                            text: 'Plan deleted successfully.',
                            icon: 'success',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000
                        });
                    },
                    error: function(xhr) {
                        $.toast({
                            heading: 'Error',
                            text: 'An error occurred while deleting the plan.',
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
