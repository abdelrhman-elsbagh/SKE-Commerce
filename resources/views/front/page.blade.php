@extends('front.layout')

@section('title', ($config->name ?? "") . " - " . ($page->title ?? ""))

@section('content')
    <main class="page-main">
        <div class="">
            <h3 class="custom-page-title" style="">
                <span style="border-bottom: 2px solid; padding-bottom: 2px;">{{ $page->title ?? 'Page' }}</span>
            </h3>
            <div class="content custom-page-content" style="">
                {!! $page->data ?? '' !!}
            </div>
        </div>
    </main>
@endsection

@section('css')
    @vite(['node_modules/jquery-toast-plugin/dist/jquery.toast.min.css'])
@endsection
