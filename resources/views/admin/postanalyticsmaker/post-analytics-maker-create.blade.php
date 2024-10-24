@extends('layouts.admin.admin')
@section('title', 'New Maker Analytics Register')

@section('content')
<!--start page wrapper -->
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Admin</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    {{-- <li class="breadcrumb-item"><a href="{{ url('admin/postanalyticsmaker/post-analytics-maker-listing')}}"><i class="bx bx-user"></i></a>
                    </li> --}}
                    <li class="breadcrumb-item active" aria-current="page">Maker Analytics Register</li>
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
                <h6 class="mb-0 text-uppercase text-primary">Create New Maker Analytics</h6>
                <hr/>
                <form  action="{{ url('/admin/country/country-store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <label for="countryNameInEnglish" class="form-label">Country Name In English</label>
                            <input type="text" class="form-control  @error('countryNameInEnglish') is-invalid @enderror" id="countryNameInEnglish" name="countryNameInEnglish" value="{{ old('countryNameInEnglish') }}" >
                            @error('countryNameInEnglish')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="countryNameInUnicode" class="form-label">Country Name In Hindi</label>
                            <input type="text" class="form-control  @error('countryNameInUnicode') is-invalid @enderror" id="countryNameInUnicode" name="countryNameInUnicode" value="{{ old('countryNameInUnicode') }}" >
                            @error('countryNameInUnicode')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="countryNameInUnicode" class="form-label">Country Name In Hindi</label>
                            <input type="text" class="form-control  @error('countryNameInUnicode') is-invalid @enderror" id="countryNameInUnicode" name="countryNameInUnicode" value="{{ old('countryNameInUnicode') }}" >
                            @error('countryNameInUnicode')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="countryNameInUnicode" class="form-label">Country Name In Hindi</label>
                            <input type="text" class="form-control  @error('countryNameInUnicode') is-invalid @enderror" id="countryNameInUnicode" name="countryNameInUnicode" value="{{ old('countryNameInUnicode') }}" >
                            @error('countryNameInUnicode')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer mt-3">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->
@endsection

