@extends('layouts.admin.admin')
@section('title', 'Edit Country Portal')

@section('content')
    <!--start page wrapper -->
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Admin</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.country-portals.index') }}"><i
                                    class="bx bx-world"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Country Portal</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-xl-10 mx-auto">
                <h6 class="mb-0 text-uppercase">Edit Country Portal: {{ $countryPortal->country_name }}</h6>
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

                <!-- Error Message -->
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bx bx-error-circle"></i> <strong>Error!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.country-portals.update', $countryPortal) }}" method="POST"
                            id="country-portal-form">
                            @csrf
                            @method('PUT')

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
                                                <input type="text" name="country_name" class="form-control"
                                                    value="{{ old('country_name', $countryPortal->country_name) }}"
                                                    required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Country Code <span
                                                        class="text-danger">*</span></label>
                                                <select name="country_code" id="country_code_select" class="form-control"
                                                    required>
                                                    <option value="">Select Country Code</option>
                                                </select>
                                                <small class="form-text text-muted">Select from existing countries or type
                                                    to search</small>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Country Name (Local)</label>
                                                <input type="text" name="country_name_locale" class="form-control"
                                                    value="{{ old('country_name_locale', $countryPortal->country_name_locale) }}">
                                                <small class="form-text text-muted">Country name in local language</small>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Language Code</label>
                                                <input type="text" name="locale_lang" class="form-control"
                                                    value="{{ old('locale_lang', $countryPortal->locale_lang) }}"
                                                    maxlength="10">
                                                <small class="form-text text-muted">Language code (e.g., en, hi, fr)</small>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Slogan</label>
                                                <input type="text" name="slogan" class="form-control"
                                                    value="{{ old('slogan', $countryPortal->slogan) }}">
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
                                                            $offset_prefix = $offset < 0 ? '-' : '+';
                                                            $offset_formatted = gmdate('H:i', abs($offset));
                                                            $timezone_offsets[
                                                                $timezone
                                                            ] = "(UTC{$offset_prefix}{$offset_formatted}) {$timezone}";
                                                        }
                                                        asort($timezone_offsets);
                                                        $selectedTimezone = old('timezone', $countryPortal->timezone);
                                                    @endphp
                                                    @foreach ($timezone_offsets as $tz_value => $tz_label)
                                                        <option value="{{ $tz_value }}"
                                                            {{ $selectedTimezone == $tz_value ? 'selected' : '' }}>
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
                                                <input type="text" name="anlytics_code" class="form-control"
                                                    value="{{ old('anlytics_code', $countryPortal->anlytics_code) }}">
                                                <small class="form-text text-muted">Google Analytics tracking code</small>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Analytics Slug</label>
                                                <input type="text" name="analytics_slug" class="form-control"
                                                    value="{{ old('analytics_slug', $countryPortal->analytics_slug) }}">
                                                <small class="form-text text-muted">Leave empty to auto-generate from
                                                    country name</small>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Maps URL</label>
                                                <input type="text" name="maps" class="form-control"
                                                    value="{{ old('maps', $countryPortal->maps) }}">
                                                <small class="form-text text-muted">Google Maps or other mapping service
                                                    URL</small>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Embassy Link</label>
                                                <input type="text" name="embassy_link"
                                                    class="form-control @error('embassy_link') is-invalid @enderror"
                                                    value="{{ old('embassy_link', $countryPortal->embassy_link) }}"
                                                    placeholder="https://embassy.example.com">
                                                @error('embassy_link')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small class="form-text text-muted">Official embassy website URL</small>
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
                                            <h6 class="mb-0"><i class="bx bx-data"></i> Additional Data (JSON Format)
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Weather Widget Code</label>
                                                        <textarea name="weather" class="form-control" rows="6">{{ old('weather', $countryPortal->weather) }}</textarea>
                                                        <small class="form-text text-muted">Paste your complete
                                                            OpenWeatherMap widget code here. You can customize the widget
                                                            ID, city ID, and API key as needed.</small>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">News</label>
                                                        <input type="text" name="news" class="form-control"
                                                            value="{{ old('news', $countryPortal->news) }}">
                                                        <small class="form-text text-muted">Enter news or updates related
                                                            to this country portal</small>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <!-- Local Metrics Section -->
                                                    <div class="mb-3">
                                                        <label class="form-label">Local Metrics</label>
                                                        <div id="local-metrics-container">
                                                            @php
                                                                $metricsData = old(
                                                                    'local_metrics',
                                                                    $countryPortal->local_metrics,
                                                                );
                                                                $metricsArray = is_string($metricsData)
                                                                    ? json_decode($metricsData, true)
                                                                    : $metricsData;
                                                                if (!is_array($metricsArray)) {
                                                                    $metricsArray = [];
                                                                }
                                                            @endphp
                                                            @foreach ($metricsArray as $value)
                                                                <div class="row mb-2 metric-row">
                                                                    <div class="col-md-4">
                                                                        <input type="text"
                                                                            class="form-control metric-key"
                                                                            placeholder="Metric Name (e.g., Population)"
                                                                            value="{{ $value['key'] ?? '' }}">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <input type="text"
                                                                            class="form-control metric-value"
                                                                            placeholder="Metric Value (e.g., 1.4B)"
                                                                            value="{{ $value['value'] ?? '' }}">
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <button type="button"
                                                                            class="btn btn-danger btn-sm remove-metric-btn">×</button>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <button type="button" class="btn btn-success btn-sm"
                                                            id="add-local-metric">
                                                            <i class="bx bx-plus"></i>
                                                            Add Metric
                                                        </button>
                                                        <input type="hidden" name="local_metrics"
                                                            id="local-metrics-json">
                                                        <small class="form-text text-muted">Add key-value pairs for local
                                                            metrics</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Important Links</label>
                                                <div id="important-links-container">
                                                    @php
                                                        $importantLinks = old(
                                                            'important_links',
                                                            $countryPortal->important_links,
                                                        );
                                                        $linksArray = is_string($importantLinks)
                                                            ? json_decode($importantLinks, true)
                                                            : $importantLinks;
                                                        if (!is_array($linksArray)) {
                                                            $linksArray = [
                                                                'tourist_places' => [],
                                                                'community_pages' => [],
                                                                'resources' => [],
                                                            ];
                                                        }

                                                        // Ensure all required categories exist
                                                        $linksArray = array_merge(
                                                            [
                                                                'general' => [],
                                                                'tourist_places' => [],
                                                                'community_pages' => [],
                                                                'resources' => [],
                                                            ],
                                                            $linksArray,
                                                        );
                                                    @endphp

                                                    <!-- General Important Links -->
                                                    <div class="card mb-3">
                                                        <div class="card-header bg-light">
                                                            <h6 class="mb-0">General Important Links</h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <div id="general-links-container">
                                                                @if (isset($linksArray['general']) && is_array($linksArray['general']))
                                                                    @foreach ($linksArray['general'] as $link)
                                                                        <div class="row mb-2 link-row">
                                                                            <div class="col-md-4">
                                                                                <input type="text"
                                                                                    class="form-control general-link-name"
                                                                                    placeholder="Link Name (e.g., Official Website)"
                                                                                    value="{{ is_array($link) ? $link['name'] ?? '' : $link }}">
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <input type="url"
                                                                                    class="form-control general-link-url"
                                                                                    placeholder="https://example.com"
                                                                                    value="{{ is_array($link) ? $link['url'] ?? '' : $link }}">
                                                                            </div>
                                                                            <div class="col-md-2">
                                                                                <button type="button"
                                                                                    class="btn btn-danger btn-sm remove-link-btn">×</button>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <button type="button"
                                                                class="btn btn-success btn-sm add-general-link">+ Add
                                                                General Link</button>
                                                        </div>
                                                    </div>

                                                    <!-- Tourist Places -->
                                                    <div class="card mb-3">
                                                        <div class="card-header bg-light">
                                                            <h6 class="mb-0">Tourist Places</h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <div id="tourist-places-container">
                                                                @if (isset($linksArray['tourist_places']) && is_array($linksArray['tourist_places']))
                                                                    @foreach ($linksArray['tourist_places'] as $link)
                                                                        <div class="row mb-2 link-row">
                                                                            <div class="col-md-4">
                                                                                <input type="text"
                                                                                    class="form-control tourist-place-name"
                                                                                    placeholder="Place Name (e.g., Taj Mahal)"
                                                                                    value="{{ is_array($link) ? $link['name'] ?? '' : $link }}">
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <input type="url"
                                                                                    class="form-control tourist-place-url"
                                                                                    placeholder="https://example.com/place"
                                                                                    value="{{ is_array($link) ? $link['url'] ?? '' : $link }}">
                                                                            </div>
                                                                            <div class="col-md-2">
                                                                                <button type="button"
                                                                                    class="btn btn-danger btn-sm remove-link-btn">×</button>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <button type="button"
                                                                class="btn btn-success btn-sm add-tourist-link">+ Add
                                                                Tourist Place</button>
                                                        </div>
                                                    </div>

                                                    <!-- Community Pages -->
                                                    <div class="card mb-3">
                                                        <div class="card-header bg-light">
                                                            <h6 class="mb-0">Community Pages</h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <div id="community-pages-container">
                                                                @if (isset($linksArray['community_pages']) && is_array($linksArray['community_pages']))
                                                                    @foreach ($linksArray['community_pages'] as $link)
                                                                        <div class="row mb-2 link-row">
                                                                            <div class="col-md-4">
                                                                                <input type="text"
                                                                                    class="form-control community-page-name"
                                                                                    placeholder="Page Name (e.g., Facebook Group)"
                                                                                    value="{{ is_array($link) ? $link['name'] ?? '' : $link }}">
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <input type="url"
                                                                                    class="form-control community-page-url"
                                                                                    placeholder="https://facebook.com/community"
                                                                                    value="{{ is_array($link) ? $link['url'] ?? '' : $link }}">
                                                                            </div>
                                                                            <div class="col-md-2">
                                                                                <button type="button"
                                                                                    class="btn btn-danger btn-sm remove-link-btn">×</button>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <button type="button"
                                                                class="btn btn-success btn-sm add-community-link">+ Add
                                                                Community Page</button>
                                                        </div>
                                                    </div>

                                                    <!-- Resources -->
                                                    <div class="card mb-3">
                                                        <div class="card-header bg-light">
                                                            <h6 class="mb-0">Resources</h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <div id="resources-container">
                                                                @if (isset($linksArray['resources']) && is_array($linksArray['resources']))
                                                                    @foreach ($linksArray['resources'] as $link)
                                                                        <div class="row mb-2 link-row">
                                                                            <div class="col-md-4">
                                                                                <input type="text"
                                                                                    class="form-control resource-name"
                                                                                    placeholder="Resource Name (e.g., Government Portal)"
                                                                                    value="{{ is_array($link) ? $link['name'] ?? '' : $link }}">
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <input type="url"
                                                                                    class="form-control resource-url"
                                                                                    placeholder="https://gov.example.com/resource"
                                                                                    value="{{ is_array($link) ? $link['url'] ?? '' : $link }}">
                                                                            </div>
                                                                            <div class="col-md-2">
                                                                                <button type="button"
                                                                                    class="btn btn-danger btn-sm remove-link-btn">×</button>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <button type="button"
                                                                class="btn btn-success btn-sm add-resource-link">+ Add
                                                                Resource</button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <input type="hidden" name="important_links" id="important-links-json">
                                                <small class="form-text text-muted">Add categorized important links for
                                                    this country portal</small>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Connections</label>
                                                    <textarea name="connections" class="form-control" rows="4">{{ old('connections', $countryPortal->connections) }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Header Scripts</label>
                                                    <textarea name="header_scripts" class="form-control" rows="4">{{ old('header_scripts', $countryPortal->header_scripts) }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Footer Scripts</label>
                                                    <textarea name="footer_scripts" class="form-control" rows="4">{{ old('footer_scripts', $countryPortal->footer_scripts) }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12 text-center">

                                    <button type="submit" class="btn btn-primary px-5">Update Country Portal</button>
                                    <a href="{{ route('admin.country-portals.index') }}"
                                        class="btn btn-secondary px-5 ms-2">Cancel</a>
                                    <a href="{{ route('admin.country-portals.show', $countryPortal) }}"
                                        class="btn btn-info px-5 ms-2">View Details</a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Load countries for dropdown
            loadCountriesDropdown();

            // Initialize timezone select with search functionality
            initializeTimezoneSelect();

            // Function to load countries dropdown
            async function loadCountriesDropdown() {
                try {
                    const response = await fetch('/admin/api/countries-dropdown');

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const countries = await response.json();
                    const select = document.getElementById('country_code_select');

                    // Clear existing options except the first one
                    select.innerHTML = '<option value="">Select Country Code</option>';

                    // Current country code for selection
                    const currentCountryCode = @json($countryPortal->country_code ?? '');

                    countries.forEach(country => {
                        const option = document.createElement('option');
                        option.value = country.country_code;
                        option.textContent = `${country.country_code} - ${country.country_name}`;
                        option.dataset.countryName = country.country_name;
                        option.dataset.countryNameUnicode = country.country_name_unicode;

                        if (country.country_code === currentCountryCode) {
                            option.selected = true;
                        }

                        select.appendChild(option);
                    });
                } catch (error) {
                    console.error('Error loading countries:', error);

                    // Show user-friendly error message
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-warning alert-dismissible fade show';
                    alertDiv.innerHTML = `
                        <i class="bx bx-error-circle"></i>
                        <strong>Warning:</strong> Failed to load countries dropdown. Please refresh the page.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;

                    // Insert error message at the top of the form
                    const form = document.getElementById('country-portal-form');
                    form.insertBefore(alertDiv, form.firstChild);
                }
            }

            // Function to initialize timezone select with search
            function initializeTimezoneSelect() {
                const timezoneSelect = document.getElementById('timezone_select');
                if (timezoneSelect) {
                    // Add search functionality if needed
                    timezoneSelect.style.height = 'auto';
                }
            }

            // Auto-generate analytics slug from country name if slug is empty
            const countryNameInput = document.querySelector('input[name="country_name"]');
            const analyticsSlugInput = document.querySelector('input[name="analytics_slug"]');
            const countryCodeSelect = document.getElementById('country_code_select');
            const originalSlug = analyticsSlugInput.value;

            countryNameInput.addEventListener('input', function() {
                if (!analyticsSlugInput.value || analyticsSlugInput.dataset.autoGenerated) {
                    const slug = this.value.toLowerCase()
                        .replace(/[^a-z0-9\s-]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-')
                        .trim('-');
                    analyticsSlugInput.value = slug;
                    analyticsSlugInput.dataset.autoGenerated = 'true';
                }
            });

            analyticsSlugInput.addEventListener('input', function() {
                if (this.value !== originalSlug) {
                    this.dataset.autoGenerated = 'false';
                }
            });

            // Auto-fill country name when country code is selected (only if fields are empty)
            countryCodeSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.dataset.countryName && !countryNameInput.value.trim()) {
                    countryNameInput.value = selectedOption.dataset.countryName;
                    countryNameInput.dispatchEvent(new Event('input')); // Trigger slug generation
                }
                if (selectedOption.dataset.countryNameUnicode && !document.querySelector(
                        'input[name="country_name_locale"]').value.trim()) {
                    document.querySelector('input[name="country_name_locale"]').value = selectedOption
                        .dataset.countryNameUnicode;
                }
            });

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

            // Initialize existing remove buttons
            document.querySelectorAll(".remove-link-btn").forEach(btn => {
                btn.addEventListener("click", function() {
                    btn.closest(".link-row").remove();
                    updateImportantLinksJSON();
                });
            });

            document.querySelectorAll(".remove-metric-btn").forEach(btn => {
                btn.addEventListener("click", function() {
                    btn.closest(".metric-row").remove();
                    updateLocalMetricsJSON();
                });
            });

            // Update JSON on page load for existing data
            updateLocalMetricsJSON();
            updateImportantLinksJSON();

            // Handle form submission
            document.getElementById("country-portal-form").addEventListener("submit", function(e) {
                // Client-side validation
                const countryName = document.querySelector('input[name="country_name"]').value.trim();
                const countryCode = document.querySelector('select[name="country_code"]').value.trim();

                if (!countryName) {
                    e.preventDefault();
                    showAlert('error', 'Country Name is required.');
                    return false;
                }

                if (!countryCode) {
                    e.preventDefault();
                    showAlert('error', 'Country Code is required.');
                    return false;
                }

                updateLocalMetricsJSON();
                updateImportantLinksJSON();

                // Show loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Updating...';
                submitBtn.disabled = true;

                // Re-enable button after 5 seconds if form is still on page (in case of validation errors)
                setTimeout(() => {
                    if (submitBtn) {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                }, 5000);
            });

            // Function to show alerts
            function showAlert(type, message) {
                const alertClass = type === 'error' ? 'alert-danger' : 'alert-success';
                const icon = type === 'error' ? 'bx-error-circle' : 'bx-check-circle';

                const alertDiv = document.createElement('div');
                alertDiv.className = `alert ${alertClass} alert-dismissible fade show`;
                alertDiv.innerHTML = `
                    <i class="bx ${icon}"></i> <strong>${type === 'error' ? 'Error!' : 'Success!'}</strong> ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;

                // Remove existing alerts
                document.querySelectorAll('.alert').forEach(alert => {
                    if (alert.querySelector('.bx-error-circle, .bx-check-circle')) {
                        alert.remove();
                    }
                });

                // Insert new alert at the top of the form
                const form = document.getElementById('country-portal-form');
                form.insertBefore(alertDiv, form.firstChild);

                // Auto-remove after 5 seconds
                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        alertDiv.remove();
                    }
                }, 5000);
            }
        });
    </script>

@endsection
