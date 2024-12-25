@extends('layouts.admin.admin')
@section('title', 'Portals')

@section('content')
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
    <!--start page wrapper -->
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Admin</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('portal.index') }}">Portal List</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">New Portal</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="card" style="padding-top: 15px;">
                <div class="col-xl-9 mx-auto w-100">
                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="alert alert-success mt-3">
                            {{ session('success') }}
                        </div>
                    @endif
                    <h6 class="mb-0 text-uppercase text-primary">Create Portals</h6>
                    <hr />
                    <div class="">

                        <div class="card-body">

                            <form action="{{ route('portal.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-sm-4">
                                        <label for="city_name">City Name</label>
                                        <input class="form-control" type="text" name="city_name" id="city_name"
                                            value="{{ old('city_name') }}" required>
                                        @error('city_name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="city_id">City ID</label>
                                        <input class="form-control" type="number" name="city_id" id="city_id"
                                            value="{{ old('city_id') }}" required>
                                        @error('city_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="city_code">City Code</label>
                                        <input class="form-control" type="text" name="city_code" id="city_code"
                                            value="{{ old('city_code') }}" required>
                                        @error('city_code')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>


                                <div class="row mt-3">
                                    <div class="col-sm-6">
                                        <label for="city_name_local">City Name Local</label>
                                        <input class="form-control" type="text" name="city_name_local"
                                            id="city_name_local" value="{{ old('city_name_local') }}">
                                        @error('city_name_local')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="city_slogan">City Slogan</label>
                                        <input class="form-control" type="text" name="city_slogan" id="city_slogan"
                                            value="{{ old('city_slogan') }}">
                                        @error('city_slogan')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>



                                <div class="row mt-3">
                                    <div class="col-sm-6">
                                        <label for="local_lang">Local Language</label>
                                        <input class="form-control" type="text" name="local_lang" id="local_lang"
                                            value="{{ old('local_lang') }}">
                                        @error('local_lang')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="map_link">Slug</label>
                                        <input class="form-control" type="text" name="slug" id="slug"
                                            value="{{ old('slug') }}">
                                        @error('slug')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label for="map_link">Map Link</label>
                                    <input class="form-control" type="text" name="map_link" id="map_link"
                                        value="{{ old('map_link') }}">
                                    @error('map_link')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>


                                <!-- Weather Widget Code -->
                                <div class="mt-3">
                                    <label for="weather_widget_code">Weather Widget Code</label>
                                    <textarea class="form-control" name="weather_widget_code" id="weather_widget_code">{{ old('weather_widget_code') }}</textarea>
                                    @error('weather_widget_code')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Sports Widget Code -->
                                <div class="mt-3">
                                    <label for="sports_widget_code">Sports Widget Code</label>
                                    <textarea class="form-control" name="sports_widget_code" id="sports_widget_code">{{ old('sports_widget_code') }}</textarea>
                                    @error('sports_widget_code')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- News Widget Code -->
                                <div class="mt-3">
                                    <label for="news_widget_code">News Widget Code</label>
                                    <textarea class="form-control" name="news_widget_code" id="news_widget_code">{{ old('news_widget_code') }}</textarea>
                                    @error('news_widget_code')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Local Matrics -->
                                <div class="mt-3">
                                    <label for="local_matrics">Local Metrics</label>
                                    <textarea class="form-control" name="local_matrics" id="local_matrics">{{ old('local_matrics') }}</textarea>
                                    @error('local_matrics')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Header Image -->
                                <div class="row">
                                    <!-- Header Image -->
                                    <div class="col-md-4">
                                        <label for="header_image">Header Image</label>
                                        <input class="form-control" type="file" name="header_image" id="header_image"
                                            accept="image/*" onchange="previewImage(this, 'header_preview')">
                                        @error('header_image')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        <div class="mt-2">
                                            <img id="header_preview" src="#" alt="Header Image Preview"
                                                style="display: none; max-height: 100px;">
                                        </div>
                                    </div>

                                    <!-- Footer Image -->
                                    <div class="col-md-4">
                                        <label for="footer_image">Footer Image</label>
                                        <input class="form-control" type="file" name="footer_image" id="footer_image"
                                            accept="image/*" onchange="previewImage(this, 'footer_preview')">
                                        @error('footer_image')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        <div class="mt-2">
                                            <img id="footer_preview" src="#" alt="Footer Image Preview"
                                                style="display: none; max-height: 100px;">
                                        </div>
                                    </div>

                                    <!-- Local Info Image -->
                                    <div class="col-md-4">
                                        <label for="local_info_image">Local Info Image</label>
                                        <input class="form-control" type="file" name="local_info_image"
                                            id="local_info_image" accept="image/*"
                                            onchange="previewImage(this, 'local_info_preview')">
                                        @error('local_info_image')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        <div class="mt-2">
                                            <img id="local_info_preview" src="#" alt="Local Info Image Preview"
                                                style="display: none; max-height: 100px;">
                                        </div>
                                    </div>
                                </div>

                                <button class="text-end btn btn-success  mt-4" type="submit">Create City</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input, previewId) {
            const file = input.files[0];
            const preview = document.getElementById(previewId);

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = "block"; // Show the preview image
                };
                reader.readAsDataURL(file);
            } else {
                preview.style.display = "none"; // Hide the preview if no file selected
            }
        }
    </script>
    <script>
        // Select all textarea elements
        document.querySelectorAll('textarea').forEach(function(textarea) {
            ClassicEditor
                .create(textarea)
                .catch(error => {
                    console.error(error);
                });
        });
    </script>
@endsection
