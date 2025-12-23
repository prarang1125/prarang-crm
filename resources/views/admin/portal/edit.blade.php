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
                        <li class="breadcrumb-item active" aria-current="page">Update Portal</li>
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
                    <h6 class="mb-0 text-uppercase text-primary">Edit Portals</h6>
                    <hr />
                    <div class="">

                        <div class="card-body">

                            <form action="{{ route('portal.update', ['portal' => $portal->id]) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-sm-4">
                                        <label for="city_name">City Name</label>
                                        <input class="form-control" type="text" name="city_name" id="city_name"
                                            value="{{ old('city_name', $portal->city_name) }}" required>
                                        @error('city_name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="city_id">City ID</label>
                                        <input class="form-control" type="number" name="city_id" id="city_id"
                                            value="{{ old('city_id', $portal->city_id) }}" required>
                                        @error('city_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="city_code">City Code</label>
                                        <select class="form-control" name="city_code" id="city_code" required>
                                            <option value="">Select City Code</option>
                                            @if (isset($cityCodes))
                                                @foreach ($cityCodes as $cityCode)
                                                    <option value="{{ $cityCode->geographycode }}"
                                                        {{ old('city_code', $portal->city_code) == $cityCode->geographycode ? 'selected' : '' }}>
                                                        {{ $cityCode->geographycode }} - {{ $cityCode->geography }}
                                                    </option>
                                                @endforeach
                                            @else
                                                <option value="{{ $portal->city_code }}" selected>{{ $portal->city_code }}
                                                </option>
                                            @endif
                                        </select>
                                        @error('city_code')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>


                                <div class="row mt-3">
                                    <div class="col-sm-6">
                                        <label for="city_name_local">City Name Local</label>
                                        <input class="form-control" type="text" name="city_name_local"
                                            id="city_name_local"
                                            value="{{ old('city_name_local', $portal->city_name_local) }}">
                                        @error('city_name_local')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="city_slogan">City Slogan</label>
                                        <input class="form-control" type="text" name="city_slogan" id="city_slogan"
                                            value="{{ old('city_slogan', $portal->city_slogan) }}">
                                        @error('city_slogan')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>


                                <div class="row mt-3">
                                    <div class="col-sm-6">
                                        <label for="local_lang">Local Language</label>
                                        <select class="form-control" name="local_lang" id="local_lang">
                                            <option value="">Select Language</option>
                                            <option value="en"
                                                {{ old('local_lang', $portal->local_lang) == 'en' ? 'selected' : '' }}>
                                                English (en)</option>
                                            <option value="hi"
                                                {{ old('local_lang', $portal->local_lang) == 'hi' ? 'selected' : '' }}>
                                                Hindi (hi)</option>
                                            <option value="bn"
                                                {{ old('local_lang', $portal->local_lang) == 'bn' ? 'selected' : '' }}>
                                                Bengali (bn)</option>
                                            <option value="te"
                                                {{ old('local_lang', $portal->local_lang) == 'te' ? 'selected' : '' }}>
                                                Telugu (te)</option>
                                            <option value="mr"
                                                {{ old('local_lang', $portal->local_lang) == 'mr' ? 'selected' : '' }}>
                                                Marathi (mr)</option>
                                            <option value="ta"
                                                {{ old('local_lang', $portal->local_lang) == 'ta' ? 'selected' : '' }}>
                                                Tamil (ta)</option>
                                            <option value="ur"
                                                {{ old('local_lang', $portal->local_lang) == 'ur' ? 'selected' : '' }}>Urdu
                                                (ur)</option>
                                            <option value="gu"
                                                {{ old('local_lang', $portal->local_lang) == 'gu' ? 'selected' : '' }}>
                                                Gujarati (gu)</option>
                                            <option value="kn"
                                                {{ old('local_lang', $portal->local_lang) == 'kn' ? 'selected' : '' }}>
                                                Kannada (kn)</option>
                                            <option value="or"
                                                {{ old('local_lang', $portal->local_lang) == 'or' ? 'selected' : '' }}>Odia
                                                (or)</option>
                                            <option value="pa"
                                                {{ old('local_lang', $portal->local_lang) == 'pa' ? 'selected' : '' }}>
                                                Punjabi (pa)</option>
                                            <option value="ml"
                                                {{ old('local_lang', $portal->local_lang) == 'ml' ? 'selected' : '' }}>
                                                Malayalam (ml)</option>
                                            <option value="as"
                                                {{ old('local_lang', $portal->local_lang) == 'as' ? 'selected' : '' }}>
                                                Assamese (as)</option>
                                        </select>
                                        @error('local_lang')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="map_link">Slug</label>
                                        <input class="form-control" type="text" name="slug" id="slug"
                                            value="{{ old('slug', $portal->slug) }}">
                                        @error('slug')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6 mt-2">
                                        <label for="list_order">Order</label>
                                        <input class="form-control" type="number" name="list_order" id="list_order"
                                            value="{{ old('list_order', $portal->list_order) }}">
                                        @error('list_order')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6 mt-2">
                                        <label for="state">State</label>
                                        <input class="form-control" type="text" name="state" id="state"
                                            value="{{ old('state', $portal->state) }}">
                                        @error('state')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label for="map_link">Map Link</label>
                                    <input class="form-control" type="text" name="map_link" id="map_link"
                                        value="{{ old('map_link', $portal->map_link) }}">
                                    @error('map_link')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <!-- Weather Widget Code -->
                                        <div class="mt-3">
                                            <label for="weather_widget_code">Weather Widget Code</label>
                                            <textarea class="form-control" name="weather_widget_code" id="weather_widget_code">{{ old('weather_widget_code', $portal->weather_widget_code) }}</textarea>
                                            @error('weather_widget_code')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!-- Sports Widget Code -->
                                        <div class="mt-3">
                                            <label for="sports_widget_code">Sports Widget Code</label>
                                            <textarea class="form-control" name="sports_widget_code" id="sports_widget_code">{{ old('sports_widget_code', $portal->sports_widget_code) }}</textarea>
                                            @error('sports_widget_code')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!-- News Widget Code -->
                                        <div class="mt-3">
                                            <label for="news_widget_code">News Widget Code</label>
                                            <textarea class="form-control" name="news_widget_code" id="news_widget_code">{{ old('news_widget_code', $portal->news_widget_code) }}</textarea>
                                            @error('news_widget_code')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <!-- Local Matrics -->
                                        <div class="mt-3">
                                            <label for="local_matrics">Local Metrics</label>
                                            <textarea class="form-control ckeditorinit" name="local_matrics" id="local_matrics">{{ old('local_matrics', $portal->local_matrics) }}</textarea>
                                            @error('local_matrics')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="mt-3 col-sm-6">
                                        <label for="header_scripts">Header Scripts</label>
                                        <textarea class="form-control " rows="8" name="header_scripts" id="header_scripts">{{ old('header_scripts', $portal->header_scripts) }}</textarea>
                                        @error('header_scripts')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mt-3 col-sm-6">
                                        <label for="footer_scripts">Footer Scripts</label>
                                        <textarea class="form-control " rows="8" name="footer_scripts" id="footer_scripts">{{ old('footer_scripts', $portal->footer_scripts) }}</textarea>
                                        @error('footer_scripts')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mt-3 col-sm-6">
                                        <label for="books">Books (JSON)</label>
                                        <textarea class="form-control " rows="8" name="books" id="books">{{ old('books', $portal->books) }}</textarea>
                                        @error('books')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mt-3 col-sm-6">
                                        <label for="links">Links (JSON)</label>
                                        <textarea class="form-control " rows="8" name="links" id="links">{{ old('links', $portal->links) }}</textarea>
                                        @error('links')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label for="viewership">Viewership Text (HTML)</label>
                                    <textarea class="form-control " rows="8" name="viewership" id="viewership">{{ old('viewership', $portal->viewership) }}</textarea>
                                    @error('viewership')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <br>
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
                                            @if ($portal->header_image)
                                                <img id="header_preview" src="{{ Storage::url($portal->header_image) }}"
                                                    alt="Header Image Preview" style="max-height: 100px;">
                                            @else
                                                <img id="header_preview" src="#" alt="Header Image Preview"
                                                    style="display: none; max-height: 100px;">
                                            @endif
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
                                            @if ($portal->footer_image)
                                                <img id="footer_preview" src="{{ Storage::url($portal->footer_image) }}"
                                                    alt="Footer Image Preview" style="max-height: 100px;">
                                            @else
                                                <img id="footer_preview" src="#" alt="Footer Image Preview"
                                                    style="display: none; max-height: 100px;">
                                            @endif
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
                                            @if ($portal->local_info_image)
                                                <img id="local_info_preview"
                                                    src="{{ Storage::url($portal->local_info_image) }}"
                                                    alt="Local Info Image Preview" style="max-height: 100px;">
                                            @else
                                                <img id="local_info_preview" src="#"
                                                    alt="Local Info Image Preview"
                                                    style="display: none; max-height: 100px;">
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <button class="text-end btn btn-success  mt-4" type="submit">Update Portal</button>
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
        document.querySelectorAll('.ckeditorinit').forEach(function(textarea) {
            ClassicEditor
                .create(textarea)
                .catch(error => {
                    console.error(error);
                });
        });
    </script>

@endsection
