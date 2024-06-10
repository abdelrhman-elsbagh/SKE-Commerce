<!-- resources/views/front/payment-methods.blade.php -->

@extends('front.layout')

@section('title', 'Payment Methods')

@section('content')
    <main class="page-main">
        <div class="uk-container">
            <h1 class="uk-heading-line"><span>Payment Methods</span></h1>
            <div class="uk-grid uk-child-width-1-3@m uk-child-width-1-2@s uk-grid-small" data-uk-grid>
                @foreach($paymentMethods as $method)
                    <div>
                        <div class="uk-card uk-card-default uk-card-hover uk-margin">
                            <div class="uk-card-header">
                                <h3 class="uk-card-title">{{ $method->gateway }}</h3>
                            </div>
                            <div class="uk-card-body">
                                <p>{{ $method->description }}</p>
                                @if($method->getFirstMediaUrl('payment_method_images'))
                                    <img src="{{ $method->getFirstMediaUrl('payment_method_images') }}" alt="{{ $method->gateway }}" class="uk-width-1-1">
                                @else
                                    <img src="{{ asset('assets/img/default-image.jpg') }}" alt="Default Image" class="uk-width-1-1">
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
@endsection
