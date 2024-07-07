@extends('front.layout')

@section('title', 'Ske E-Commerce')

@section('content')
    <main class="page-main">
        <div class="widjet --filters">
            <div class="widjet__head">
                <h3 class="uk-text-lead">My Favourites</h3>
            </div>
        </div>
        <div class="uk-grid uk-child-width-1-6@xl uk-child-width-1-4@l uk-child-width-1-3@s uk-flex-middle uk-grid-small" data-uk-grid>
            @forelse($userFavorites as $favorite)
                <div>
                    <div class="game-card" style="position: relative;">
                        <div class="game-card__box">
                            <div class="game-card__media">
                                <a href="{{ route('item.show', ['id' => $favorite->subItem ? $favorite->subItem->item->id : $favorite->item_id]) }}">
                                    @if($favorite->subItem && $favorite->subItem->getFirstMediaUrl('images'))
                                        <img src="{{ $favorite->subItem->getFirstMediaUrl('images') }}" alt="{{ $favorite->subItem->name }}">
                                    @elseif($favorite->item && $favorite->item->getFirstMediaUrl('images'))
                                        <img src="{{ $favorite->item->getFirstMediaUrl('images') }}" alt="{{ $favorite->item->name }}">
                                    @else
                                        <img src="{{ asset('assets/img/default-image.jpg') }}" alt="Default Image">
                                    @endif
                                </a>
                                <button class="remove-favorite-btn" data-id="{{ $favorite->id }}" style="position: absolute; top: 10px; right: 10px; background: none; border: none; font-size: 20px; color: #ff0000;">
                                    <i class="fas fa-minus-circle"></i>
                                </button>
                            </div>
                            <div class="game-card__info">
                                <a class="game-card__title" href="{{ route('item.show', ['id' => $favorite->subItem ? $favorite->subItem->item->id : $favorite->item_id]) }}">
                                    {{ $favorite->subItem ? $favorite->subItem->name : $favorite->item->name }}
                                </a>
                                <div class="game-card__genre">
                                    {{ $favorite->subItem ? $favorite->subItem->amount : 'N/A' }}
                                </div>
                                <div class="game-card__rating-and-price">
                                    <div class="game-card__price">
                                        <span>
                                            ${{ number_format($favorite->subItem->price + ($favorite->subItem->price * $config->fee / 100), 2) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="uk-width-1-1">
                    <p>No favourites found.</p>
                </div>
            @endforelse
        </div>
    </main>
    @if(session('success'))
        @push('scripts')
            <script>
                toastr.success("{{ session('success') }}");
            </script>
        @endpush
    @endif
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Remove from favorites
            $('.remove-favorite-btn').on('click', function() {
                var favoriteId = $(this).data('id');
                var $favoriteCard = $(this).closest('.game-card');

                $.ajax({
                    url: '{{ route("favorites.remove") }}', // Assuming you have a route named 'favorites.remove'
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: favoriteId
                    },
                    success: function(response) {
                        if(response.success) {
                            $favoriteCard.remove();
                            toastr.success('Removed from favorites successfully.');
                        } else {
                            toastr.error('Failed to remove from favorites.');
                        }
                    },
                    error: function(xhr) {
                        toastr.error('An error occurred while removing from favorites.');
                    }
                });
            });
        });
    </script>
@endsection
