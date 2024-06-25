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
                                <div class="game-card {{ $item->status == 'inactive' ? 'inactive' : '' }}">
                                    <div class="game-card__box">
                                        <div class="game-card__media">
                                            <a href="{{ $item->status == 'active' ? route('item.show', ['id' => $item->id]) : '#' }}" class="{{ $item->status == 'inactive' ? 'disabled-link' : '' }}">
                                                @if ($item->getFirstMediaUrl('images'))
                                                    <img src="{{ $item->getFirstMediaUrl('images') }}" alt="{{ $item->name }}">
                                                @else
                                                    <img src="{{ asset('assets/img/default-game.jpg') }}" alt="Default Image">
                                                @endif
                                            </a>
                                        </div>
                                        <div class="game-card__info">
                                            <a class="game-card__title" href="{{ $item->status == 'active' ? route('item.show', ['id' => $item->id]) : '#' }}">{{ $item->name }}</a>
                                            @if($item->title_type != 'default')
                                                <div class="card-tag {{ $item->title_type == 'discount' ? 'card-tag-discount' : ($item->title_type == 'new' ? 'card-tag-new' : '') }}">{{ $item->title }}</div>
                                            @endif
                                            @foreach($item->tags as $tag)
                                                <span class="game-card__genre">{{ $tag->name }}{{ !$loop->last ? ' - ' : '' }}</span>
                                            @endforeach
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

    <style>
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
        .card-tag-discount {
            background-color: red;
        }
        .card-tag-new {
            background-color: yellow;
            color: #2d2d2d;
        }
        .game-card__box {
            position: relative;
            overflow: hidden;
        }
    </style>
@endsection
