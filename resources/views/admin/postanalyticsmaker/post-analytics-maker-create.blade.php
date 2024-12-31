@extends('layouts.admin.admin')
@section('title', 'New Maker Analytics Register')

@section('content')
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Admin</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item active" aria-current="page">Maker Analytics Register</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="card" style="padding-top: 15px;">
                <div class="col-xl-9 mx-auto w-100">
                    @if (session('success'))
                        <div class="alert alert-success mt-3">
                            {{ session('success') }}
                        </div>
                    @endif
                    <h6 class="mb-0 text-uppercase text-primary">Create New Maker Analytics</h6>
                    <hr />
                    <form
                        action="{{ route('admin.post-analytics-maker-update', $chitti->chittiId) }}?cityCode={{ Request::query('city') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-2">
                                <label for="postNumber" class="form-label">Post Number</label>
                                <input type="text" class="form-control  @error('postNumber') is-invalid @enderror"
                                    id="postNumber" name="postNumber" value="{{ $chitti->chittiId }}" readonly>
                                @error('postNumber')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="titleOfPost" class="form-label">Title of Post</label>
                                <input type="text" class="form-control  @error('titleOfPost') is-invalid @enderror"
                                    id="titleOfPost" name="titleOfPost" value="{{ $chitti->Title }}" readonly>
                                @error('titleOfPost')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-md-2">
                                <label for="uploadDate" class="form-label">Upload Date</label>
                                <input type="text" class="form-control  @error('uploadDate') is-invalid @enderror"
                                    id="uploadDate" name="uploadDate"
                                    value="{{ \Carbon\Carbon::parse($chitti->createDate)->format('Y-M-d') }}" readonly>
                                @error('uploadDate')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-md-2">
                                <label for="numberOfDays" class="form-label">Number of Days</label>
                                <input type="text" class="form-control  @error('numberOfDays') is-invalid @enderror"
                                    id="numberOfDays" name="numberOfDays"
                                    value="{{ old('numberOfDays', (int) \Carbon\Carbon::parse($chitti->createDate ?? now())->diffInDays(now())) }}"
                                    readonly>
                                @error('numberOfDays')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="nameOfCity" class="form-label">Name of City</label>
                                <input type="text" class="form-control  @error('nameOfCity') is-invalid @enderror"
                                    id="nameOfCity" name="nameOfCity"
                                    value="{{ old('nameOfCity', $chitti->citynameInEnglish ?? 'N/A') }}" readonly>
                                @error('nameOfCity')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-md-6">
                                <label for="advertisementInPost" class="form-label">Advertisement in Post</label>
                                <div class="select-wrapper">
                                    <select class="form-control @error('advertisementInPost') is-invalid @enderror"
                                        id="advertisementInPost" name="advertisementInPost">
                                        <option value="" selected disabled>Select</option>
                                        <option value="Yes" {{ 'Yes' == $chitti->advertisementPost ? 'selected' : '' }}>
                                            Yes</option>
                                        <option value="No" {{ 'No' == $chitti->advertisementPost ? 'selected' : '' }}>
                                            No</option>
                                    </select>
                                    <span class="select-arrow">&#9662;</span> <!-- Unicode arrow symbol -->
                                </div>
                                @error('advertisementInPost')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="postViewershipFrom" class="form-label">Post Viewership From</label>
                                <input type="text"
                                    class="form-control  @error('postViewershipFrom') is-invalid @enderror"
                                    id="postViewershipFrom" name="postViewershipFrom"
                                    value="{{ \Carbon\Carbon::parse($chitti->createDate)->format('Y-m-d') ?? '' }}"
                                    readonly>
                                @error('postViewershipFrom')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="to" class="form-label">To</label>
                                <input type="date" class="form-control @error('to') is-invalid @enderror" id="to"
                                    name="to" value="{{ old('to', $chitti->postViewershipDateTo) }}">
                                @error('to')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        @if (in_array($chitti->postStatusMakerChecker, ['return_post_from_checker', 'return_chitti_post_from_checker']))
                            <div class="row mt-1">
                                <div class="col-md-6">
                                    <label for="citySubscribers" class="form-label">City Subscribers(FB)</label>
                                    <input type="text"
                                        class="form-control  @error('citySubscribers') is-invalid @enderror"
                                        id="citySubscribers" name="citySubscribers" id="citySubscribers"
                                        value="{{ $chitti->citySubscriber }}" oninput="calculateTotal()">
                                    @error('citySubscribers')
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="total" class="form-label">Total</label>
                                    <input type="text" class="form-control  @error('total') is-invalid @enderror"
                                        id="total" name="total" value="{{ $chitti->totalViewerCount }}" readonly>
                                    @error('total')
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-6">
                                    <label for="prarangApplication" class="form-label">Prarang Application</label>
                                    <input type="text"
                                        class="form-control  @error('prarangApplication') is-invalid @enderror"
                                        id="prarangApplication" name="prarangApplication"
                                        value="{{ old('prarangApplication', $chitti->prarangApplication ?? '') }}"
                                        oninput="calculateTotal()">
                                    @error('prarangApplication')
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-6">
                                    <label for="facebookLinkClick" class="form-label">Facebook Link Click</label>
                                    <input type="text"
                                        class="form-control  @error('facebookLinkClick') is-invalid @enderror"
                                        id="facebookLinkClick" name="facebookLinkClick"
                                        value="{{ old('facebookLinkClick', $chitti->prarangApplication ?? '') }}"
                                        oninput="calculateTotal()">
                                    @error('facebookLinkClick')
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-6">
                                    <label for="websiteGd" class="form-label">Website (Google+Direct)</label>
                                    <input type="text" class="form-control  @error('websiteGd') is-invalid @enderror"
                                        id="websiteGd" name="websiteGd"
                                        value="{{ old('websiteGd', $chitti->websiteCount ?? '') }}"
                                        oninput="calculateTotal()">
                                    @error('websiteGd')
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-md-6 p-4">
                                    <label for="monthDay" class="form-label">Month Day</label>
                                    <div class="row">
                                        <div class="form-check col-sm">
                                            <input class="form-check-input @error('monthDay') is-invalid @enderror"
                                                type="radio" id="monthDay1" name="monthDay" value="(5th) Day"
                                                {{ old('monthDay', $chitti->monthDay ?? '') == '(5th) Day' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="monthDay1">
                                                (5th) Day
                                            </label>
                                        </div>
                                        <div class="form-check col-sm ps-2">
                                            <input class="form-check-input @error('monthDay') is-invalid @enderror"
                                                type="radio" id="monthDay2" name="monthDay" value="(31st) Day"
                                                {{ old('monthDay', $chitti->monthDay ?? '') == '(31st) Day' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="monthDay2">
                                                (31st) Day
                                            </label>
                                        </div>
                                    </div>
                                    @error('monthDay')
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" class="form-control  @error('email') is-invalid @enderror"
                                        id="email" name="email"
                                        value="{{ old('email', $chitti->emailCount ?? '') }}" oninput="calculateTotal()">
                                    @error('email')
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="sponsored" class="form-label">This Post was Sponsored by</label>
                                    <input type="text" class="form-control  @error('sponsored') is-invalid @enderror"
                                        id="sponsored" name="sponsored"
                                        value="{{ old('sponsored', $chitti->sponsoredBy ?? '') }}">
                                    @error('sponsored')
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-6">
                                    <label for="instagram" class="form-label">Instagram</label>
                                    <input type="text" class="form-control  @error('instagram') is-invalid @enderror"
                                        id="instagram" name="instagram"
                                        value="{{ old('instagram', $chitti->instagramCount ?? '') }}"
                                        oninput="calculateTotal()">
                                    @error('instagram')
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        @else
                            <div class="row mt-1">
                                <div class="col-md-6">
                                    <label for="citySubscribers" class="form-label">City Subscribers(FB)</label>
                                    <input type="text"
                                        class="form-control @error('citySubscribers') is-invalid @enderror"
                                        id="citySubscribers" name="citySubscribers"
                                        value="{{ old('citySubscribers', $chitti->citySubscriber) }}"
                                        oninput="calculateTotal()">
                                    @error('citySubscribers')
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="total" class="form-label">Total</label>
                                    <input type="text" class="form-control @error('total') is-invalid @enderror"
                                        id="total" name="total"
                                        value="{{ old('total', $chitti->totalViewerCount) }}" readonly>
                                    @error('total')
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-6">
                                    <label for="prarangApplication" class="form-label">Prarang Application</label>
                                    <input type="text"
                                        class="form-control @error('prarangApplication') is-invalid @enderror"
                                        id="prarangApplication" name="prarangApplication"
                                        value="{{ old('prarangApplication', $chitti->prarangApplication) }}"
                                        oninput="calculateTotal()">
                                    @error('prarangApplication')
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-6">
                                    <label for="facebookLinkClick" class="form-label">Facebook Link Click</label>
                                    <input type="text"
                                        class="form-control @error('facebookLinkClick') is-invalid @enderror"
                                        id="facebookLinkClick" name="facebookLinkClick"
                                        value="{{ old('facebookLinkClick', $chitti->fb_link_click) }}"
                                        oninput="calculateTotal()">
                                    @error('facebookLinkClick')
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-6">
                                    <label for="websiteGd" class="form-label">Website (Google+Direct)</label>
                                    <input type="text" class="form-control @error('websiteGd') is-invalid @enderror"
                                        id="websiteGd" name="websiteGd"
                                        value="{{ old('websiteGd', $chitti->websiteCount) }}" oninput="calculateTotal()">
                                    @error('websiteGd')
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-md-6 p-4">
                                    <label for="monthDay" class="form-label">Month Day</label>
                                    <div class="row">
                                        <div class="form-check col-sm">
                                            <input class="form-check-input @error('monthDay') is-invalid @enderror"
                                                type="radio" id="monthDay1" name="monthDay" value="(5th) Day"
                                                {{ old('monthDay', $chitti->monthDay ?? '') == '(5th) Day' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="monthDay1">
                                                (5th) Day
                                            </label>
                                        </div>
                                        <div class="form-check col-sm ps-2">
                                            <input class="form-check-input @error('monthDay') is-invalid @enderror"
                                                type="radio" id="monthDay2" name="monthDay" value="(31st) Day"
                                                {{ old('monthDay', $chitti->monthDay ?? '') == '(31st) Day' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="monthDay2">
                                                (31st) Day
                                            </label>
                                        </div>
                                    </div>
                                    @error('monthDay')
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>

                            </div>

                            <div class="row mt-1">
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email', $chitti->emailCount) }}"
                                        oninput="calculateTotal()">
                                    @error('email')
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="sponsored" class="form-label">This Post was Sponsored by</label>
                                    <input type="text" class="form-control @error('sponsored') is-invalid @enderror"
                                        id="sponsored" name="sponsored"
                                        value="{{ old('sponsored', $chitti->sponsoredBy) }}">
                                    @error('sponsored')
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-6">
                                    <label for="instagram" class="form-label">Instagram</label>
                                    <input type="text" class="form-control @error('instagram') is-invalid @enderror"
                                        id="instagram" name="instagram"
                                        value="{{ old('instagram', $chitti->instagramCount) }}"
                                        oninput="calculateTotal()">
                                    @error('instagram')
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        @endif
                        <div class="modal-footer mt-3">
                            <button type="submit" class="btn btn-primary">Send to checker</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--end page wrapper -->
@endsection
