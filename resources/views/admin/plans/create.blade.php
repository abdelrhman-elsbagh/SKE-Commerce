@extends('layouts.vertical', ['page_title' => isset($plan) ? 'Edit Plan' : 'Create Plan'])

@section('css')
    @vite(['node_modules/jquery-toast-plugin/dist/jquery.toast.min.css'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">{{ isset($plan) ? 'Edit Plan' : 'Create Plan' }}</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form id="create-edit-plan-form" action="{{ isset($plan) ? route('plans.update', $plan->id) : route('plans.store') }}" method="POST">
                            @csrf
                            @if(isset($plan))
                                @method('PUT')
                            @endif
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $plan->name ?? '' }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ $plan->price ?? '' }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="duration" class="form-label">Duration (days)</label>
                                <input type="number" class="form-control" id="duration" name="duration" value="{{ $plan->duration ?? '' }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description">{{ $plan->description ?? '' }}</textarea>
                            </div>

                            <div id="features-container">
                                <h5>Features</h5>
                                <button type="button" class="btn btn-secondary mb-3" id="add-feature-btn">Add Feature</button>
                                <div id="features">
                                    @if(isset($plan))
                                        @foreach($plan->features as $feature)
                                            <div class="feature" data-id="{{ $feature->id }}">
                                                <input type="hidden" name="features[{{ $loop->index }}][id]" value="{{ $feature->id }}">
                                                <div class="mb-3">
                                                    <label for="feature_name_{{ $loop->index }}" class="form-label">Feature Name</label>
                                                    <input type="text" class="form-control" id="feature_name_{{ $loop->index }}" name="features[{{ $loop->index }}][name]" value="{{ $feature->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="feature_description_{{ $loop->index }}" class="form-label">Feature Description</label>
                                                    <textarea class="form-control" id="feature_description_{{ $loop->index }}" name="features[{{ $loop->index }}][description]">{{ $feature->description }}</textarea>
                                                </div>
                                                <button type="button" class="btn btn-danger remove-feature-btn">Remove Feature</button>
                                                <hr>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @vite(['node_modules/jquery-toast-plugin/dist/jquery.toast.min.js'])

    <script>
        $(document).ready(function() {
            let featureCount = $('#features .feature').length;

            $('#add-feature-btn').click(function() {
                const featureHTML = `
                    <div class="feature" data-temp-id="${featureCount}">
                        <div class="mb-3">
                            <label for="feature_name_${featureCount}" class="form-label">Feature Name</label>
                            <input type="text" class="form-control" id="feature_name_${featureCount}" name="features[${featureCount}][name]" required>
                        </div>
                        <div class="mb-3">
                            <label for="feature_description_${featureCount}" class="form-label">Feature Description</label>
                            <textarea class="form-control" id="feature_description_${featureCount}" name="features[${featureCount}][description]"></textarea>
                        </div>
                        <button type="button" class="btn btn-danger remove-feature-btn">Remove Feature</button>
                        <hr>
                    </div>
                `;

                $('#features').append(featureHTML);
                featureCount++;
            });

            $(document).on('click', '.remove-feature-btn', function() {
                $(this).closest('.feature').remove();
            });

            $('#create-edit-plan-form').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $.toast({
                            heading: 'Success',
                            text: 'Plan saved successfully.',
                            icon: 'success',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000
                        });
                        setTimeout(function() {
                            window.location.href = "{{ route('plans.index') }}";
                        }, 3000);
                    },
                    error: function(response) {
                        $.toast({
                            heading: 'Error',
                            text: 'There was an error saving the plan.',
                            icon: 'error',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000
                        });
                    }
                });
            });
        });
    </script>
@endsection
