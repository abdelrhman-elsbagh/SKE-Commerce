@extends('front.layout')

@section('title', ($config->name ?? "") . "- Payment Methods" )

@section('content')
    <main class="page-main">
        <div class="">
{{--            <h1 class=""><span>Payment Methods</span></h1>--}}
            <div class="uk-grid uk-child-width-1-3@m uk-child-width-1-2@s uk-grid-small" data-uk-grid>
                @foreach($paymentMethods as $method)
                    <div class="">
                        <div class="uk-card uk-card-default uk-card-hover uk-margin uk-flex uk-flex-column pay-method-card">
                            <div class="uk-card-header">
                                <h3 class="uk-card-title pay-method-title">
                                    {{ App::getLocale() == 'ar' && $method->ar_gateway ? $method->ar_gateway : $method->gateway }}
                                </h3>
                            </div>
                            <div class="uk-card-body uk-flex-1">
                                <div class="description payment-desc">
                                    {!! App::getLocale() == 'ar' && $method->ar_description ? $method->ar_description : $method->description !!}
                                </div>
                                @if($method->getFirstMediaUrl('payment_method_images'))
                                    <img src="{{ $method->getFirstMediaUrl('payment_method_images') }}" alt="{{ App::getLocale() == 'ar' && $method->ar_gateway ? $method->ar_gateway : $method->gateway }}" class="" style="max-height: 300px;border-radius: 10px">
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

@section('css')
    @vite(['node_modules/jquery-toast-plugin/dist/jquery.toast.min.css'])
    <style>
        .uk-card-body {
            display: flex;
            flex-direction: column;
        }
        .description {
            flex: 1;
        }
    </style>
@endsection
