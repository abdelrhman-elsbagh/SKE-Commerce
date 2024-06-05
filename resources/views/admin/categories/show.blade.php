@extends('layouts.vertical', ['page_title' => 'Category Details'])

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Category Details</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <p id="name">{{ $category->name }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <p id="description">{{ $category->description }}</p>
                        </div>
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
