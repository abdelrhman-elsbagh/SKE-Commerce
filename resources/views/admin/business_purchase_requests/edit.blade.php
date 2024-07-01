@extends('layouts.vertical', ['page_title' => 'Edit Business Purchase Request'])

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
                    <h4 class="page-title">Edit Business Purchase Request</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form id="edit-business-purchase-request-form" action="{{ route('business-purchase-requests.update', $purchaseRequest->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="business_client_id" class="form-label">Business Client</label>
                                <select class="form-control" id="business_client_id" name="business_client_id">
                                    @foreach($businessClients as $client)
                                        <option value="{{ $client->id }}" {{ $purchaseRequest->business_client_id == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control" id="notes" name="notes">{{ $purchaseRequest->notes }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="{{ $purchaseRequest->amount }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="purchase_status" class="form-label">Status</label>
                                <select class="form-control" id="purchase_status" name="status" required>
                                    <option value="pending" {{ $purchaseRequest->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ $purchaseRequest->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ $purchaseRequest->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="business_purchase_documents" class="form-label">Document</label>
                                <input type="file" class="form-control" id="business_purchase_documents" name="business_purchase_documents" accept="image/*">
                                <div class="mt-2" id="document-preview">
                                    @if($purchaseRequest->getFirstMediaUrl('business_purchase_documents'))
                                        <img src="{{ $purchaseRequest->getFirstMediaUrl('business_purchase_documents') }}" alt="Document Preview" style="max-width: 200px;">
                                    @endif
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
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
            $('#business_purchase_documents').change(function() {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#document-preview').html('<img src="' + e.target.result + '" alt="Document Preview" style="max-width: 200px;">');
                }
                reader.readAsDataURL(this.files[0]);
            });

            $('#edit-business-purchase-request-form').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $.toast({
                            heading: 'Success',
                            text: 'Business purchase request updated successfully.',
                            icon: 'success',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000
                        });
                    },
                    error: function(response) {
                        $.toast({
                            heading: 'Error',
                            text: 'There was an error updating the business purchase request.',
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
