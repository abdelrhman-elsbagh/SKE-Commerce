@extends('front.layout')

@section('title', 'Plans')

@section('content')
    <main class="page-main">
        <div class="uk-container">
            <h1 class="uk-heading-line"><span>Our Plans</span></h1>
            <div class="uk-grid uk-child-width-1-3@m uk-child-width-1-2@s uk-grid-small" data-uk-grid>
                @foreach($plans as $plan)
                    <div>
                        <div class="uk-card uk-card-default uk-card-hover uk-margin plan-card">
                            <div class="uk-card-header uk-text-center">
                                <h3 class="uk-card-title">{{ $plan->name }}</h3>
                                <p class="uk-text-meta uk-margin-remove-top">${{ number_format($plan->price, 2) }} / {{ $plan->duration }} days</p>
                            </div>
                            <div class="uk-card-body">
                                <p>{{ $plan->description }}</p>
                                <ul class="uk-list uk-list-bullet uk-margin-top">
                                    @foreach($plan->features as $feature)
                                        <li><i class="fas fa-check-circle uk-margin-small-right" style="color: #f46119;"></i>{{ $feature->name }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="uk-card-footer uk-text-center">
                                <a href="#" class="uk-button uk-button-primary">Choose Plan</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>

    <style>
        .uk-card-header {
            background-color: #f0f0f0;
            padding: 20px;
            border-bottom: 1px solid #ddd;
        }
        .uk-card-body {
            padding: 20px;
        }
        .uk-card-footer {
            background-color: #f0f0f0;
            padding: 20px;
            border-top: 1px solid #ddd;
        }
        .uk-card-title {
            margin-bottom: 10px;
            font-size: 1.5em;
        }
        .uk-text-meta {
            font-size: 1.2em;
            color: #888;
        }
        .uk-button-primary {
            background-color: #f46119;
            border-color: #f46119;
            color: #fff;
        }
    </style>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cards = document.querySelectorAll('.uk-card-body');
        let maxHeight = 0;

        // Calculate the max height
        cards.forEach(card => {
            const cardHeight = card.offsetHeight;
            if (cardHeight > maxHeight) {
                maxHeight = cardHeight;
            }
        });

        // Set all cards to the max height
        cards.forEach(card => {
            card.style.height = `${maxHeight}px`;
        });
    });
</script>
