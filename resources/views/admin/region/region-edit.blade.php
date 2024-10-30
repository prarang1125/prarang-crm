@extends('layouts.admin.admin')
@section('title', 'Region Edit')

@section('content')
<!--start page wrapper -->
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Admin</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/region/region-listing')}}"><i class="bx bx-user"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Region Edit</li>
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
                <h6 class="mb-0 text-uppercase text-primary">Edit Region</h6>
                <hr/>
                <form  action="{{ route('admin.region-update' , $region->regionId) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <label for="regionnameInEnglish" class="form-label">City Name In English</label>
                            <input type="text" class="form-control  @error('regionnameInEnglish') is-invalid @enderror" id="regionnameInEnglish" name="regionnameInEnglish" value="{{ old('regionnameInEnglish' ,$region->regionnameInEnglish) }} " >
                            @error('regionnameInEnglish')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="regionnameInUnicode" class="form-label">City Name In Hindi</label>
                            <input type="text" class="form-control  @error('regionnameInUnicode') is-invalid @enderror" id="regionnameInUnicode" name="regionnameInUnicode" value="{{ old('regionnameInUnicode' , $region->regionnameInUnicode) }}" >
                            @error('regionnameInUnicode')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="regionImage" class="form-label">Image</label>
                            <input type="file" class="form-control @error('regionImage') is-invalid @enderror" id="regionImage" name="regionImage">
                            @error('regionImage')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                            @if ($region->image)
                                <div class="mt-2">
                                    <p>Current Image:</p>
                                    <!-- Display the image -->
                                    <img src="{{ asset('uploads/region_images/' . $region->image) }}" alt="Region Image" width="100">
                                    <!-- Display the image name -->
                                    <p>Image Name: {{ $region->Image_Name }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="map" class="form-label">Map</label>
                            <input type="file" class="form-control @error('regionMap') is-invalid @enderror" id="regionMap" name="regionMap">
                            @error('regionMap')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                            @if ($region->map)
                                <div class="mt-2">
                                    <p>Current Map:</p>
                                    <!-- Display the map -->
                                    <img src="{{ asset('uploads/region_maps/' . $region->map) }}" alt="Region Map" width="100">
                                    <!-- Display the map name -->
                                    <p>Map Name: {{ $region->Map_Name }}</p>
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
                                       {{ old('isCultureNature', $region->Culture_Nature) == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="cultureNatureYes">Yes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input @error('isCultureNature') is-invalid @enderror"
                                       type="radio" name="isCultureNature" id="cultureNatureNo" value="0"
                                       {{ old('isCultureNature', $region->Culture_Nature) == '0' ? 'checked' : '' }}>
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
                            <textarea class="@error('content') is-invalid @enderror" name="content" id="editor">{{ old('content', $region->text) }}</textarea>
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

