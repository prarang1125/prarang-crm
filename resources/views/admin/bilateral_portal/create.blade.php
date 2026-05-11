@extends('layouts.admin.admin')
@section('title', 'Create Bilateral Portal')

@section('content')
<!--start page wrapper -->
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Admin</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.bilateral-portals.index') }}"><i
                                class="bx bx-globe"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create Bilateral Portal</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="row">
        <div class="col-xl-9 mx-auto">
            <h6 class="mb-0 text-uppercase">Create New Bilateral Portal</h6>
            <hr />

            <!-- Error Messages -->
            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.bilateral-portals.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Portal Title <span class="text-danger">*</span></h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input type="text" name="title" class="form-control" value="{{ old('title') }}"
                                    required>
                                <small class="form-text text-muted">Enter the title for the bilateral portal</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Slogan</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input type="text" name="slogan" class="form-control" value="{{ old('slogan') }}">
                                <small class="form-text text-muted">Optional slogan for the portal</small>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Slug</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input type="text" name="slug" class="form-control" value="{{ old('slug') }}">
                                <small class="form-text text-muted">Leave empty to auto-generate from title</small>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Content Country Code</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <select name="content_country_code" class="form-select" required>
                                    <option value="">Select Content Country</option>
                                    @foreach($livecountries as $country)
                                    <option value="{{ $country->countryCode }}" {{
                                        old('content_country_code')==$country->countryCode ? 'selected' : '' }}>
                                        {{ $country->countryNameInEnglish }} ({{ $country->countryCode }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Primary Country <span class="text-danger">*</span></h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <select name="primary_country_id" class="form-select" required>
                                    <option value="">Select Primary Country</option>
                                    @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ old('primary_country_id')==$country->id ?
                                        'selected' : '' }}>
                                        {{ $country->country_name }} ({{ $country->country_code }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Secondary Country <span class="text-danger">*</span></h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <select name="secondary_country_id" class="form-select" required>
                                    <option value="">Select Secondary Country</option>
                                    @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ old('secondary_country_id')==$country->id ?
                                        'selected' : '' }}>
                                        {{ $country->country_name }} ({{ $country->country_code }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- here we will add the connections script --}}
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Primary Embassy link</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input type="text" name="primary_embassy_link" class="form-control"
                                    value="{{ old('primary_embassy_link') }}">
                                <small class="form-text text-muted">Leave empty to auto-generate from title</small>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Secondary Embassy link</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input type="text" name="secondary_embassy_link" class="form-control"
                                    value="{{ old('secondary_embassy_link') }}">
                                <small class="form-text text-muted">Leave empty to auto-generate from title</small>
                            </div>
                        </div>



                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Connections(History)</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <textarea name="connections" class="form-control"
                                    rows="4">{{ old('connections') }}</textarea>
                                <small class="form-text text-muted">Add connections for the bilateral portal</small>
                            </div>
                        </div>



                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Header Image</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input type="file" name="header_image" class="form-control" accept="image/*">
                                <small class="form-text text-muted">Upload header image (JPEG, PNG, JPG, GIF - Max:
                                    2MB)</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Footer Image</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input type="file" name="footer_image" class="form-control" accept="image/*">
                                <small class="form-text text-muted">Upload footer image (JPEG, PNG, JPG, GIF - Max:
                                    2MB)</small>
                            </div>
                        </div>



                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Header Scripts (JSON)</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <textarea name="header_scripts" class="form-control"
                                    rows="3">{{ old('header_scripts') }}</textarea>
                                <small class="form-text text-muted">JSON data for header scripts</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Footer Scripts (JSON)</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <textarea name="footer_scripts" class="form-control"
                                    rows="3">{{ old('footer_scripts') }}</textarea>
                                <small class="form-text text-muted">JSON data for footer scripts</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <livewire:excel-data-paster label="Primary Link" inputName="extended_primary_link"
                                :initialData="old('extended_primary_link')" />
                            @error('extended_primary_link')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            {{-- <small class="form-text text-muted">Add categorized important links for this country
                                portal
                                using the Excel paster component.</small> --}}
                        </div>
                        <div class="mb-3">
                            <livewire:excel-data-paster label="Secondary Link" inputName="extended_secondary_link"
                                :initialData="old('extended_secondary_link')" />
                            @error('extended_secondary_link')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Add categorized important links for this country
                                portal
                                using the Excel paster component.</small>
                        </div>



                        <div class="row">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-9 text-secondary">
                                <button type="submit" class="btn btn-primary px-4">Create Portal</button>
                                <a href="{{ route('admin.bilateral-portals.index') }}"
                                    class="btn btn-secondary px-4 ms-2">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate slug from title
    const titleInput = document.querySelector('input[name="title"]');
    const slugInput = document.querySelector('input[name="slug"]');

    titleInput.addEventListener('input', function() {
        if (!slugInput.value || slugInput.dataset.autoGenerated) {
            const slug = this.value.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            slugInput.value = slug;
            slugInput.dataset.autoGenerated = 'true';
        }
    });

    slugInput.addEventListener('input', function() {
        if (this.value) {
            this.dataset.autoGenerated = 'false';
        }
    });
});
</script>

@endsection
