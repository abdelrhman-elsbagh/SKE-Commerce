@extends('front.layout')

@section('title', ($config->name ?? "") . "- Partners")

@section('content')
    <main class="page-main">
        <div class="">
            <div class="uk-grid uk-child-width-1-3@m uk-child-width-1-2@s uk-grid-small" data-uk-grid>
                @foreach($partners as $partner)
                    <div class="partner-card-container">
                        <div class="uk-card uk-card-default uk-card-hover uk-margin uk-flex uk-flex-column partner-card">
                            <div class="uk-card-header">
                                <h3 class="uk-card-title">{{ $partner->name }}</h3>
                            </div>
                            <div class="uk-card-body uk-flex-1">
                                <div class="description agent-desc" style="padding: 0;margin: 0">{!! $partner->description !!}</div>

                                <p></p>
                                @if($partner->getFirstMediaUrl('partner_images'))
                                    <img src="{{ $partner->getFirstMediaUrl('partner_images') }}" alt="{{ $partner->name }}" class="" style="height: 250px;width: 100%;border-radius: 10px">
                                @else
                                    <img src="{{ asset('assets/img/default-image.jpg') }}" alt="Default Image" class="uk-width-1-1">
                                @endif
                            </div>
                            <div class="uk-card-footer">
                                <span class="bold">Contact us</span>
                                <div class="social-icons" style="float: right">
                                    @if($partner->facebook)
                                        <a href="{{ $partner->facebook }}" target="_blank" class="btn facebook-icon"><i class="fab fa-facebook-f"></i></a>
                                    @endif
                                    @if($partner->whatsapp)
                                        <a href="https://wa.me/{{ $partner->whatsapp }}" target="_blank" class="btn whatsapp-icon"><i class="fab fa-whatsapp"></i></a>
                                    @endif
                                    @if($partner->insta)
                                        <a href="{{ $partner->insta }}" target="_blank" class="btn instagram-icon"><i class="fab fa-instagram"></i></a>
                                    @endif
                                    @if($partner->telegram)
                                        <a href="{{ $partner->telegram }}" target="_blank" class="btn telegram-icon"><i class="fab fa-telegram-plane"></i></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
@endsection

@section('css')
    @vite(['node_modules/jquery-toast-plugin/dist/jquery.toast.min.css'])
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.uk-card');
            let maxCardHeight = 0;

            // Calculate the maximum height of all cards
            cards.forEach(card => {
                maxCardHeight = Math.max(maxCardHeight, card.offsetHeight);
            });

            // Apply the maximum height to all cards
            cards.forEach(card => {
                card.style.height = `${maxCardHeight}px`;
            });
        });
    </script>
@endsection
