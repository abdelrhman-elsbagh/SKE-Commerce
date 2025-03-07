@extends('layouts.vertical', ['page_title' => 'Agents Details'])

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Agents Details</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <p id="name">{{ $partner->name }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <p id="description">{!! $partner->description !!}</p>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Arabic Name</label>
                            <p id="name">{{ $partner->ar_name ?? "" }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Arabic Description</label>
                            <p id="description">{!! $partner->ar_description ?? "" !!}</p>
                        </div>
                        <div class="mb-3">
                            <label for="facebook" class="form-label">Facebook</label>
                            <p id="facebook">{{ $partner->facebook }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="whatsapp" class="form-label">Whatsapp</label>
                            <p id="whatsapp">{{ $partner->whatsapp }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="insta" class="form-label">Instagram</label>
                            <p id="insta">{{ $partner->insta }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="telegram" class="form-label">Telegram</label>
                            <p id="telegram">{{ $partner->telegram }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <div id="image">
                                @if($partner->getFirstMediaUrl('partner_images'))
                                    <img src="{{ $partner->getFirstMediaUrl('partner_images') }}" alt="Partner Image" style="max-width: 200px;">
                                @else
                                    <p>No image available</p>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('partners.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
