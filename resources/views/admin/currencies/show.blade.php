@extends('layouts.vertical', ['page_title' => 'Currency Details'])

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Currency Details</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="currency" class="form-label">Currency</label>
                            <p id="currency">{{ $currency->currency }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price Equivalent To "USD"</label>
                            <p id="price">{{ $currency->price }}</p>
                        </div>
                        <a href="{{ route('currencies.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
