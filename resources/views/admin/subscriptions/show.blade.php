@extends('layouts.vertical', ['page_title' => 'Subscription Details'])

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Subscription Details</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="business_client_id" class="form-label">Business Client ID</label>
                            <p id="business_client_id">{{ $subscription->business_client_id }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="plan_id" class="form-label">Plan ID</label>
                            <p id="plan_id">{{ $subscription->plan_id }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <p id="start_date">{{ $subscription->start_date }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <p id="end_date">{{ $subscription->end_date }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <p id="status">{{ $subscription->status }}</p>
                        </div>
                        <a href="{{ route('subscriptions.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
