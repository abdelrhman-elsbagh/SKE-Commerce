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
                        <button class="btn btn-secondary mb-3" data-bs-toggle="modal" data-bs-target="#moveItemModal">Move Item</button>
                        <button class="btn btn-danger delete-selected mb-3" disabled>Delete Selected</button>

                        <table id="basic-datatable" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>Select</th>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Ar Name</th>
                                <th>Type</th>
                                <th>Customizable</th>
                                <th>Order Active</th>
                                <th>Order Refunded</th>
                                <th>Description</th>
                                <th>Category</th>
                                <th>Tags</th>
                                <th>Rank</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($items as $item)
                                <tr id="item-{{ $item->id }}">
                                    <td>
                                        <input type="checkbox" class="item-select" value="{{ $item->id }}">
                                    </td>
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
                                    <td>{{ $item->order ?? 0 }}</td>
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

            <div class="modal fade" id="moveItemModal" tabindex="-1" aria-labelledby="moveItemModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="moveItemModalLabel">Move SubItems</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="moveItemForm">
                                @csrf
                                <div class="mb-3">
                                    <label for="subitem-select" class="form-label">Select SubItems</label>
                                    <select id="subitem-select" class="form-control" multiple>
                                        @foreach ($subitems as $subitem)
                                            <option value="{{ $subitem->id }}">
                                                {{ $subitem->name }} (Item: {{ $subitem->item->name ?? 'Unassigned' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="item-select" class="form-label">Select Target Item</label>
                                    <select id="item-select" class="form-control">
                                        <option value="">Select an Item</option>
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Move</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

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
    <script>
        $(document).ready(function () {
            // Initialize Select2 for subitem and item selects with explicit reset
            $('#subitem-select').val(null).trigger('change'); // Reset pre-selected values
            $('#subitem-select').select2({
                theme: 'default',
                placeholder: 'Select SubItems',
                allowClear: true
            });

            $('#item-select').val(null).trigger('change'); // Reset pre-selected values
            $('#item-select').select2({
                theme: 'default',
                placeholder: 'Select Target Item',
                allowClear: true
            });

            // Handle Move Item Form Submission
            $('#moveItemForm').on('submit', function (event) {
                event.preventDefault();

                const subitemIds = $('#subitem-select').val(); // Get selected sub-items
                const targetItemId = $('#item-select').val();  // Get target item

                if (!subitemIds || !targetItemId) {
                    $.toast({
                        heading: 'Error',
                        text: 'Please select at least one subitem and a target item.',
                        icon: 'error',
                        loader: true,
                        loaderBg: '#f96868',
                        position: 'top-right',
                        hideAfter: 3000,
                    });
                    return;
                }

                fetch('{{ route('subitems.move') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        subitem_ids: subitemIds, // Send as an array
                        target_item_id: targetItemId,
                    }),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            $.toast({
                                heading: 'Success',
                                text: 'SubItems moved successfully!',
                                icon: 'success',
                                loader: true,
                                loaderBg: '#5ba035',
                                position: 'top-right',
                                hideAfter: 3000,
                            });
                            $('#moveItemModal').modal('hide');
                            location.reload();
                        } else {
                            $.toast({
                                heading: 'Error',
                                text: 'Failed to move SubItems: ' + data.message,
                                icon: 'error',
                                loader: true,
                                loaderBg: '#f96868',
                                position: 'top-right',
                                hideAfter: 3000,
                            });
                        }
                    })
                    .catch(err => {
                        console.error('Error moving subitems:', err);
                        $.toast({
                            heading: 'Error',
                            text: 'An unexpected error occurred while moving the subitems.',
                            icon: 'error',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000,
                        });
                    });
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            const deleteSelectedButton = $('.delete-selected');

            // Manage row selection and action disabling
            $(document).on('change', '.item-select', function () {
                const selectedItems = $('.item-select:checked').map(function () {
                    return $(this).val();
                }).get();

                // Disable actions for selected rows
                $('.item-select').each(function () {
                    const itemId = $(this).val();
                    const actions = $(`.item-action[data-id="${itemId}"]`);
                    if ($(this).is(':checked')) {
                        actions.prop('disabled', true);
                    } else {
                        actions.prop('disabled', false);
                    }
                });

                // Enable or disable the delete button
                deleteSelectedButton.prop('disabled', selectedItems.length === 0);
            });

            // Handle delete action for selected rows
            deleteSelectedButton.on('click', function () {
                const selectedItems = $('.item-select:checked').map(function () {
                    return $(this).val();
                }).get();

                if (selectedItems.length === 0) {
                    $.toast({
                        heading: 'Error',
                        text: 'No items selected.',
                        icon: 'error',
                        loader: true,
                        loaderBg: '#f96868',
                        position: 'top-right',
                        hideAfter: 3000,
                    });
                    return;
                }

                if (!confirm('Are you sure you want to delete the selected items?')) {
                    return;
                }

                $.ajax({
                    url: '{{ route("items.deleteSelected") }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    data: { item_ids: selectedItems },
                    success: function (response) {
                        if (response.success) {
                            selectedItems.forEach(id => {
                                $(`#item-${id}`).remove(); // Remove rows from the table
                            });

                            $.toast({
                                heading: 'Success',
                                text: 'Selected items deleted successfully.',
                                icon: 'success',
                                loader: true,
                                loaderBg: '#5ba035',
                                position: 'top-right',
                                hideAfter: 3000,
                            });

                            deleteSelectedButton.prop('disabled', true); // Disable button after action
                        } else {
                            $.toast({
                                heading: 'Error',
                                text: response.message || 'Failed to delete selected items.',
                                icon: 'error',
                                loader: true,
                                loaderBg: '#f96868',
                                position: 'top-right',
                                hideAfter: 3000,
                            });
                        }
                    },
                    error: function () {
                        $.toast({
                            heading: 'Error',
                            text: 'An unexpected error occurred while deleting the items.',
                            icon: 'error',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000,
                        });
                    },
                });
            });
        });
    </script>
@endsection
