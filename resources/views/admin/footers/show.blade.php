@extends('layouts.vertical', ['page_title' => 'Footer Link Details'])

@section('content')
    <div class="container-fluid">
        <div class="page-title-box">
            <h4 class="page-title">Footer Link Details</h4>
        </div>

        <div class="card">
            <div class="card-body">
                <h5>Tag: {{ $footer->tag }}</h5>
                <h5>Title: {{ $footer->title }}</h5>
                <h5>Arabic Tag: {{ $footer->ar_tag }}</h5>
                <h5>Arabic Title: {{ $footer->ar_title }}</h5>
                <h5>URL: <a href="{{ $footer->link }}" target="_blank">{{ $footer->link }}</a></h5>
                <div class="mb-3">
                    @if($footer->getFirstMediaUrl('images'))
                        <img src="{{ $footer->getFirstMediaUrl('images') }}" alt="Footer Image" style="width: 100px; height: 100px;">
                    @else
                        <span>No image</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
