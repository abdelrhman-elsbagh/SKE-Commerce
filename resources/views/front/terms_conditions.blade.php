@extends('front.layout')

@section('title', ($config->name ?? "") . "- Terms & Conditions")

@section('content')
    <main class="page-main">
        <div class="">
            <h3 class="" style="text-align: center"><span style="border-bottom: 2px solid; padding-bottom: 2px;">Terms & Conditions</span></h3>
            <div class="content" style="width: 100%;background: #FFF;padding: 40px;border-radius: 10px;">
                {!! $terms->data ?? "" !!}
            </div>
        </div>
    </main>
@endsection

@section('css')
    @vite(['node_modules/jquery-toast-plugin/dist/jquery.toast.min.css'])
@endsection
