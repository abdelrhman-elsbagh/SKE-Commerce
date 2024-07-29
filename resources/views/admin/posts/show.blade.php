@extends('layouts.vertical', ['page_title' => 'Post Details'])

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Post Details</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <p id="title">{{ $post->title ?? "" }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <p id="description">{{ $post->description ?? "" }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <div id="image">
                                @if($post->getMedia('images')->isNotEmpty())
                                    <img src="{{ $post->getFirstMediaUrl('images') }}" alt="Post Image" style="max-width: 200px;">
                                @else
                                    <p>No image available</p>
                                @endif
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="likes" class="form-label">Likes</label>
                            <p id="likes">{{ $post->likes_count ?? 0 }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="dislikes" class="form-label">Dislikes</label>
                            <p id="dislikes">{{ $post->dislikes_count ?? 0 }}</p>
                        </div>
                        <a href="{{ route('posts.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
