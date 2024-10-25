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
                        <div class="col-md-2">
                            <label for="postNumber" class="form-label">Post Number</label>
                            <input type="text" class="form-control  @error('postNumber') is-invalid @enderror" id="postNumber" name="postNumber" value="{{ old('postNumber') }}" >
                            @error('postNumber')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="titleOfPost" class="form-label">Title of Post</label>
                            <input type="text" class="form-control  @error('titleOfPost') is-invalid @enderror" id="titleOfPost" name="titleOfPost" value="{{ old('titleOfPost') }}" >
                            @error('titleOfPost')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-2">
                            <label for="uploadDate" class="form-label">Upload Date</label>
                            <input type="text" class="form-control  @error('uploadDate') is-invalid @enderror" id="uploadDate" name="uploadDate" value="{{ old('uploadDate') }}" >
                            @error('uploadDate')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-2">
                            <label for="numberOfDays" class="form-label">Number of Days</label>
                            <input type="text" class="form-control  @error('numberOfDays') is-invalid @enderror" id="numberOfDays" name="numberOfDays" value="{{ old('numberOfDays') }}" >
                            @error('numberOfDays')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="nameOfCity" class="form-label">Name of City</label>
                            <input type="text" class="form-control  @error('nameOfCity') is-invalid @enderror" id="nameOfCity" name="nameOfCity" value="{{ old('nameOfCity') }}" >
                            @error('nameOfCity')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-md-5">
                            <label for="advertisementInPost" class="form-label">Advertisement in Post</label>
                            <input type="text" class="form-control  @error('advertisementInPost') is-invalid @enderror" id="advertisementInPost" name="advertisementInPost" value="{{ old('advertisementInPost') }}" >
                            @error('advertisementInPost')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="postViewershipFrom" class="form-label">Post Viewership From</label>
                            <input type="text" class="form-control  @error('postViewershipFrom') is-invalid @enderror" id="postViewershipFrom" name="postViewershipFrom" value="{{ old('postViewershipFrom') }}" >
                            @error('postViewershipFrom')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="to" class="form-label">To</label>
                            <input type="text" class="form-control  @error('to') is-invalid @enderror" id="to" name="to" value="{{ old('to') }}" >
                            @error('to')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-md-6">
                            <label for="citySubscribers" class="form-label">City Subscribers(FB)</label>
                            <input type="text" class="form-control  @error('citySubscribers') is-invalid @enderror" id="citySubscribers" name="citySubscribers" value="{{ old('citySubscribers') }}" >
                            @error('citySubscribers')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="total" class="form-label">Total</label>
                            <input type="text" class="form-control  @error('total') is-invalid @enderror" id="total" name="total" value="{{ old('total') }}" >
                            @error('total')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-md-6">
                            <label for="prarangApplication" class="form-label">Prarang Application</label>
                            <input type="text" class="form-control  @error('prarangApplication') is-invalid @enderror" id="prarangApplication" name="prarangApplication" value="{{ old('prarangApplication') }}" >
                            @error('prarangApplication')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-md-6">
                            <label for="facebookLinkClick" class="form-label">Facebook Link Click</label>
                            <input type="text" class="form-control  @error('facebookLinkClick') is-invalid @enderror" id="facebookLinkClick" name="facebookLinkClick" value="{{ old('facebookLinkClick') }}" >
                            @error('facebookLinkClick')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-md-6">
                            <label for="websiteGd" class="form-label">Website (Google+Direct)</label>
                            <input type="text" class="form-control  @error('websiteGd') is-invalid @enderror" id="websiteGd" name="websiteGd" value="{{ old('websiteGd') }}" >
                            @error('websiteGd')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="monthDay" class="form-label">Month Day</label>
                            <input type="text" class="form-control  @error('monthDay') is-invalid @enderror" id="monthDay" name="monthDay" value="{{ old('monthDay') }}" >
                            @error('monthDay')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control  @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" >
                            @error('email')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="sponsored" class="form-label">This Post was Sponsored by</label>
                            <input type="text" class="form-control  @error('sponsored') is-invalid @enderror" id="sponsored" name="sponsored" value="{{ old('sponsored') }}" >
                            @error('sponsored')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-md-6">
                            <label for="instagram" class="form-label">Instagram</label>
                            <input type="text" class="form-control  @error('instagram') is-invalid @enderror" id="instagram" name="instagram" value="{{ old('instagram') }}" >
                            @error('instagram')
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

