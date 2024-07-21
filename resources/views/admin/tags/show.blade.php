@extends('layouts.vertical', ['page_title' => 'Tag Details'])

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Tag Details</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <p id="name">{{ $tag->name }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <div id="image">
                                @if($tag->getFirstMediaUrl('images'))
                                    <img src="{{ $tag->getFirstMediaUrl('images') }}" alt="Tag Image" style="max-width: 200px;">
                                @else
                                    <p>No image available.</p>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('tags.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
