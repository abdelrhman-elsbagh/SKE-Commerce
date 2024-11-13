@extends('layouts.vertical', ['page_title' => 'Footer Links'])

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
                    <h4 class="page-title">Footer Links</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('footer.create') }}" class="btn btn-primary mb-3">Add Footer Link</a>
                        <table id="footer-datatable" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tag</th>
                                <th>Title</th>
                                <th>URL</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($footers as $link)
                                <tr id="footer-{{ $link->id }}">
                                    <td>{{ $link->id }}</td>
                                    <td>{{ $link->tag }}</td>
                                    <td>{{ $link->title }}</td>
                                    <td>{{ $link->link }}</td>
                                    <td>
                                        @if($link->getFirstMediaUrl('images'))
                                            <img src="{{ $link->getFirstMediaUrl('images') }}" alt="{{ $link->tag }}" style="width: 50px; height: 50px;">
                                        @else
                                            <span>No image</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('footer.show', $link->id) }}" class="btn btn-info"><i class="ri-eye-line"></i></a>
                                        <a href="{{ route('footer.edit', $link->id) }}" class="btn btn-warning"><i class="ri-edit-box-fill"></i></a>
                                        <button type="button" class="btn btn-danger btn-delete" data-id="{{ $link->id }}"><i class="ri-delete-bin-5-line"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
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
                        Are you sure you want to delete this footer link?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmDelete"><i class="ri-delete-bin-5-line"></i></button>
                    </div>
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
            var footerLinkIdToDelete;

            $(document).on('click', '.btn-delete', function() {
                footerLinkIdToDelete = $(this).data('id');
                $('#deleteModal').modal('show');
            });

            $('#confirmDelete').on('click', function() {
                $.ajax({
                    url: '{{ route('footer.index') }}/' + footerLinkIdToDelete,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        $('#deleteModal').modal('hide');
                        $('#footer-' + footerLinkIdToDelete).remove();
                        $.toast({ heading: 'Success', text: 'Footer link deleted.', icon: 'success', loaderBg: '#f96868', position: 'top-right', hideAfter: 3000 });
                    },
                    error: function() {
                        $.toast({ heading: 'Error', text: 'Could not delete footer link.', icon: 'error', loaderBg: '#f96868', position: 'top-right', hideAfter: 3000 });
                    }
                });
            });
        });
    </script>
@endsection
