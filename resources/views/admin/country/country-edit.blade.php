@extends('layouts.admin.admin')
@section('title', 'Country Edit')

@section('content')
<!--start page wrapper -->
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Admin</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/country/country-listing')}}"><i class="bx bx-user"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Country Edit</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="card" style="padding-top: 15px;">
            <div class="col-xl-9 mx-auto w-100">
                <!-- Success Message -->
                @if(session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                @endif
                <h6 class="mb-0 text-uppercase text-primary">Edit Country</h6>
                <hr/>
                <form  action="{{ route('admin.country-update' , $mcountry->countryId) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <label for="countryNameInEnglish" class="form-label">Country Name In English</label>
                            <input type="text" class="form-control  @error('countryNameInEnglish') is-invalid @enderror" id="countryNameInEnglish" name="countryNameInEnglish" value="{{ old('countryNameInEnglish' ,$mcountry->countryNameInEnglish) }} " >
                            @error('countryNameInEnglish')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="countryNameInUnicode" class="form-label">Country Name In Hindi</label>
                            <input type="text" class="form-control  @error('countryNameInUnicode') is-invalid @enderror" id="countryNameInUnicode" name="countryNameInUnicode" value="{{ old('countryNameInUnicode' , $mcountry->countryNameInUnicode) }}" >
                            @error('countryNameInUnicode')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control @error('countryImage') is-invalid @enderror" id="countryImage" name="countryImage">
                            @error('countryImage')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                            @if ($mcountry->Image)
                                <div class="mt-2">
                                    <p>Current Image:</p>
                                    <!-- Display the image -->
                                    <img src="{{ asset('uploads/country_images/' . $mcountry->Image) }}" alt="Country Image" width="100">
                                    <!-- Display the image name -->
                                    <p>Image Name: {{ $mcountry->Image_Name }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="map" class="form-label">Map</label>
                            <input type="file" class="form-control @error('countryMap') is-invalid @enderror" id="countryMap" name="countryMap">
                            @error('countryMap')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                            @if ($mcountry->Map)
                                <div class="mt-2">
                                    <p>Current Map:</p>
                                    <!-- Display the map -->
                                    <img src="{{ asset('uploads/country_maps/' . $mcountry->Map) }}" alt="Country Map" width="100">
                                    <!-- Display the map name -->
                                    <p>Map Name: {{ $mcountry->Map_Name }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-md-4 mt-2">
                            <label class="form-label">Culture/Nature</label>
                        </div>
                        <div class="col-md-8 d-flex">
                            <div class="form-check me-3">
                                <input class="form-check-input @error('isCultureNature') is-invalid @enderror"
                                       type="radio" name="isCultureNature" id="cultureNatureYes" value="1"
                                       {{ old('isCultureNature', $mcountry->Culture_Nature) == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="cultureNatureYes">Yes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input @error('isCultureNature') is-invalid @enderror"
                                       type="radio" name="isCultureNature" id="cultureNatureNo" value="0"
                                       {{ old('isCultureNature', $mcountry->Culture_Nature) == '0' ? 'checked' : '' }}>
                                <label class="form-check-label" for="cultureNatureNo">No</label>
                            </div>
                            <!-- Display the error message for the radio group -->
                            @error('isCultureNature')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <label for="content" class="form-label mt-3">Matrix</label>
                            <textarea class="@error('content') is-invalid @enderror" name="content" id="editor">{{ old('content', $mcountry->text) }}</textarea>
                            @error('content')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer mt-3">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->
@endsection

