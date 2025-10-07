@extends('layouts.admin.admin')
@section('title', 'Edit Bilateral Portal')

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
                        <li class="breadcrumb-item active" aria-current="page">Edit Bilateral Portal</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-xl-9 mx-auto">
                <h6 class="mb-0 text-uppercase">Edit Bilateral Portal</h6>
                <hr />

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.bilateral-portals.update', $bilateralPortal) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Portal Title <span class="text-danger">*</span></h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="text" name="title" class="form-control"
                                        value="{{ old('title', $bilateralPortal->title) }}" required>
                                    <small class="form-text text-muted">Enter the title for the bilateral portal</small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Slogan</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="text" name="slogan" class="form-control"
                                        value="{{ old('slogan', $bilateralPortal->slogan) }}">
                                    <small class="form-text text-muted">Optional slogan for the portal</small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Primary Country <span class="text-danger">*</span></h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <select name="primary_country_id" class="form-select" required>
                                        <option value="">Select Primary Country</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}"
                                                {{ old('primary_country_id', $bilateralPortal->primary_country_id) == $country->id ? 'selected' : '' }}>
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
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}"
                                                {{ old('secondary_country_id', $bilateralPortal->secondary_country_id) == $country->id ? 'selected' : '' }}>
                                                {{ $country->country_name }} ({{ $country->country_code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Embassy Links Section -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="card border-info">
                                        <div class="card-header bg-info text-white">
                                            <h6 class="mb-0"><i class="bx bx-link"></i> Embassy Links</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-3">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Primary Country Embassy Link</h6>
                                                </div>
                                                <div class="col-sm-9">
                                                    <div class="input-group">
                                                        <input type="text" name="primary_embassy_link"
                                                            id="primary_embassy_link" class="form-control"
                                                            value="{{ old('primary_embassy_link', $bilateralPortal->primaryCountry->embassy_link ?? '') }}"
                                                            placeholder="https://embassy.example.com">
                                                        <a href="{{ route('admin.country-portals.edit', $bilateralPortal->primary_country_id) }}"
                                                            class="btn btn-outline-primary" target="_blank"
                                                            title="Edit in Country Portal">
                                                            <i class="bx bx-edit"></i>
                                                        </a>
                                                    </div>
                                                    <small class="form-text text-muted">Embassy link for
                                                        {{ $bilateralPortal->primaryCountry->country_name ?? 'Primary Country' }}
                                                        - Changes will update the country portal</small>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Secondary Country Embassy Link</h6>
                                                </div>
                                                <div class="col-sm-9">
                                                    <div class="input-group">
                                                        <input type="text" name="secondary_embassy_link"
                                                            id="secondary_embassy_link" class="form-control"
                                                            value="{{ old('secondary_embassy_link', $bilateralPortal->secondaryCountry->embassy_link ?? '') }}"
                                                            placeholder="https://embassy.example.com">
                                                        <a href="{{ route('admin.country-portals.edit', $bilateralPortal->secondary_country_id) }}"
                                                            class="btn btn-outline-primary" target="_blank"
                                                            title="Edit in Country Portal">
                                                            <i class="bx bx-edit"></i>
                                                        </a>
                                                    </div>
                                                    <small class="form-text text-muted">Embassy link for
                                                        {{ $bilateralPortal->secondaryCountry->country_name ?? 'Secondary Country' }}
                                                        - Changes will update the country portal</small>
                                                </div>
                                            </div>

                                            <div class="alert alert-warning">
                                                <i class="bx bx-info-circle"></i> <strong>Note:</strong> Editing embassy
                                                links here will update the respective country portals directly.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Country Maps Section -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="card border-success">
                                        <div class="card-header bg-success text-white">
                                            <h6 class="mb-0"><i class="bx bx-map"></i> Country Maps</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-3">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Primary Country Maps URL</h6>
                                                </div>
                                                <div class="col-sm-9">
                                                    <div class="input-group">
                                                        <input type="text" name="primary_country_maps"
                                                            id="primary_country_maps" class="form-control"
                                                            value="{{ old('primary_country_maps', $bilateralPortal->primaryCountry->maps ?? '') }}"
                                                            placeholder="https://maps.example.com">
                                                        <a href="{{ route('admin.country-portals.edit', $bilateralPortal->primary_country_id) }}"
                                                            class="btn btn-outline-primary" target="_blank"
                                                            title="Edit in Country Portal">
                                                            <i class="bx bx-edit"></i>
                                                        </a>
                                                    </div>
                                                    <small class="form-text text-muted">Maps URL for
                                                        {{ $bilateralPortal->primaryCountry->country_name ?? 'Primary Country' }}
                                                        - Changes will update the country portal</small>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Secondary Country Maps URL</h6>
                                                </div>
                                                <div class="col-sm-9">
                                                    <div class="input-group">
                                                        <input type="text" name="secondary_country_maps"
                                                            id="secondary_country_maps" class="form-control"
                                                            value="{{ old('secondary_country_maps', $bilateralPortal->secondaryCountry->maps ?? '') }}"
                                                            placeholder="https://maps.example.com">
                                                        <a href="{{ route('admin.country-portals.edit', $bilateralPortal->secondary_country_id) }}"
                                                            class="btn btn-outline-primary" target="_blank"
                                                            title="Edit in Country Portal">
                                                            <i class="bx bx-edit"></i>
                                                        </a>
                                                    </div>
                                                    <small class="form-text text-muted">Maps URL for
                                                        {{ $bilateralPortal->secondaryCountry->country_name ?? 'Secondary Country' }}
                                                        - Changes will update the country portal</small>
                                                </div>
                                            </div>

                                            <div class="alert alert-warning">
                                                <i class="bx bx-info-circle"></i> <strong>Note:</strong> Editing maps URLs
                                                here will update the respective country portals directly.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Country Timezones Section -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="card border-secondary">
                                        <div class="card-header bg-secondary text-white">
                                            <h6 class="mb-0"><i class="bx bx-time"></i> Country Timezones</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-3">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Primary Country Timezone</h6>
                                                </div>
                                                <div class="col-sm-9">
                                                    <select name="primary_country_timezone" class="form-select">
                                                        <option value="">Select Primary Country Timezone</option>
                                                        @php
                                                            $timezones = timezone_identifiers_list();
                                                            $timezone_offsets = [];
                                                            foreach ($timezones as $timezone) {
                                                                $tz = new DateTimeZone($timezone);
                                                                $offset = $tz->getOffset(new DateTime('now', $tz));
                                                                $offset_prefix = $offset < 0 ? '-' : '+';
                                                                $offset_formatted = gmdate('H:i', abs($offset));
                                                                $timezone_offsets[
                                                                    $timezone
                                                                ] = "(UTC{$offset_prefix}{$offset_formatted}) {$timezone}";
                                                            }
                                                            asort($timezone_offsets);
                                                            $selectedPrimaryTimezone = old(
                                                                'primary_country_timezone',
                                                                $bilateralPortal->primaryCountry->timezone ?? '',
                                                            );
                                                        @endphp
                                                        @foreach ($timezone_offsets as $tz_value => $tz_label)
                                                            <option value="{{ $tz_value }}"
                                                                {{ $selectedPrimaryTimezone == $tz_value ? 'selected' : '' }}>
                                                                {{ $tz_label }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <small class="form-text text-muted">Timezone for
                                                        {{ $bilateralPortal->primaryCountry->country_name ?? 'Primary Country' }}
                                                        - Changes will update the country portal</small>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Secondary Country Timezone</h6>
                                                </div>
                                                <div class="col-sm-9">
                                                    <select name="secondary_country_timezone" class="form-select">
                                                        <option value="">Select Secondary Country Timezone</option>
                                                        @php
                                                            $timezones = timezone_identifiers_list();
                                                            $timezone_offsets = [];
                                                            foreach ($timezones as $timezone) {
                                                                $tz = new DateTimeZone($timezone);
                                                                $offset = $tz->getOffset(new DateTime('now', $tz));
                                                                $offset_prefix = $offset < 0 ? '-' : '+';
                                                                $offset_formatted = gmdate('H:i', abs($offset));
                                                                $timezone_offsets[
                                                                    $timezone
                                                                ] = "(UTC{$offset_prefix}{$offset_formatted}) {$timezone}";
                                                            }
                                                            asort($timezone_offsets);
                                                            $selectedSecondaryTimezone = old(
                                                                'secondary_country_timezone',
                                                                $bilateralPortal->secondaryCountry->timezone ?? '',
                                                            );
                                                        @endphp
                                                        @foreach ($timezone_offsets as $tz_value => $tz_label)
                                                            <option value="{{ $tz_value }}"
                                                                {{ $selectedSecondaryTimezone == $tz_value ? 'selected' : '' }}>
                                                                {{ $tz_label }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <small class="form-text text-muted">Timezone for
                                                        {{ $bilateralPortal->secondaryCountry->country_name ?? 'Secondary Country' }}
                                                        - Changes will update the country portal</small>
                                                </div>
                                            </div>

                                            <div class="alert alert-warning">
                                                <i class="bx bx-info-circle"></i> <strong>Note:</strong> Editing timezones
                                                here will update the respective country portals directly.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Slug</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="text" name="slug" class="form-control"
                                        value="{{ old('slug', $bilateralPortal->slug) }}">
                                    <small class="form-text text-muted">Leave empty to auto-generate from title</small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Content Country Code</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <select name="content_country_code" id="content_country_code_select"
                                        class="form-select">
                                        <option value="">Select Content Country Code</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->country_code }}"
                                                {{ old('content_country_code', $bilateralPortal->content_country_code) == $country->country_code ? 'selected' : '' }}>
                                                {{ $country->country_name }} ({{ $country->country_code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Country code for content localization (e.g., IN,
                                        US, GB)</small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Header Image</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    @if ($bilateralPortal->header_image)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $bilateralPortal->header_image) }}"
                                                alt="Current Header Image" class="img-thumbnail"
                                                style="max-width: 200px; max-height: 150px;">
                                            <p class="small text-muted mt-1">Current header image</p>
                                        </div>
                                    @endif
                                    <input type="file" name="header_image" class="form-control" accept="image/*">
                                    <small class="form-text text-muted">Upload new header image (JPEG, PNG, JPG, GIF - Max:
                                        2MB) - Leave empty to keep current image</small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Footer Image</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    @if ($bilateralPortal->footer_image)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $bilateralPortal->footer_image) }}"
                                                alt="Current Footer Image" class="img-thumbnail"
                                                style="max-width: 200px; max-height: 150px;">
                                            <p class="small text-muted mt-1">Current footer image</p>
                                        </div>
                                    @endif
                                    <input type="file" name="footer_image" class="form-control" accept="image/*">
                                    <small class="form-text text-muted">Upload new footer image (JPEG, PNG, JPG, GIF - Max:
                                        2MB) - Leave empty to keep current image</small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Connections</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <textarea name="connections" class="form-control" rows="4">{{ old('connections', $bilateralPortal->connections) }}</textarea>
                                    <small class="form-text text-muted">Connections information</small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Header Scripts</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <textarea name="header_scripts" class="form-control" rows="3">{{ old('header_scripts', $bilateralPortal->header_scripts) }}</textarea>
                                    <small class="form-text text-muted">Header scripts</small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Footer Scripts</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <textarea name="footer_scripts" class="form-control" rows="3">{{ old('footer_scripts', $bilateralPortal->footer_scripts) }}</textarea>
                                    <small class="form-text text-muted">Footer scripts</small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-9 text-secondary">
                                    <button type="submit" class="btn btn-primary px-4">Update Portal</button>
                                    <a href="{{ route('admin.bilateral-portals.index') }}"
                                        class="btn btn-secondary px-4 ms-2">Cancel</a>
                                    <a href="{{ route('admin.bilateral-portals.show', $bilateralPortal) }}"
                                        class="btn btn-info px-4 ms-2">View Portal</a>
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
            // Auto-generate slug from title if slug is empty
            const titleInput = document.querySelector('input[name="title"]');
            const slugInput = document.querySelector('input[name="slug"]');
            const originalSlug = slugInput.value;

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
                if (this.value !== originalSlug) {
                    this.dataset.autoGenerated = 'false';
                }
            });


        });
    </script>
@endsection
