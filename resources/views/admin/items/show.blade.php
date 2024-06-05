@extends('layouts.vertical', ['page_title' => 'Item Details'])

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Item Details</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <p id="name">{{ $item->name }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <p id="description">{{ $item->description }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="price_in_diamonds" class="form-label">Price in Diamonds</label>
                            <p id="price_in_diamonds">{{ $item->price_in_diamonds }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <p id="category">{{ $item->category->name }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <div id="image">
                                @if($item->getMedia('images')->isNotEmpty())
                                    <img src="{{ $item->getFirstMediaUrl('images') }}" alt="Item Image" style="max-width: 200px;">
                                @else
                                    <p>No image available</p>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('items.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
