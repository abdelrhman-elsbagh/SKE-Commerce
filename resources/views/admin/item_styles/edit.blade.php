@extends('layouts.vertical', ['page_title' => 'Edit Item Style'])

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
                    <h4 class="page-title">Edit Item Style</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form id="edit-style-form" action="{{ route('item_styles.update', $itemStyle->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="xl" class="form-label">Large Screen (XL)</label>
                                <select class="form-control" id="xl" name="xl">
                                    <option value="8" {{ $itemStyle->xl == 8 ? 'selected' : '' }}>8</option>
                                    <option value="6" {{ $itemStyle->xl == 6 ? 'selected' : '' }}>6</option>
                                    <option value="4" {{ $itemStyle->xl == 4 ? 'selected' : '' }}>4</option>
                                    <option value="3" {{ $itemStyle->xl == 3 ? 'selected' : '' }}>3</option>
                                    <option value="2" {{ $itemStyle->xl == 2 ? 'selected' : '' }}>2</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="lg" class="form-label">Medium-Large Screen (LG)</label>
                                <select class="form-control" id="lg" name="lg">
                                    <option value="8" {{ $itemStyle->lg == 8 ? 'selected' : '' }}>8</option>
                                    <option value="6" {{ $itemStyle->lg == 6 ? 'selected' : '' }}>6</option>
                                    <option value="4" {{ $itemStyle->lg == 4 ? 'selected' : '' }}>4</option>
                                    <option value="3" {{ $itemStyle->lg == 3 ? 'selected' : '' }}>3</option>
                                    <option value="2" {{ $itemStyle->lg == 2 ? 'selected' : '' }}>2</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="md" class="form-label">Medium Screen (MD)</label>
                                <select class="form-control" id="md" name="md">
                                    <option value="8" {{ $itemStyle->md == 8 ? 'selected' : '' }}>8</option>
                                    <option value="6" {{ $itemStyle->md == 6 ? 'selected' : '' }}>6</option>
                                    <option value="4" {{ $itemStyle->md == 4 ? 'selected' : '' }}>4</option>
                                    <option value="3" {{ $itemStyle->md == 3 ? 'selected' : '' }}>3</option>
                                    <option value="2" {{ $itemStyle->md == 2 ? 'selected' : '' }}>2</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="sm" class="form-label">Small Screen (SM)</label>
                                <select class="form-control" id="sm" name="sm">
                                    <option value="8" {{ $itemStyle->sm == 8 ? 'selected' : '' }}>8</option>
                                    <option value="6" {{ $itemStyle->sm == 6 ? 'selected' : '' }}>6</option>
                                    <option value="4" {{ $itemStyle->sm == 4 ? 'selected' : '' }}>4</option>
                                    <option value="3" {{ $itemStyle->sm == 3 ? 'selected' : '' }}>3</option>
                                    <option value="2" {{ $itemStyle->sm == 2 ? 'selected' : '' }}>2</option>
                                    <option value="2" {{ $itemStyle->sm == 1 ? 'selected' : '' }}>1</option>
                                </select>
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

            $('#edit-style-form').on('submit', function(e) {
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
                            text: 'Styles updated successfully.',
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
                            text: 'There was an error updating the styles.',
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
