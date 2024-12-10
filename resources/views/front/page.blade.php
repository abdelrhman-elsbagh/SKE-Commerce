@extends('front.layout')

@section('title', ($config->name ?? "") . " - " . ($page->title ?? ""))

@section('content')
    <main class="page-main">
        <div class="">
            <h3 class="custom-page-title" style="">
                <span style="border-bottom: 2px solid; padding-bottom: 2px;">{{ App::getLocale() === 'ar' ? $page->ar_title : $page->title ?? "Page" }}</span>
            </h3>
            <div class="content custom-page-content" style="">
                {!! App::getLocale() === 'ar' ? $page->ar_data : $page->data ?? "" !!}
            </div>
        </div>
    </main>
@endsection

@section('css')
    @vite(['node_modules/jquery-toast-plugin/dist/jquery.toast.min.css'])
@endsection
