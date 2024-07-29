@extends('layouts.vertical', ['page_title' => 'Notification Details'])

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Notification Details</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <p id="title">{{ $notification->title }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <p id="description">{{ $notification->description }}</p>
                        </div>
                        <div class="mb-3">
                            <label for="attachment" class="form-label">Attachment</label>
                            <div id="attachment">
                                @if($notification->getMedia('attachments')->isNotEmpty())
                                    <img src="{{ $notification->getFirstMediaUrl('attachments') }}" alt="Notification Attachment" style="max-width: 200px;">
                                @else
                                    <p>No attachment available</p>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('notifications.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
