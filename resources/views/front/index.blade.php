@extends('front.layout')

@section('title', 'Ske E-Commerce')

@section('content')
    <main class="page-main">
        <div class="uk-width-4-5@l uk-width-3-3@m uk-width-3-3@s uk-margin-auto">
            <h3 class="uk-text-lead">Recommended & Featured</h3>
            <div class="js-recommend">
                <div class="swiper">
                    <div class="swiper-wrapper">
                        @foreach ($sliders as $slider)
                            <div class="swiper-slide">
                                <div class="recommend-slide">
                                    <div class="tour-slide__box">
                                        <a href="{{route('home')}}">
                                            @if($slider->getFirstMediaUrl('images'))
                                                <img src="{{ $slider->getFirstMediaUrl('images') }}" alt="{{ $slider->name }}" style="width: 100%">
                                            @else
                                                <img src="{{ asset('assets/img/default-slider.jpg') }}" alt="Default Slider" style="width: 100%">
                                            @endif
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="swipper-nav">
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>

        </div>

        <div class="uk-grid uk-child-width-1-6@xl uk-child-width-1-5@m uk-child-width-1-3@s uk-grid-small" data-uk-grid>
            @foreach ($categorizedItems as $categoryName => $items)
                <div class="uk-width-1-1">
                    <h3>{{ $categoryName }}</h3>
                    <div class="uk-grid uk-child-width-1-6@xl uk-child-width-1-5@m uk-child-width-1-3@s uk-grid-small">
                        @foreach ($items as $item)
                            <div class="uk-width-1-6@xl uk-width-1-5@m uk-width-1-3@s" style="margin-top: 15px;">
                                <div class="game-card">
                                    <div class="game-card__box">
                                        <div class="game-card__media">
                                            <a href="{{route('item.show', ['id' => $item->id])}}">
                                                @if ($item->getFirstMediaUrl('images'))
                                                    <img src="{{ $item->getFirstMediaUrl('images') }}" alt="{{ $item->name }}">
                                                @else
                                                    <img src="{{ asset('assets/img/default-game.jpg') }}" alt="Default Image">
                                                @endif
                                            </a>
                                        </div>
                                        <div class="game-card__info">
                                            <a class="game-card__title" href="10_game-profile.html">{{ $item->name }}</a>
                                            <div class="game-card__genre">{{ $item->category->name }}</div>
                                            <div class="game-card__rating-and-price">
                                                <div class="game-card__rating"><span>{{ $item->rating ?? 'N/A' }}</span><i class="ico_star"></i></div>
                                                <div class="game-card__price"><span>${{ number_format($item->getPriceInUsdAttribute() ?? 0, 2) }}</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
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
