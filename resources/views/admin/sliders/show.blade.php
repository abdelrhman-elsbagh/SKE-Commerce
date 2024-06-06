@extends('layouts.vertical', ['page_title' => 'Slider Details'])

@section('css')
    @vite([
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.css'
    ])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Slider Details</h4>
                        <table class="table table-bordered">
                            <tr>
                                <th>ID</th>
                                <td>{{ $slider->id }}</td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td>{{ $slider->name }}</td>
                            </tr>
                            <tr>
                                <th>Image</th>
                                <td><img src="{{ $slider->getFirstMediaUrl('images') }}" alt="{{ $slider->name }}" style="max-width: 200px;"></td>
                            </tr>
                        </table>
                        <a href="{{ route('sliders.index') }}" class="btn btn-primary">Back</a>
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
@endsection
