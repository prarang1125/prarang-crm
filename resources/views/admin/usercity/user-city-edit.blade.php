@extends('layouts.admin.admin')
@section('title', 'User City Edit')
@section('content')
<!--start page wrapper -->
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Admin</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/usercity/user-city-listing')}}"><i class="bx bx-user"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">User City Edit</li>
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
                <h6 class="mb-0 text-uppercase">User City Edit</h6>
                <hr/>
                <form action="{{ route('admin.user-city-update', $userCitys->cityId) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <label for="cityNameInEnglish" class="form-label">City Name In English</label>
                            <input type="text" class="form-control @error('cityNameInEnglish') is-invalid @enderror" id="cityNameInEnglish" name="cityNameInEnglish" value="{{ old('cityNameInEnglish', $userCitys->cityNameInEnglish) }}">
                            @error('cityNameInEnglish')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="cityNameInHindi" class="form-label">City Name In Hindi</label>
                            <input type="text" class="form-control @error('cityNameInHindi') is-invalid @enderror" id="cityNameInHindi" name="cityNameInHindi" value="{{ old('cityNameInHindi', $userCitys->cityNameInHindi) }}">
                            @error('cityNameInHindi')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="countryId" class="form-label">User Country</label>
                            <select id="countryId" class="form-select @error('countryId') is-invalid @enderror" name="countryId">
                                <option selected disabled>Choose...</option>
                                @foreach($userCountries as $userCountry)
                                    <option value="{{ $userCountry->countryId }}" {{ $userCountry->countryId == $userCitys->countryId ? 'selected' : '' }}>
                                        {{ $userCountry->countryNameInEnglish }}
                                    </option>
                                @endforeach
                            </select>
                            @error('countryId')
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
