@extends('layouts.admin.admin')
@section('title', 'Create Advertisement')
@section('content')
<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Advertisements</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.ads.index') }}">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Create Advertisement
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-9 mx-auto">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-0 text-uppercase text-primary">
                        Create Advertisement
                    </h5>
                    <hr>
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form action="{{ route('admin.ads.store') }}"
                        method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="partner_id" class="form-label"> Partner ID </label>
                            <input type="number" class="form-control @error('partner_id') is-invalid @enderror"
                                id="partner_id" name="partner_id" value="{{ old('partner_id') }}"
                                placeholder="Enter Partner ID">
                            @error('partner_id')
                            <div class="invalid-feedback"> {{ $message }} </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="city_id" class="form-label"> City ID </label>
                            <input type="number" class="form-control @error('city_id') is-invalid @enderror"
                                id="city_id" name="city_id" value="{{ old('city_id') }}"
                                placeholder="Enter City ID">
                            @error('city_id')
                            <div class="invalid-feedback"> {{ $message }} </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="creative_link" class="form-label">
                                Creative Link
                            </label>

                            <input
                                type="url"
                                class="form-control @error('creative_link') is-invalid @enderror"
                                id="creative_link"
                                name="creative_link"
                                value="{{ old('creative_link') }}"
                                placeholder="https://example.com/ad-image.jpg">

                            @error('creative_link')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="ad_link" class="form-label">
                                Advertisement Link
                            </label>
                            <input type="url" class="form-control @error('ad_link') is-invalid @enderror"
                                id="ad_link" name="ad_link" value="{{ old('ad_link') }}"
                                placeholder="Enter advertisement link">
                            @error('ad_link')
                            <div class="invalid-feedback"> {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="ad_title" class="form-label">
                                Advertisement Title
                            </label>
                            <input type="text" class="form-control @error('ad_title') is-invalid @enderror"
                                id="ad_title" name="ad_title" value="{{ old('ad_title') }}"
                                placeholder="Enter advertisement title">
                            @error('ad_title')
                            <div class="invalid-feedback"> {{ $message }} </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="cta_title" class="form-label">
                                CTA Title
                            </label>
                            <input
                                type="text" class="form-control @error('cta_title') is-invalid @enderror"
                                id="cta_title" name="cta_title" value="{{ old('cta_title') }}"
                                placeholder="Enter CTA title">
                            @error('cta_title')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="cta_text" class="form-label">
                                CTA Text
                            </label>
                            <textarea class="form-control @error('cta_text') is-invalid @enderror"
                                id="cta_text" name="cta_text" rows="4" placeholder="Enter CTA text">{{ old('cta_text') }}</textarea>
                            @error('cta_text')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">
                                Status
                            </label>
                            <select
                                class="form-select @error('status') is-invalid @enderror"
                                id="status"
                                name="status">
                                <option value="">Select Status</option>
                                <option value="1"
                                    {{ old('status') == '1' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="0"
                                    {{ old('status') == '0' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                            @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="ad_type" class="form-label">
                                Advertisement Type
                            </label>
                            <select
                                class="form-select @error('ad_type') is-invalid @enderror"
                                id="ad_type"
                                name="ad_type">
                                <option value="">Select Ad Type</option>
                                <option value="image"
                                    {{ old('ad_type') == 'image' ? 'selected' : '' }}>
                                    Image
                                </option>
                                <option value="video"
                                    {{ old('ad_type') == 'video' ? 'selected' : '' }}>
                                    Video
                                </option>
                            </select>
                            @error('ad_type')
                            <div class="invalid-feedback"> {{ $message }} </div>
                            @enderror
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('admin.ads.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Save Advertisement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection