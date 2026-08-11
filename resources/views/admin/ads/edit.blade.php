@extends('layouts.admin.admin')
@section('title', 'Edit Advertisement')
@section('content')
<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">
            Advertisements
        </div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.ads.index') }}">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">
                        Edit Advertisement
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
                        Edit Advertisement
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
                    <form action="{{ route('admin.ads.update', $ad->id) }}"
                        method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="partner_id" class="form-label">
                                Partner ID
                            </label>
                            <input type="number" class="form-control @error('partner_id') is-invalid @enderror"
                                id="partner_id" name="partner_id" value="{{ old('partner_id', $ad->partner_id) }}">
                            @error('partner_id')
                            <div class="invalid-feedback"> {{ $message }} </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="city_id" class="form-label">
                                City ID
                            </label>
                            <input type="number" class="form-control @error('city_id') is-invalid @enderror"
                                id="city_id" name="city_id" value="{{ old('city_id', $ad->city_id) }}">
                            @error('city_id')
                            <div class="invalid-feedback"> {{ $message }} </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="creative_link" class="form-label">
                                Upload Creative
                            </label>
                            {{-- Current Creative --}}
                            @if ($ad->creative_link)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $ad->creative_link) }}" alt="{{ $ad->ad_title }}"
                                    style="width: 200px; height: 120px; object-fit: cover; border-radius: 6px; border: 1px solid #ddd;">
                            </div>
                            @endif
                            {{-- Upload New Creative --}}
                            <input type="file" class="form-control @error('creative_link') is-invalid @enderror"
                                id="creative_link" name="creative_link" accept="image/jpeg,image/png,image/webp,video/mp4">
                            <small class="text-muted">
                                Leave empty if you want to keep the current image.
                            </small>
                            @error('creative_link')
                            <div class="invalid-feedback"> {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="ad_link" class="form-label">
                                Advertisement Link
                            </label>
                            <input type="url" class="form-control @error('ad_link') is-invalid @enderror"
                                id="ad_link" name="ad_link" value="{{ old('ad_link', $ad->ad_link) }}">
                            @error('ad_link')
                            <div class="invalid-feedback"> {{ $message }} </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="ad_title" class="form-label">
                                Advertisement Title
                            </label>
                            <input type="text" class="form-control @error('ad_title') is-invalid @enderror"
                                id="ad_title" name="ad_title" value="{{ old('ad_title', $ad->ad_title) }}">
                            @error('ad_title')
                            <div class="invalid-feedback"> {{ $message }} </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="cta_title" class="form-label">
                                CTA Title
                            </label>
                            <input type="text" class="form-control @error('cta_title') is-invalid @enderror"
                                id="cta_title" name="cta_title" value="{{ old('cta_title', $ad->cta_title) }}">
                            @error('cta_title')
                            <div class="invalid-feedback"> {{ $message }} </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="cta_text" class="form-label">
                                CTA Text
                            </label>
                            <textarea class="form-control @error('cta_text') is-invalid @enderror"
                                id="cta_text" name="cta_text" rows="4">{{ old('cta_text', $ad->cta_text) }}</textarea>
                            @error('cta_text')
                            <div class="invalid-feedback"> {{ $message }} </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">
                                Status
                            </label>
                            <select class="form-select @error('status') is-invalid @enderror"
                                id="status" name="status">
                                <option value=""> Select Status </option>
                                <option value="1"
                                    {{ old('status', $ad->status) == 1 ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="0"
                                    {{ old('status', $ad->status) == 0 ? 'selected' : '' }}>
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
                            <select class="form-select @error('ad_type') is-invalid @enderror"
                                id="ad_type" name="ad_type">
                                <option value="">
                                    Select Ad Type
                                </option>
                                <option value="image"
                                    {{ old('ad_type', $ad->ad_type) == 'image' ? 'selected' : '' }}>
                                    Image
                                </option>
                                <option value="video"
                                    {{ old('ad_type', $ad->ad_type) == 'video' ? 'selected' : '' }}>
                                    Video
                                </option>
                            </select>
                            @error('ad_type')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('admin.ads.index') }}"
                                class="btn btn-secondary">
                                Cancel
                            </a>
                            <button type="submit"
                                class="btn btn-primary">
                                Update Advertisement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
