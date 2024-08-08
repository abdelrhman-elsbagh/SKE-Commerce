@extends('layouts.vertical', ['page_title' => 'Agents'])

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
                    <h4 class="page-title">Agents</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Agents</h4>
                        <table id="basic-datatable" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Facebook <i class="ri-facebook-circle-fill" style="color: #3b5998;"></i></th>
                                <th>Whatsapp <i class="ri-whatsapp-line" style="color: #25D366;"></i></th>
                                <th>Instagram <i class="ri-instagram-line" style="color: #E4405F;"></i></th>
                                <th>Telegram <i class="ri-telegram-line" style="color: #0088cc;"></i></th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($partners as $partner)
                                <tr id="partner-{{ $partner->id }}">
                                    <td>{{ $partner->id }}</td>
                                    <td>{{ $partner->name ?? "" }}</td>
                                    <td>{!! \Illuminate\Support\Str::limit($partner->description, 70, '...') !!}</td>
                                    <td>
                                        @if($partner->facebook)
                                            <a href="{{ $partner->facebook }}" target="_blank" class="badge bg-info-subtle text-info rounded-pill">
                                                {{ $partner->facebook }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if($partner->whatsapp)
                                            <a href="https://wa.me/{{ $partner->whatsapp }}" target="_blank" class="badge bg-success-subtle text-success rounded-pill">
                                                {{ $partner->whatsapp }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if($partner->insta)
                                            <a href="{{ $partner->insta }}" target="_blank" class="badge bg-danger-subtle text-danger rounded-pill">
                                                {{ $partner->insta }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if($partner->telegram)
                                            <a href="https://t.me/{{ $partner->telegram }}" target="_blank" class="badge bg-primary-subtle text-primary rounded-pill">
                                                {{ $partner->telegram }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('partners.show', $partner->id) }}" class="btn btn-info"><i class="ri-eye-line"></i></a>
                                        <a href="{{ route('partners.edit', $partner->id) }}" class="btn btn-warning"><i class="ri-edit-box-fill"></i></a>
                                        <button class="btn btn-danger btn-delete" data-id="{{ $partner->id }}"><i class="ri-delete-bin-5-line"></i></button>
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
                    Are you sure you want to delete this partner?
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
            var partnerIdToDelete;

            // Ensure click event handler is attached only once using event delegation
            $(document).on('click', '.btn-delete', function() {
                partnerIdToDelete = $(this).data('id');
                $('#deleteModal').modal('show');
            });

            $('#confirmDelete').on('click', function() {
                $.ajax({
                    url: '{{ route('partners.index') }}/' + partnerIdToDelete,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(result) {
                        $('#deleteModal').modal('hide');
                        $('#partner-' + partnerIdToDelete).remove();
                        // Show success message
                        $.toast().reset('all'); // Reset previous toasts
                        $.toast({
                            heading: 'Success',
                            text: 'Agent deleted successfully.',
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
                            text: 'An error occurred while deleting the agent.',
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
