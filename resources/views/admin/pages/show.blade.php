@extends('layouts.vertical', ['page_title' => 'Page Details'])

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Page Details</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <p id="title">{{ $page->title ?? '' }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="title" class="form-label">Arabic Title</label>
                            <p id="title">{{ $page->ar_title ?? '' }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <p id="slug">{{ $page->slug ?? '' }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="data" class="form-label">Content</label>
                            <div id="data">{!! $page->data ?? '' !!}</div>
                        </div>
                        <div class="mb-3">
                            <label for="data" class="form-label">Arabic Content</label>
                            <div id="data">{!! $page->ar_data ?? '' !!}</div>
                        </div>
                        <a href="{{ route('pages.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
