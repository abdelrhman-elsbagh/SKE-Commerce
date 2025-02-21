@extends('layouts.vertical', ['page_title' => 'Edit Configuration'])

@section('css')
    <style>
        #color-swatches {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }

        .color-swatch {
            width: 40px;
            height: 40px;
            border-radius: 5px;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .color-swatch:hover {
            border-color: #4c4c4c;
        }

    </style>

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
                                <label for="seo_description" class="form-label">SEO Description</label>
                                <textarea class="form-control" id="seo_description" name="seo_description">{{ $config->seo_description }}</textarea>
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
                                <label for="main_color" class="form-label">Main Color</label>

                                <!-- Predefined Color Squares -->
                                <div id="color-swatches" class="d-flex">
                                    <div class="color-swatch" style="background-color: #F46119;" data-color="#F46119"></div>
                                    <div class="color-swatch" style="background-color: #3498db;" data-color="#3498db"></div>
                                    <div class="color-swatch" style="background-color: #2ecc71;" data-color="#2ecc71"></div>
                                    <div class="color-swatch" style="background-color: #e74c3c;" data-color="#e74c3c"></div>
                                    <div class="color-swatch" style="background-color: #9b59b6;" data-color="#9b59b6"></div>
                                    <!-- Add more color swatches as needed -->
                                </div>

                                <!-- Color Input (for custom color) -->
                                <input type="color" class="form-control mt-2" id="main_color_input" name="main_color" value="{{ old('main_color', $config->main_color ?? '#F46119') }}">
                            </div>


                            <div class="mb-3">
                                <label for="font" class="form-label">English Font</label>
                                <select class="form-control" id="font" name="font">
                                    <option value="Nunito" {{ $config->font == 'Nunito' ? 'selected' : '' }}>Nunito</option>
                                    <option value="Oswald" {{ $config->font == 'Oswald' ? 'selected' : '' }}>Oswald</option>
                                    <option value="Noto Sans" {{ $config->font == 'Noto Sans' ? 'selected' : '' }}>Noto Sans</option>
                                    <option value="Raleway" {{ $config->font == 'Raleway' ? 'selected' : '' }}>Raleway</option>
                                    <option value="Roboto" {{ $config->font == 'Roboto' ? 'selected' : '' }}>Roboto</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="ar_font" class="form-label">Arabic Font</label>
                                <select class="form-control" id="ar_font" name="ar_font">
                                    <option value="Cairo" {{ $config->ar_font == 'Cairo' ? 'selected' : '' }}>Cairo</option>
                                    <option value="Almarai" {{ $config->ar_font == 'Almarai' ? 'selected' : '' }}>Almarai</option>
                                    <option value="Amiri" {{ $config->ar_font == 'Amiri' ? 'selected' : '' }}>Amiri</option>
                                    <option value="Marhey" {{ $config->ar_font == 'Marhey' ? 'selected' : '' }}>Marhey</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="fav_icon" class="form-label">Fav Icon</label>
                                <input type="file" class="form-control" id="fav_icon" name="fav_icon" accept="image/*">
                                <div class="mt-2" id="logo-preview">
                                    @if($config->getFirstMediaUrl('fav_icon'))
                                        <img src="{{ $config->getFirstMediaUrl('fav_icon') }}" alt="{{ $config->name }}" style="max-width: 200px;">
                                    @endif
                                </div>
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

                            <div class="mb-3">
                                <label for="dark_logo_input" class="form-label">Dark Logo</label>
                                <input type="file" class="form-control" id="dark_logo_input" name="dark_logo_input" accept="image/*">
                                <div class="mt-2" id="dark-logo-preview">
                                    @if($config->getFirstMediaUrl('dark_logos'))
                                        <img src="{{ $config->getFirstMediaUrl('dark_logos') }}" alt="{{ $config->name }} Dark Logo" style="max-width: 200px;">
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
            $('.color-swatch').click(function() {
                var selectedColor = $(this).data('color');
                $('#main_color_input').val(selectedColor);
            });

            // Ensure the color input updates when a custom color is selected
            $('#main_color_input').change(function() {
                var selectedColor = $(this).val();
                // Optionally, you can update the swatch to reflect the custom color
                $('#color-swatches').find('.color-swatch').each(function() {
                    $(this).removeClass('selected');
                });
            });

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
