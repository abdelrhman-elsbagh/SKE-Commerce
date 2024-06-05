@extends('layouts.vertical', ['page_title' => 'Tag Details'])

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Tag Details</h4>
                        <p><strong>ID:</strong> {{ $tag->id }}</p>
                        <p><strong>Name:</strong> {{ $tag->name }}</p>
                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div> <!-- container -->
@endsection

@section('script')
    <!-- Your custom script goes here -->
@endsection
