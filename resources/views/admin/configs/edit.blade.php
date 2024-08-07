@extends('layouts.vertical', ['page_title' => 'Edit Configuration'])

@section('css')
    @vite([
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.css'
    ])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Edit Configuration</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form id="edit-config-form" action="{{ route('configs.update', $config->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $config->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description">{{ $config->description }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="whatsapp" class="form-label">WhatsApp</label>
                                <textarea class="form-control" id="whatsapp" name="whatsapp">{{ $config->whatsapp }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="telegram" class="form-label">Telegram</label>
                                <textarea class="form-control" id="telegram" name="telegram">{{ $config->telegram }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <textarea class="form-control" id="phone" name="phone">{{ $config->phone }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="facebook" class="form-label">Facebook</label>
                                <textarea class="form-control" id="facebook" name="facebook">{{ $config->facebook }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="fee" class="form-label">Fee (%)</label>
                                <input type="number" step="0.01" class="form-control" id="fee" name="fee" value="{{ $config->fee }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="font" class="form-label">Font</label>
                                <select class="form-control" id="font" name="font">
                                    <option value="Nunito" {{ $config->font == 'Nunito' ? 'selected' : '' }}>Nunito</option>
                                    <option value="Oswald" {{ $config->font == 'Oswald' ? 'selected' : '' }}>Oswald</option>
                                    <option value="Noto Sans" {{ $config->font == 'Noto Sans' ? 'selected' : '' }}>Noto Sans</option>
                                    <option value="Raleway" {{ $config->font == 'Raleway' ? 'selected' : '' }}>Raleway</option>
                                    <option value="Roboto" {{ $config->font == 'Roboto' ? 'selected' : '' }}>Roboto</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="logo" class="form-label">Logo</label>
                                <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                                <div class="mt-2" id="logo-preview">
                                    @if($config->getFirstMediaUrl('logos'))
                                        <img src="{{ $config->getFirstMediaUrl('logos') }}" alt="{{ $config->name }}" style="max-width: 200px;">
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
    @vite([
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.js'
    ])

    <script>
        $(document).ready(function() {
            $('#logo').change(function() {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#logo-preview').html('<img src="' + e.target.result + '" alt="Logo Preview" style="max-width: 200px;">');
                }
                reader.readAsDataURL(this.files[0]);
            });

            $('#edit-config-form').on('submit', function(e) {
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
                            text: 'Config updated successfully.',
                            icon: 'success',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000
                        });
                    },
                    error: function(response) {
                        $.toast({
                            heading: 'Error',
                            text: 'There was an error updating the config.',
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
