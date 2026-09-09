@extends('layouts.admin.admin')
@section('title', 'Create Country Portal')

@section('content')
<!--start page wrapper -->
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Admin</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.country-portals.index') }}">
                            <i class="bx bx-world"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ __('Create Country Portal') }}
                    </li>
                </ol>
            </nav>
        </div>
    </div>

</div>
<!--end breadcrumb-->

<div class="row">
    <div class="col-xl-10 mx-auto">
        <h6 class="mb-0 text-uppercase">Create New Country Portal</h6>
        <hr />

        <!-- Error Messages -->
        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h6 class="alert-heading"><i class="bx bx-error-circle"></i> Please fix the following errors:</h6>
            <hr>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Success Message -->
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle"></i> <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.country-portals.store') }}" method="POST" id="country-portal-form">
                    @csrf

                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="bx bx-info-circle"></i> Basic Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Country Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="country_name"
                                            class="form-control @error('country_name') is-invalid @enderror"
                                            value="{{ old('country_name') }}" required>
                                        @error('country_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Enter the official name of the
                                            country.</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Country Code <span
                                                class="text-danger">*</span></label>
                                        <select name="country_code" id="country_code_select"
                                            class="form-control @error('country_code') is-invalid @enderror" required>
                                            <option value="">Select Country For Code</option>

                                            @foreach ($countries as $country)
                                            <option value="{{ $country->countryCode }}" {{
                                                old('country_code')==$country->countryCode ? 'selected' : '' }}>
                                                {{ $country->countryNameInEnglish }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('country_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">ISO country code (e.g., IN, US,
                                            GB).</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Country Name (Local)</label>
                                        <input type="text" name="country_name_locale"
                                            class="form-control @error('country_name_locale') is-invalid @enderror"
                                            value="{{ old('country_name_locale') }}">
                                        @error('country_name_locale')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Country name in local language.</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Language Code</label>
                                        <input type="text" name="locale_lang"
                                            class="form-control @error('locale_lang') is-invalid @enderror"
                                            value="{{ old('locale_lang') }}" maxlength="10">
                                        @error('locale_lang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Language code (e.g., en, hi,
                                            fr).</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Slogan</label>
                                        <input type="text" name="slogan"
                                            class="form-control @error('slogan') is-invalid @enderror"
                                            value="{{ old('slogan') }}">
                                        @error('slogan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Timezone</label>
                                        <select name="timezone" id="timezone_select"
                                            class="form-control @error('timezone') is-invalid @enderror">
                                            <option value="">Select Timezone</option>
                                            @php
$timezones = timezone_identifiers_list();
$timezone_offsets = [];
foreach ($timezones as $timezone) {
    $tz = new DateTimeZone($timezone);
    $offset = $tz->getOffset(new DateTime('now', $tz));
    $offset_prefix = $offset < 0 ? '-' : '+' ;
    $offset_formatted=gmdate('H:i', abs($offset));
    $timezone_offsets[$timezone]="(UTC{$offset_prefix}{$offset_formatted}) {$timezone}" ;
}
asort($timezone_offsets);
@endphp
@foreach ($timezone_offsets as $tz_value => $tz_label)
    <option value="{{ $tz_value }}" {{ old('timezone') == $tz_value ? 'selected' : '' }}>
        {{ $tz_label }}
    </option>
@endforeach

                                        </select>
                                        @error('timezone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Select the timezone for this
                                            country</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Analytics & Technical -->
                        <div class="col-md-6">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="bx bx-bar-chart"></i> Analytics & Technical</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Analytics Code</label>
                                        <input type="text" name="anlytics_code"
                                            class="form-control @error('anlytics_code') is-invalid @enderror"
                                            value="{{ old('anlytics_code') }}">
                                        @error('anlytics_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Google Analytics tracking code.</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Analytics Slug</label>
                                        <input type="text" name="analytics_slug"
                                            class="form-control @error('analytics_slug') is-invalid @enderror"
                                            value="{{ old('analytics_slug') }}">
                                        @error('analytics_slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Leave empty to auto-generate from
                                            country
                                            name.</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Maps URL</label>
                                        <input type="text" name="maps"
                                            class="form-control @error('maps') is-invalid @enderror"
                                            value="{{ old('maps') }}">
                                        @error('maps')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Google Maps or other mapping service
                                            URL.</small>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- JSON Data Fields -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0"><i class="bx bx-data"></i> Additional Data</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Weather Widget Code</label>
                                                <textarea name="weather"
                                                    class="form-control @error('weather') is-invalid @enderror"
                                                    rows="6">{{ old('weather') }}</textarea>
                                                @error('weather')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small class="form-text text-muted">Paste your OpenWeatherMap
                                                    widget
                                                    code here.</small>
                                            </div>

                                            <!-- News Sources Section -->
                                            <div class="mb-3">
                                                <label class="form-label">News Source</label>
                                                <input type="text" name="news"
                                                    class="form-control @error('news') is-invalid @enderror"
                                                    value="{{ old('news') }}">
                                                @error('news')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small class="form-text text-muted">Enter the main news source URL or
                                                    identifier.</small>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <!-- Local Metrics Section -->
                                            <div class="mb-3">
                                                <label class="form-label">Local Metrics</label>
                                                <div id="local-metrics-container">
                                                    <!-- Dynamic metrics will be added here -->
                                                </div>
                                                <button type="button" class="btn btn-success btn-sm"
                                                    id="add-local-metric">
                                                    <i class="bx bx-plus"></i> Add Metric
                                                </button>
                                                <input type="hidden" name="local_metrics" id="local-metrics-json">
                                                <small class="form-text text-muted">Population, GDP, currency etc.
                                                    in
                                                    JSON format.</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <livewire:excel-data-paster label="Important Links" inputName="important_links"
                                            :initialData="old('important_links')" />
                                        @error('important_links')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary px-5">Create Country Portal</button>
                            <a href="{{ route('admin.country-portals.index') }}"
                                class="btn btn-secondary px-5 ms-2">Cancel</a>
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
    document.addEventListener("DOMContentLoaded", function() {
            // ===============================
            // LOCAL METRICS HANDLER
            // ===============================
            const localMetricsContainer = document.getElementById("local-metrics-container");
            const addLocalMetricBtn = document.getElementById("add-local-metric");
            const localMetricsJson = document.getElementById("local-metrics-json");

            function updateLocalMetricsJSON() {
                const metrics = [];
                localMetricsContainer.querySelectorAll(".metric-row").forEach(row => {
                    const key = row.querySelector(".metric-key").value.trim();
                    const value = row.querySelector(".metric-value").value.trim();
                    if (key) metrics.push({
                        key,
                        value
                    });
                });
                localMetricsJson.value = JSON.stringify(metrics);
            }

            function addMetricRow(key = "", value = "") {
                const row = document.createElement("div");
                row.className = "row mb-2 metric-row";
                row.innerHTML = `
            <div class="col-md-4">
                <input type="text" class="form-control metric-key" placeholder="Metric Name (e.g., Population)" value="${key}">
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control metric-value" placeholder="Metric Value (e.g., 1.4B)" value="${value}">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm remove-metric-btn">×</button>
            </div>
        `;
                localMetricsContainer.appendChild(row);

                row.querySelectorAll("input").forEach(input =>
                    input.addEventListener("input", updateLocalMetricsJSON)
                );
                row.querySelector(".remove-metric-btn").addEventListener("click", function() {
                    row.remove();
                    updateLocalMetricsJSON();
                });
                updateLocalMetricsJSON();
            }

            addLocalMetricBtn.addEventListener("click", function() {
                addMetricRow();
            });


            // ===============================
            // IMPORTANT LINKS HANDLER
            // ===============================
            const importantLinksJson = document.getElementById("important-links-json");

            function updateImportantLinksJSON() {
                const linksData = {
                    general: [],
                    tourist_places: [],
                    community_pages: [],
                    resources: []
                };

                document.querySelectorAll("#general-links-container .link-row").forEach(row => {
                    const name = row.querySelector(".general-link-name").value.trim();
                    const url = row.querySelector(".general-link-url").value.trim();
                    if (name && url) linksData.general.push({
                        name,
                        url
                    });
                });

                document.querySelectorAll("#tourist-places-container .link-row").forEach(row => {
                    const name = row.querySelector(".tourist-place-name").value.trim();
                    const url = row.querySelector(".tourist-place-url").value.trim();
                    if (name && url) linksData.tourist_places.push({
                        name,
                        url
                    });
                });

                document.querySelectorAll("#community-pages-container .link-row").forEach(row => {
                    const name = row.querySelector(".community-page-name").value.trim();
                    const url = row.querySelector(".community-page-url").value.trim();
                    if (name && url) linksData.community_pages.push({
                        name,
                        url
                    });
                });

                document.querySelectorAll("#resources-container .link-row").forEach(row => {
                    const name = row.querySelector(".resource-name").value.trim();
                    const url = row.querySelector(".resource-url").value.trim();
                    if (name && url) linksData.resources.push({
                        name,
                        url
                    });
                });

                importantLinksJson.value = JSON.stringify(linksData);
            }

            function addLinkRow(containerSelector, nameClass, urlClass) {
                const container = document.querySelector(containerSelector);
                const row = document.createElement("div");
                row.className = "row mb-2 link-row";
                row.innerHTML = `
            <div class="col-md-4">
                <input type="text" class="form-control ${nameClass}" placeholder="Name">
            </div>
            <div class="col-md-6">
                <input type="url" class="form-control ${urlClass}" placeholder="https://example.com">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm remove-link-btn">×</button>
            </div>
        `;
                container.appendChild(row);

                row.querySelectorAll("input").forEach(input =>
                    input.addEventListener("input", updateImportantLinksJSON)
                );
                row.querySelector(".remove-link-btn").addEventListener("click", function() {
                    row.remove();
                    updateImportantLinksJSON();
                });
                updateImportantLinksJSON();
            }

            document.getElementById("add-general-link")?.addEventListener("click", () => {
                addLinkRow("#general-links-container", "general-link-name", "general-link-url");
            });

            document.querySelector(".add-tourist-link")?.addEventListener("click", () => {
                addLinkRow("#tourist-places-container", "tourist-place-name", "tourist-place-url");
            });

            document.querySelector(".add-community-link")?.addEventListener("click", () => {
                addLinkRow("#community-pages-container", "community-page-name", "community-page-url");
            });

            document.querySelector(".add-resource-link")?.addEventListener("click", () => {
                addLinkRow("#resources-container", "resource-name", "resource-url");
            });
        });
</script>
@endsection
