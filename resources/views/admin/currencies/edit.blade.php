@extends('layouts.vertical', ['page_title' => 'Edit Currency'])

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Edit Currency</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('currencies.update', $currency->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="currency" class="form-label">Currency</label>
                                <input type="text" class="form-control" id="currency" name="currency" value="{{ $currency->currency }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Price Equivalent To "USD"</label>
                                <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ $currency->price }}" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
