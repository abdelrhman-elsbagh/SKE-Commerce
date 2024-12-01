@extends('front.layout')

@section('title', ($config->name ?? "") . "- Home")

@section('content')
    <main class="page-main">
        <div class="uk-width-4-5@l uk-width-3-3@m uk-width-3-3@s uk-margin-auto">
            {{-- <h3 class="uk-text-lead">Recommended & Featured</h3> --}}
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

        <div class="news-bar">
            <span class="rotated" id="news-text">
                {{ App::getLocale() === 'ar' ? $news->ar_news : $news->news ?? "" }}
            </span>
        </div>


        <!-- Search Input Field -->
        <div class="uk-width-4-5@l uk-width-3-3@m uk-width-3-3@s uk-margin-auto uk-margin-top">
            <div class="search-container">
                <input type="text" id="search-items" class="form-control uk-input" placeholder="@lang('messages.search.placeholder')">
                <i class="fa fa-search main-index-search-icon"></i>
            </div>
        </div>

        <div class="uk-grid uk-child-width-1-6@xl uk-child-width-1-5@m uk-child-width-1-3 uk-grid-small" data-uk-grid id="items-container">
            @foreach ($categorizedItems as $categoryName => $items)
                <div class="uk-width-1-1">
                    <h3 class="category-title">{{ $categoryName }}</h3>
                    <div class="uk-grid uk-child-width-1-6@xl uk-child-width-1-5@m uk-child-width-1-3 uk-grid-small">
                        @foreach ($items as $item)
                            <div class="uk-width-1-6@xl uk-width-1-5@m uk-width-1-3 item-card" data-name="{{ strtolower($item->name) }}" style="margin-top: 15px;">
                                <div class="game-card" style="overflow: hidden">
                                    <div class="game-card__box game-card {{ $item->status == 'inactive' ? 'inactive' : '' }}">
                                        <div class="game-card__media">
                                            <a href="{{ $item->status == 'active' ? route('item.show', ['id' => $item->id]) : '#' }}" class="{{ $item->status == 'inactive' ? 'disabled-link' : '' }}">
                                                @if ($item->getFirstMediaUrl('front_image'))
                                                    <img src="{{ $item->getFirstMediaUrl('front_image') }}" alt="{{ $item->name }}">
                                                @else
                                                    <img src="{{ asset('assets/img/default-game.jpg') }}" alt="Default Image">
                                                @endif
                                            </a>
                                        </div>
                                        <div class="game-card__info">
                                            <a class="game-card__title" style="padding-bottom: 0; margin-bottom: 0;" href="{{ $item->status == 'active' ? route('item.show', ['id' => $item->id]) : '#' }}">{{ $item->name }}</a>
                                            @if(!empty($item->title) && ($item->title_type == 'default' || $item->title_type == 'new'))
                                                <div class="card-tag {{ $item->title_type == 'discount' ? 'card-tag-discount' : ($item->title_type == 'new' ? 'card-tag-new' : '') }}">{{ $item->title }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    @if($item->status == 'inactive')
                                        <div class="card-tag card-tag-inactive">inactive</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <footer class="page-footer" style="">
            <div class="uk-container">
                <div class="uk-grid" uk-grid>
                    <div class="uk-width-1-3@m footer-section">
                        <h5 class="footer-title">@lang('messages.footer.registration')</h5>
                        <ul>
                            <li>
                                <a href="{{ route('register-partner') }}">@lang('messages.footer.partner_registration')</a>
                            </li>
                        </ul>
                    </div>
                @foreach($footerItems as $tag => $items)
                        <div class="uk-width-1-3@m footer-section">
                            <h5 class="footer-title">{{ ucfirst($tag) }}</h5>
                            <ul class="footer-ul">
                                @foreach($items as $item)
                                    <li>
                                        <a href="{{ $item->link }}">
                                            @if($item->hasMedia('images'))
                                                <img src="{{ $item->getFirstMediaUrl('images') }}" alt="{{ $item->title }}" style="width: 20px; height: 20px;">
                                            @endif
                                            {{ $item->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>

                <hr />

                <!-- Footer Bottom Section -->
                <p class="mt-2" style="margin-bottom: 5px; text-align: center;">
                    &copy;
                    <script>document.write(new Date().getFullYear())</script>
                    {{ $config->name }} @lang('messages.footer.rights_reserved')
                </p>

            </div>
        </footer>
    </main>
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
                const newsText = document.getElementById('news-text');
                const textLength = newsText.offsetWidth; // Get the width of the text
                const containerWidth = newsText.parentElement.offsetWidth; // Get the width of the container
                const duration = (textLength / containerWidth) * 30; // Adjust the multiplier as needed

                // Set the animation duration
                newsText.style.animationDuration = `${duration}s`;

                // Search functionality
                document.getElementById('search-items').addEventListener('keyup', function() {
                    const query = this.value.toLowerCase();
                    document.querySelectorAll('.item-card').forEach(function(itemCard) {
                        const itemName = itemCard.getAttribute('data-name');
                        if (itemName.includes(query)) {
                            itemCard.style.display = 'block';
                        } else {
                            itemCard.style.display = 'none';
                        }
                    });
                });
            });
        </script>
    @endsection

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
        .card-tag-inactive {
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
