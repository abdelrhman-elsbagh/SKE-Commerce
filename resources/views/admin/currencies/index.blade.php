@extends('layouts.vertical', ['page_title' => 'Currencies'])

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Currencies</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('currencies.create') }}" class="btn btn-primary mb-3">Create Currency</a>
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Currency</th>
                                <th>Price Equivalent To "USD"</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($currencies as $currency)
                                <tr>
                                    <td>{{ $currency->id }}</td>
                                    <td>{{ $currency->currency }}</td>
                                    <td>{{ $currency->price }}</td>
                                    <td>
                                        <a href="{{ route('currencies.show', $currency->id) }}" class="btn btn-info">Show</a>
                                        <a href="{{ route('currencies.edit', $currency->id) }}" class="btn btn-warning">Edit</a>
                                        <form action="{{ route('currencies.destroy', $currency->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
