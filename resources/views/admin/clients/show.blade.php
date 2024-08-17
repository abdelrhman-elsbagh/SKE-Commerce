@extends('layouts.vertical', ['page_title' => 'Client Details'])

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Client Details</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <p id="name">{{ $client->name }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <p id="email">{{ $client->email }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <p id="phone">{{ $client->phone }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="currencies" class="form-label">Currencies</label>
                            <p id="currencies">
                                @if($client->currencies->isNotEmpty())
                                    @foreach($client->currencies as $currency)
                                        <span class="badge bg-info">{{ $currency->currency }}</span>
                                    @endforeach
                                @else
                                    No Currencies
                                @endif
                            </p>
                        </div>
                        <a href="{{ route('clients.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
