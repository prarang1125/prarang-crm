@extends('layouts.admin.admin')
@section('title', 'User Country Edit')
@section('content')
<!--start page wrapper -->
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Admin</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/usercountry/user-country-listing')}}"><i class="bx bx-user"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">User Country Edit</li>
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
                <h6 class="mb-0 text-uppercase">User Country Edit</h6>
                <hr/>
                <form  action="{{ route('admin.user-country-update', $userCountry->countryId) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <label for="countryNameInEnglish" class="form-label">Country Name In English</label>
                            <input type="text" class="form-control  @error('countryNameInEnglish') is-invalid @enderror" id="countryNameInEnglish" name="countryNameInEnglish" value="{{ old('countryNameInEnglish', $userCountry->countryNameInEnglish) }}" >
                            @error('countryNameInEnglish')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="countryNameInHindi" class="form-label">Country Name In Hindi</label>
                            <input type="text" class="form-control @error('countryNameInHindi') is-invalid @enderror" id="countryNameInHindi" name="countryNameInHindi" value="{{ old('countryNameInHindi', $userCountry->countryNameInHindi) }}" >
                            @error('countryNameInHindi')
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
