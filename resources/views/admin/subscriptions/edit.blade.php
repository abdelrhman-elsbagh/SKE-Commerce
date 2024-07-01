@extends('layouts.vertical', ['page_title' => 'Edit Subscription', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

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
                    <h4 class="page-title">Edit Subscription</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form id="edit-subscription-form" action="{{ route('subscriptions.update', $subscription->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="business_client_id" class="form-label">Business Client</label>
                                <select class="form-control" id="business_client_id" name="business_client_id" required>
                                    @foreach($businessClients as $businessClient)
                                        <option value="{{ $businessClient->id }}" {{ $businessClient->id == $subscription->business_client_id ? 'selected' : '' }}>{{ $businessClient->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="plan_id" class="form-label">Plan</label>
                                <select class="form-control" id="plan_id" name="plan_id" required>
                                    @foreach($plans as $plan)
                                        <option value="{{ $plan->id }}" {{ $plan->id == $subscription->plan_id ? 'selected' : '' }}>{{ $plan->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ \Carbon\Carbon::parse($subscription->start_date)->format('Y-m-d') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ \Carbon\Carbon::parse($subscription->end_date)->format('Y-m-d') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="subscription_status" class="form-label">Status</label>
                                <select class="form-control" id="subscription_status" name="status" required>
                                    <option value="active" {{ $subscription->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $subscription->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
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
            $('#edit-subscription-form').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(response) {
                        $.toast().reset('all'); // Reset previous toasts
                        $.toast({
                            heading: 'Success',
                            text: 'Subscription updated successfully.',
                            icon: 'success',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000
                        });
                    },
                    error: function(response) {
                        $.toast().reset('all'); // Reset previous toasts
                        $.toast({
                            heading: 'Error',
                            text: 'There was an error updating the subscription.',
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
