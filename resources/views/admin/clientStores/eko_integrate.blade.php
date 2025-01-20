@extends('layouts.vertical', ['page_title' => 'Eko Store Integration'])

@section('css')
    @vite([
        'node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css',
        'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css',
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.css'
    ])
@endsection

@section('content')
    <div class="container-fluid mt-5">
        <h4 class="page-title">Stores Integration</h4>
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Eko Store</h5>
                        <p class="card-text">
                            <strong>Domain:</strong> https://api.ekostore.co<br>
                            <strong>Email:</strong> your-email@email.com
                        </p>
                        @if($ekoStore && $ekoStore->secret_key)
                            <!-- Button for already integrated store -->
                            <button class="btn btn-success" disabled>Integrated Successfully</button>
                        @else
                            <!-- Button for integration -->
                            <button class="btn btn-primary btn-integrate">
                                Integrate
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form for Integration -->
    <div class="modal fade" id="integrateModal" tabindex="-1" aria-labelledby="integrateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="integrateModalLabel">Integrate Client Store</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="integrateForm">
                        <div class="mb-3">
                            <label for="apiInput" class="form-label">API Key</label>
                            <input type="text" class="form-control" id="apiInput" required>
                        </div>
                        <div class="mb-3">
                            <label for="emailInput" class="form-label">Email</label>
                            <input type="email" class="form-control" id="emailInput" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="submitIntegration">Submit</button>
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
            $(document).ready(function () {
                // Open the modal when the "Integrate" button is clicked
                $(document).on('click', '.btn-integrate', function () {
                    $('#integrateModal').modal('show');
                });

                // Handle the form submission
                $('#submitIntegration').on('click', function () {
                    const apiKey = $('#apiInput').val();
                    const email = $('#emailInput').val();

                    if (!apiKey || !email) {
                        $.toast({
                            heading: 'Error',
                            text: 'Please fill out all fields.',
                            icon: 'error',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000
                        });
                        return;
                    }

                    $.ajax({
                        url: '{{ route("clientStores.integrate") }}',
                        type: 'POST',
                        data: {
                            api_key: apiKey,
                            email: email,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            $('#integrateModal').modal('hide');

                            // Show success toast
                            $.toast({
                                heading: 'Success',
                                text: 'Integration successful. Reloading...',
                                icon: 'success',
                                loader: true,
                                loaderBg: '#28a745',
                                position: 'top-right',
                                hideAfter: 2000
                            });

                            // Reload the page after a short delay
                            setTimeout(function () {
                                location.reload();
                            }, 2000);
                        },
                        error: function () {
                            // Show error toast
                            $.toast({
                                heading: 'Error',
                                text: 'Integration failed. Please try again.',
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
