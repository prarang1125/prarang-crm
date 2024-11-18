@extends('layouts.admin.admin')
@section('title', 'New Checker Analytics Register')

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
                    <li class="breadcrumb-item active" aria-current="page">Checker Analytics Register</li>
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
                <h6 class="mb-0 text-uppercase text-primary">Create New Checker Analytics</h6>
                <hr/>
                <form action="{{ route('admin.post-analytics-checker-update', ['id' => $chitti->chittiId]) }}?checkerId={{ $chitti->makerId }}&City={{ $chitti->areaId }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-2">
                            <label for="postNumber" class="form-label">Post Number</label>
                            <input type="text" class="form-control  @error('postNumber') is-invalid @enderror" id="postNumber" name="postNumber" value="{{ old('postNumber', $chitti->chittiId ?? '') }}" readonly>
                            @error('postNumber')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="titleOfPost" class="form-label">Title of Post</label>
                            <input type="text" class="form-control  @error('titleOfPost') is-invalid @enderror" id="titleOfPost" name="titleOfPost" value="{{ old('postNumber', $chitti->Title ?? '') }}" readonly>
                            @error('titleOfPost')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-2">
                            <label for="uploadDate" class="form-label">Upload Date</label>
                            <input type="text" class="form-control  @error('uploadDate') is-invalid @enderror" id="uploadDate" name="uploadDate" value="{{ old('uploadDate', \Carbon\Carbon::parse($chitti->created_at)->format('Y-m-d') ?? '') }}" readonly>
                            @error('uploadDate')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-2">
                            <label for="numberOfDays" class="form-label">Number of Days</label>
                            <input type="text" class="form-control  @error('numberOfDays') is-invalid @enderror" id="numberOfDays" name="numberOfDays" value="{{ old('numberOfDays', (int) \Carbon\Carbon::parse($chitti->created_at ?? now())->diffInDays(now())) }}" readonly>
                            @error('numberOfDays')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="nameOfCity" class="form-label">Name of City</label>
                            <input type="text" class="form-control  @error('nameOfCity') is-invalid @enderror" id="nameOfCity" name="nameOfCity" value="{{ old('nameOfCity', $chitti->city->cityNameInEnglish ?? 'N/A') }}" readonly>
                            @error('nameOfCity')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-md-6">
                            <label for="advertisementInPost" class="form-label">Advertisement in Post</label>
                            <div class="select-wrapper">
                                <select class="form-control @error('advertisementInPost') is-invalid @enderror" id="advertisementInPost" name="advertisementInPost">
                                    <option value="" selected disabled>Select</option>
                                    <option value="Yes" {{ old('advertisementInPost') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('advertisementInPost') == 'No' ? 'selected' : '' }}>No</option>
                                </select>
                                <span class="select-arrow">&#9662;</span> <!-- Unicode arrow symbol -->
                            </div>
                            @error('advertisementInPost')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="postViewershipFrom" class="form-label">Post Viewership From</label>
                            <input type="text" class="form-control  @error('postViewershipFrom') is-invalid @enderror" id="postViewershipFrom" name="postViewershipFrom" value="{{ old('postViewershipFrom', \Carbon\Carbon::parse($chitti->created_at)->format('Y-m-d') ?? '') }}" readonly>
                            @error('postViewershipFrom')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="to" class="form-label">To</label>
                            <input type="date" class="form-control @error('to') is-invalid @enderror" id="to" name="to" value="{{ old('to', date('Y-m-d')) }}">
                            @error('to')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-md-6">
                            <label for="citySubscribers" class="form-label">City Subscribers(FB)</label>
                            <input type="text" class="form-control  @error('citySubscribers') is-invalid @enderror" id="citySubscribers" name="citySubscribers" value="{{ old('citySubscribers', $chitti->citySubscriber ?? '') }}" oninput="calculateTotal()">
                            @error('citySubscribers')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="total" class="form-label">Total</label>
                            <input type="text" class="form-control  @error('total') is-invalid @enderror" id="total" name="total" value="{{ old('total', $chitti->totalViewerCount ?? '') }}" readonly>
                            @error('total')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-md-6">
                            <label for="prarangApplication" class="form-label">Prarang Application</label>
                            <input type="text" class="form-control  @error('prarangApplication') is-invalid @enderror" id="prarangApplication" name="prarangApplication" value="{{ old('prarangApplication', $chitti->prarangApplication ?? '') }}" oninput="calculateTotal()">
                            @error('prarangApplication')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-md-6">
                            <label for="websiteGd" class="form-label">Website (Google+Direct)</label>
                            <input type="text" class="form-control  @error('websiteGd') is-invalid @enderror" id="websiteGd" name="websiteGd" value="{{ old('websiteGd', $chitti->websiteCount ?? '') }}" oninput="calculateTotal()">
                            @error('websiteGd')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control  @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $chitti->emailCount ?? '') }}" oninput="calculateTotal()">
                            @error('email')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="sponsored" class="form-label">This Post was Sponsored by</label>
                            <input type="text" class="form-control  @error('sponsored') is-invalid @enderror" id="sponsored" name="sponsored" value="{{ old('sponsored', $chitti->sponsoredBy ?? '') }}" >
                            @error('sponsored')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-md-6">
                            <label for="instagram" class="form-label">Instagram</label>
                            <input type="text" class="form-control  @error('instagram') is-invalid @enderror" id="instagram" name="instagram" value="{{ old('instagram', $chitti->instagramCount) }}" oninput="calculateTotal()">
                            @error('instagram')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer mt-3">
                        <div class="col-md-6"></div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <button type="submit" name="update" id="update" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                        <div class="col-md-2" style="text-align:center;">
                            <div class="form-group">
                                <a href="{{ route('admin.post-analytics-checker-return-region', ['id' => $chitti->chittiId]) }}?checkerId={{ $chitti->makerId }}&City={{ $chitti->areaId }}" class="btn btn-primary">Send to maker</a>
                            </div>
                        </div>
                        {{-- @dd($chitti->chittiId) --}}
                        <div class="col-md-1">
                            <div class="form-group">
                                <a href="{{ route('admin.post-analytics-checker-approve', ['id' => $chitti->chittiId]) }}?checkerId={{ $chitti->makerId }}&City={{ $chitti->areaId }}&approve={{ 'approve' }}" class="btn btn-primary">Approve</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->
@endsection

