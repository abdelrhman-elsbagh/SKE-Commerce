@extends('layouts.vertical', ['page_title' => 'Fee Group Details'])

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Fee Group Details</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="fee" class="form-label">Fee</label>
                            <p id="fee">{{ $feeGroup->fee }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <p id="name">{{ $feeGroup->name }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <div id="image">
                                @if($feeGroup->getMedia('images')->isNotEmpty())
                                    <img src="{{ $feeGroup->getFirstMediaUrl('images') }}" alt="Fee Group Image" style="max-width: 200px;">
                                @else
                                    <p>No image available</p>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('fee_groups.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
