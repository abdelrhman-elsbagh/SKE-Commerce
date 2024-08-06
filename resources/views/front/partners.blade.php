@extends('front.layout')

@section('title', ($config->name ?? "") . "- Partners")

@section('content')
    <main class="page-main">
        <div class="">
            <div class="uk-grid uk-child-width-1-3@m uk-child-width-1-2@s uk-grid-small" data-uk-grid>
                @foreach($partners as $partner)
                    <div class="partner-card-container">
                        <div class="uk-card uk-card-default uk-card-hover uk-margin uk-flex uk-flex-column">
                            <div class="uk-card-header">
                                <h3 class="uk-card-title">{{ $partner->name }}</h3>
                            </div>
                            <div class="uk-card-body uk-flex-1">
                                <p class="description">{!! $partner->description !!}</p>
                                @if($partner->getFirstMediaUrl('partner_images'))
                                    <img src="{{ $partner->getFirstMediaUrl('partner_images') }}" alt="{{ $partner->name }}" class="" style="height: 250px;width: 100%;border-radius: 10px">
                                @else
                                    <img src="{{ asset('assets/img/default-image.jpg') }}" alt="Default Image" class="uk-width-1-1">
                                @endif
                            </div>
                            <div class="uk-card-footer">
                                <a href="{{ $partner->facebook }}" target="_blank" class="btn btn-primary"><i class="fab fa-facebook"></i> Facebook</a>
                                <a style="float: right" href="https://wa.me/{{ $partner->whatsapp }}" target="_blank" class="btn btn-success"><i class="fab fa-whatsapp"></i> Whatsapp</a>
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
    <style>
        .uk-card-body {
            display: flex;
            flex-direction: column;
        }
        .description {
            flex: 1;
        }
        .uk-card-footer {
            display: flex;
            justify-content: space-between;
        }
        .partner-card-container {
            display: flex;
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.uk-card');
            const descriptions = document.querySelectorAll('.description');
            const footers = document.querySelectorAll('.uk-card-footer');

            let maxCardHeight = 0;
            let maxDescriptionHeight = 0;
            let maxFooterHeight = 0;

            // Calculate the maximum heights
            cards.forEach(card => {
                maxCardHeight = Math.max(maxCardHeight, card.offsetHeight);
            });
            descriptions.forEach(description => {
                maxDescriptionHeight = Math.max(maxDescriptionHeight, description.offsetHeight);
            });
            footers.forEach(footer => {
                maxFooterHeight = Math.max(maxFooterHeight, footer.offsetHeight);
            });

            // Apply the maximum heights
            // cards.forEach(card => {
            //     card.style.height = `${maxCardHeight}px`;
            // });
            descriptions.forEach(description => {
                description.style.height = `${maxDescriptionHeight}px`;
            });
            footers.forEach(footer => {
                footer.style.height = `${maxFooterHeight}px`;
            });
        });
    </script>
@endsection
