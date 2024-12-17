@extends('layouts.vertical', ['page_title' => 'Items'])

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
                    <h4 class="page-title">Items</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body  res-table-card">
                        <a href="{{ route('items.create') }}" class="btn btn-primary mb-3">Create Item</a>
                        <table id="basic-datatable" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>Apps ID</th>
                                <th>Name</th>
                                <th>Ar Name</th>
                                <th>Type</th>
                                <th>Customizable</th>
                                <th>Order Active</th>
                                <th>Order Refunded</th>
                                <th>Description</th>
                                <th>Category</th>
                                <th>Tags</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($items as $item)
                                <tr id="item-{{ $item->id }}">
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->ar_name ?? "" }}</td>
                                    <td>{{ $item->is_outsourced == 1 ? 'API ( ' . ( $item->source_domain ?? "") . ' )'  : 'Manual' }}</td>
                                    <td>{{ $item->is_custom == 1 ? 'Yes' : 'No' }}</td>
                                    <td>{{ $item->activeOrdersSum }}</td>
                                    <td>{{ $item->refundedOrdersSum }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($item->description, 70, '...') }}</td>
                                    <td>{{ $item->category->name . ($item->category->ar_name ? ' - ' . $item->category->ar_name : '') }}</td>
                                    <td>
                                        @foreach($item->tags as $tag)
                                            <span class="badge bg-primary">{{ $tag->name . ($tag->ar_name ? ' - ' . $tag->ar_name : '') }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <a href="{{ route('items.show', $item->id) }}" class="btn btn-info"><i class="ri-eye-line"></i></a>
                                        <a href="{{ route('items.edit', $item->id) }}" class="btn btn-warning"><i class="ri-edit-box-fill"></i></a>
                                        <button class="btn btn-danger btn-delete" data-id="{{ $item->id }}"><i class="ri-delete-bin-5-line"></i></button>
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
                    Are you sure you want to delete this item?
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
        'resources/js/pages/demo.datatable-init.js',
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.js'
    ])
@endsection

@section('admin-script')
    <script>
        $(document).ready(function() {
            var itemIdToDelete;

            // Ensure click event handler is attached only once using event delegation
            $(document).on('click', '.btn-delete', function() {
                itemIdToDelete = $(this).data('id');
                $('#deleteModal').modal('show');
            });

            $('#confirmDelete').on('click', function() {
                $.ajax({
                    url: '{{ route('items.index') }}/' + itemIdToDelete,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(result) {
                        $('#deleteModal').modal('hide');
                        $('#item-' + itemIdToDelete).remove();
                        // Show success message
                        $.toast().reset('all'); // Reset previous toasts
                        $.toast({
                            heading: 'Success',
                            text: 'Item deleted successfully.',
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
                            text: 'An error occurred while deleting the item.',
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
