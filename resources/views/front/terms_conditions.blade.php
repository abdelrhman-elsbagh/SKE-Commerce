@extends('front.layout')

@section('title', 'Terms & Conditions')

@section('content')
    <main class="page-main">
        <div class="uk-container">
            <h3 class="uk-heading-line" style="text-align: center"><span>Terms & Conditions</span></h3>
            <div class="uk-grid uk-child-width-1-3@m uk-child-width-1-2@s uk-grid-small" data-uk-grid>
                {!! $terms->data ?? "" !!}
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
