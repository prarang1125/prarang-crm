@extends('layouts.admin.admin')
@section('title', 'Portal Ads')
@section('content')
<div class="page-content">
    <!--breadcrumb-->
    <div class="d-sm-flex align-items-center mb-3 page-breadcrumb d-none">
        <div class="pe-3 breadcrumb-title">Admin</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="mb-0 p-0 breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('admin/portal-ads') }}"><i class="bx bx-user"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"> Create Portal ads</li>
                </ol>
            </nav>
        </div>
    </div>

    <section>
        <div class="card">
            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ url('admin/portal-ads') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">

                        <div class="mb-3 col-md-6">
                            <label for="portal_id" class="form-label">Portal <span class="text-danger">*</span></label>
                            <select name="portal_id" id="portal_id" class="form-select @error('portal_id') is-invalid @enderror">
                                <option value="">-- Select Portal --</option>
                                @foreach($portals as $portal)
                                    <option value="{{ $portal->id }}" {{ old('portal_id') == $portal->id ? 'selected' : '' }}>
                                        {{ $portal->city_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('portal_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}"
                                class="form-control @error('title') is-invalid @enderror" placeholder="Enter title">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="ad_logo" class="form-label">Ad Logo</label>
                            <input type="file" name="ad_logo" id="ad_logo" accept="image/*"
                                class="form-control @error('ad_logo') is-invalid @enderror">
                            @error('ad_logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="interaction_ad" class="form-label">Interaction Ad</label>
                            <input type="file" name="interaction_ad" id="interaction_ad" accept="image/*"
                                class="form-control @error('interaction_ad') is-invalid @enderror">
                            @error('interaction_ad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="non_interaction_ad" class="form-label">Non Interaction Ad</label>
                            <input type="file" name="non_interaction_ad" id="non_interaction_ad" accept="image/*"
                                class="form-control @error('non_interaction_ad') is-invalid @enderror">
                            @error('non_interaction_ad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="square_ad" class="form-label">Square Ad</label>
                            <input type="file" name="square_ad" id="square_ad" accept="image/*"
                                class="form-control @error('square_ad') is-invalid @enderror">
                            @error('square_ad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="vertical_ad" class="form-label">Vertical Ad</label>
                            <input type="file" name="vertical_ad" id="vertical_ad" accept="image/*"
                                class="form-control @error('vertical_ad') is-invalid @enderror">
                            @error('vertical_ad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="interaction_url" class="form-label">Interaction URL</label>
                            <input type="text" name="interaction_url" id="interaction_url" value="{{ old('interaction_url') }}"
                                class="form-control @error('interaction_url') is-invalid @enderror" placeholder="https://example.com">
                            @error('interaction_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="non_interaction_url" class="form-label">Non Interaction URL</label>
                            <input type="text" name="non_interaction_url" id="non_interaction_url" value="{{ old('non_interaction_url') }}"
                                class="form-control @error('non_interaction_url') is-invalid @enderror" placeholder="https://example.com">
                            @error('non_interaction_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ url('admin/portal-ads') }}" class="btn btn-secondary">Cancel</a>
                    </div>

                </form>

            </div>
        </div>
    </section>

</div>
@endsection