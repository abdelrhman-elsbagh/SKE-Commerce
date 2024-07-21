@extends('layouts.vertical', ['page_title' => 'Edit Order', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

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
                    <h4 class="page-title">Edit Order</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form id="edit-order-form" action="{{ route('orders.update', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="user_name" class="form-label">User</label>
                                <input type="text" class="form-control" id="user_name" name="user_name" value="{{ $order->user->name }}" readonly>
                                <input type="hidden" name="user_id" value="{{ $order->user_id }}">
                            </div>
                            <div class="mb-3">
                                <label for="total" class="form-label">Total</label>
                                <input type="number" step="0.01" class="form-control" id="total" name="total" value="{{ $order->total }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="order_status" class="form-label">Status</label>
                                <select class="form-control" id="order_status" name="status" required>
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="active" {{ $order->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="refunded" {{ $order->status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
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
            $('#edit-order-form').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(response) {
                        $.toast().reset('all'); // Reset previous toasts
                        $.toast({
                            heading: 'Success',
                            text: response.message || 'Order updated successfully.',
                            icon: 'success',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000,
                            afterHidden: function () {
                                window.location.href = "{{ route('orders.index') }}";
                            }
                        });
                    },
                    error: function(response) {
                        let errorMessage = 'There was an error updating the order.';
                        if (response.responseJSON && response.responseJSON.message) {
                            errorMessage = response.responseJSON.message;
                        }
                        $.toast().reset('all'); // Reset previous toasts
                        $.toast({
                            heading: 'Error',
                            text: errorMessage,
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
