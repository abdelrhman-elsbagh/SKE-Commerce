@extends('front.layout')

@section('title', ($config->name ?? "") . "- Posts")

@section('content')
    <main class="page-main">
        <!-- Search Input Field -->
        <div class="uk-grid uk-child-width-1-4@xl uk-child-width-1-5@m uk-child-width-1-3@s uk-grid-small" data-uk-grid id="posts-container">
            @foreach ($posts as $post)
                <div class="uk-width-1-4@xl uk-width-1-5@m uk-width-1-3@s post-card" data-title="{{ strtolower($post->title) }}" style="margin-top: 15px;">
                    <div class="game-card" style="overflow: hidden">
                        <div class="game-card__box game-card">
                            <div class="game-card__media">
                                <a href="javascript:void(0);" class="post-link" data-title="{{ $post->title }}" data-description="{{ $post->description }}" data-image="{{ $post->getFirstMediaUrl('images') ?: asset('assets/img/default-post.jpg') }}">
                                    @if ($post->getFirstMediaUrl('images'))
                                        <img src="{{ $post->getFirstMediaUrl('images') }}" alt="{{ $post->title }}">
                                    @else
                                        <img src="{{ asset('assets/img/default-post.jpg') }}" alt="Default Image">
                                    @endif
                                </a>
                            </div>
                            <div class="game-card__info">
                                <a class="game-card__title" style="padding-bottom: 0; margin-bottom: 0;">{{ $post->title }}</a>
                                <p style="font-size: 12px;padding: 0;margin-top: 8px;">{{ \Illuminate\Support\Str::limit($post->description, 300) ?? "" }}</p>
                                <p class="card__info" style="margin-top: 0;padding-top: 0;margin-bottom: 0">
                                    {{ $post->created_at  }}
                                </p>
                                <div class="like-dislike-buttons" style="margin-top: 10px;">
                                    <button class="like-button" data-post-id="{{ $post->id }}">
                                        <i class="fas fa-thumbs-up"></i> {{ $post->likes_count }}
                                    </button>
                                    <button class="dislike-button" data-post-id="{{ $post->id }}">
                                        <i class="fas fa-thumbs-down"></i> {{ $post->dislikes_count }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </main>

    <!-- Modal -->
    <div id="post-modal" class="uk-modal-full" uk-modal>
        <div class="uk-modal-dialog">
            <button class="uk-modal-close-full uk-close-large" type="button" uk-close></button>
            <div class="uk-grid-collapse uk-child-width-1-2@s uk-flex-middle" uk-grid>
                <div class="uk-background-cover" id="post-modal-image" style="background-image: url('');" uk-height-viewport></div>
                <div class="uk-padding-large">
                    <h1 id="post-modal-title"></h1>
                    <p id="post-modal-description"></p>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        @push('scripts')
            <script>
                toastr.success("{{ session('success') }}");
            </script>
        @endpush
    @endif

    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Modal functionality
                document.querySelectorAll('.post-link').forEach(function(link) {
                    link.addEventListener('click', function() {
                        const title = this.getAttribute('data-title');
                        const description = this.getAttribute('data-description');
                        const image = this.getAttribute('data-image');

                        document.getElementById('post-modal-title').innerText = title;
                        document.getElementById('post-modal-description').innerText = description;
                        document.getElementById('post-modal-image').style.backgroundImage = `url(${image})`;

                        UIkit.modal('#post-modal').show();
                    });
                });

                // Like button functionality
                document.querySelectorAll('.like-button').forEach(function(button) {
                    button.addEventListener('click', function() {
                        const postId = this.getAttribute('data-post-id');
                        const dislikeButton = this.nextElementSibling;
                        fetch(`/posts/${postId}/like`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        }).then(response => response.json()).then(data => {
                            if (data.success) {
                                this.innerHTML = `<i class="fas fa-thumbs-up"></i> ${data.likes_count}`;
                                dislikeButton.innerHTML = `<i class="fas fa-thumbs-down"></i> ${data.dislikes_count}`;
                                if (data.liked) {
                                    toastr.success('Post liked.');
                                } else {
                                    toastr.success('Like removed.');
                                }
                            } else {
                                toastr.error('Error liking post.');
                            }
                        }).catch(error => {
                            toastr.error('Error liking post.');
                        });
                    });
                });

                // Dislike button functionality
                document.querySelectorAll('.dislike-button').forEach(function(button) {
                    button.addEventListener('click', function() {
                        const postId = this.getAttribute('data-post-id');
                        const likeButton = this.previousElementSibling;
                        fetch(`/posts/${postId}/dislike`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        }).then(response => response.json()).then(data => {
                            if (data.success) {
                                this.innerHTML = `<i class="fas fa-thumbs-down"></i> ${data.dislikes_count}`;
                                likeButton.innerHTML = `<i class="fas fa-thumbs-up"></i> ${data.likes_count}`;
                                if (data.disliked) {
                                    toastr.success('Post disliked.');
                                } else {
                                    toastr.success('Dislike removed.');
                                }
                            } else {
                                toastr.error('Error disliking post.');
                            }
                        }).catch(error => {
                            toastr.error('Error disliking post.');
                        });
                    });
                });
            });
        </script>
    @endsection


    <style>
        .search-container {
            position: relative;
            width: 100%;
        }

        .search-container input {
            width: 100%;
            padding-right: 35px;
        }

        .search-icon {
            position: absolute;
            right: 30px;
            top: 50%;
            transform: translateY(-50%);
            color: #f46119;
        }

        .card-tag {
            text-align: center;
            width: 110px;
            rotate: -45deg;
            position: absolute;
            top: 12px;
            left: -20px;
            padding: 9px 20px;
            font-size: 14px;
            font-weight: bold;
            color: white;
            border-radius: 2px;
            max-height: 47px;
        }

        .card-tag-inactive {
            background-color: red;
        }

        .game-card__box {
            position: relative;
            overflow: hidden;
        }

        #post-modal .uk-modal-dialog {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #post-modal .uk-background-cover {
            background-size: cover;
            background-position: center;
        }

        .like-dislike-buttons {
            display: flex;
            justify-content: space-between;
        }

        .like-button,
        .dislike-button {
            background: none;
            border: none;
            cursor: pointer;
            color: #f46119;
        }
    </style>
@endsection
