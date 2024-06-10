@extends('layouts.vertical', ['page_title' => 'Show Payment Method'])

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
                    <h4 class="page-title">Show Payment Method</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>ID</th>
                                <td>{{ $paymentMethod->id }}</td>
                            </tr>
                            <tr>
                                <th>Gateway</th>
                                <td>{{ $paymentMethod->gateway }}</td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td>{{ $paymentMethod->description }}</td>
                            </tr>
                            <tr>
                                <th>Image</th>
                                <td>
                                    @if($paymentMethod->getFirstMediaUrl('payment_method_images'))
                                        <img src="{{ $paymentMethod->getFirstMediaUrl('payment_method_images') }}" alt="Payment Method Image" style="max-width: 200px;">
                                    @endif
                                </td>
                            </tr>
                        </table>
                        <a href="{{ route('payment-methods.index') }}" class="btn btn-primary">Back</a>
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
@endsection
