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
                    <li class="breadcrumb-item"><a href="{{ route('admin.country-portals.index') }}"><i class="bx bx-world"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create Country Portal</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    
    <div class="row">
        <div class="col-xl-10 mx-auto">
            <h6 class="mb-0 text-uppercase">Create New Country Portal</h6>
            <hr/>
            
            <!-- Error Messages -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h6 class="alert-heading"><i class="bx bx-error-circle"></i> Please fix the following errors:</h6>
                    <hr>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bx bx-check-circle"></i> <strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.country-portals.store') }}" method="POST">
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
                                            <label class="form-label">Country Name <span class="text-danger">*</span></label>
                                            <input type="text" name="country_name" class="form-control @error('country_name') is-invalid @enderror" value="{{ old('country_name') }}" required>
                                            @error('country_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Country Code <span class="text-danger">*</span></label>
                                            <select name="country_code" id="country_code_select" class="form-control @error('country_code') is-invalid @enderror" required>
                                                <option value="">Select Country Code</option>
                                            </select>
                                            @error('country_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Select from existing countries or type to search</small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Country Name (Local)</label>
                                            <input type="text" name="country_name_locale" class="form-control @error('country_name_locale') is-invalid @enderror" value="{{ old('country_name_locale') }}">
                                            @error('country_name_locale')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Country name in local language</small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Language Code</label>
                                            <input type="text" name="locale_lang" class="form-control @error('locale_lang') is-invalid @enderror" value="{{ old('locale_lang') }}" maxlength="10">
                                            @error('locale_lang')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Language code (e.g., en, hi, fr)</small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Slogan</label>
                                            <input type="text" name="slogan" class="form-control @error('slogan') is-invalid @enderror" value="{{ old('slogan') }}">
                                            @error('slogan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Timezone</label>
                                            <select name="timezone" id="timezone_select" class="form-control @error('timezone') is-invalid @enderror">
                                                <option value="">Select Timezone</option>
                                                @php
                                                    $timezones = timezone_identifiers_list();
                                                    $timezone_offsets = [];
                                                    foreach ($timezones as $timezone) {
                                                        $tz = new DateTimeZone($timezone);
                                                        $offset = $tz->getOffset(new DateTime('now', $tz));
                                                        $offset_prefix = $offset < 0 ? '-' : '+';
                                                        $offset_formatted = gmdate('H:i', abs($offset));
                                                        $timezone_offsets[$timezone] = "(UTC{$offset_prefix}{$offset_formatted}) {$timezone}";
                                                    }
                                                    asort($timezone_offsets);
                                                @endphp
                                                @foreach($timezone_offsets as $tz_value => $tz_label)
                                                    <option value="{{ $tz_value }}" {{ old('timezone') == $tz_value ? 'selected' : '' }}>
                                                        {{ $tz_label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('timezone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Select the timezone for this country</small>
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
                                            <input type="text" name="anlytics_code" class="form-control @error('anlytics_code') is-invalid @enderror" value="{{ old('anlytics_code') }}">
                                            @error('anlytics_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Google Analytics tracking code</small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Analytics Slug</label>
                                            <input type="text" name="analytics_slug" class="form-control @error('analytics_slug') is-invalid @enderror" value="{{ old('analytics_slug') }}">
                                            @error('analytics_slug')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Leave empty to auto-generate from country name</small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Maps URL</label>
                                            <input type="text" name="maps" class="form-control @error('maps') is-invalid @enderror" value="{{ old('maps') }}">
                                            @error('maps')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Google Maps or other mapping service URL</small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Embassy Link</label>
                                            <input type="text" name="embassy_link" class="form-control @error('embassy_link') is-invalid @enderror" value="{{ old('embassy_link') }}" placeholder="https://embassy.example.com">
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
                                        <h6 class="mb-0"><i class="bx bx-data"></i> Additional Data (JSON Format)</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Weather Widget Code</label>
                                                    <textarea name="weather" class="form-control @error('weather') is-invalid @enderror" rows="6" placeholder='<div id="openweathermap-widget-15"></div>
<script>
window.myWidgetParam ? window.myWidgetParam : window.myWidgetParam = [];  
window.myWidgetParam.push({
  id: 15,
  cityid: "2643743",
  appid: "YOUR_API_KEY_HERE",
  units: "metric",
  containerid: "openweathermap-widget-15"
});
(function() {
  var script = document.createElement("script");
  script.async = true;
  script.charset = "utf-8";
  script.src = "//openweathermap.org/themes/openweathermap/assets/vendor/owm/js/weather-widget-generator.js";
  var s = document.getElementsByTagName("script")[0];
  s.parentNode.insertBefore(script, s);  
})();
</script>'>{{ old('weather') }}</textarea>
                                                    @error('weather')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="form-text text-muted">Paste your complete OpenWeatherMap widget code here. You can customize the widget ID, city ID, and API key as needed.</small>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label">News Sources</label>
                                                    <textarea name="news" class="form-control @error('news') is-invalid @enderror" rows="4" placeholder='{"sources": ["bbc.com", "reuters.com"], "categories": ["politics", "business"]}'>{{ old('news') }}</textarea>
                                                    @error('news')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="form-text text-muted">News sources and categories in JSON format</small>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Local Metrics</label>
                                                    <textarea name="local_metrics" class="form-control @error('local_metrics') is-invalid @enderror" rows="4" placeholder='{"population": 1400000000, "gdp": 3000000000000, "currency": "INR"}'>{{ old('local_metrics') }}</textarea>
                                                    @error('local_metrics')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="form-text text-muted">Population, GDP, currency etc. in JSON format</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Important Links</label>
                                            <div id="important-links-container">
                                                @php
                                                    $importantLinks = old('important_links');
                                                    $linksArray = is_string($importantLinks) ? json_decode($importantLinks, true) : $importantLinks;
                                                    if (!is_array($linksArray)) {
                                                        $linksArray = ['tourist_places' => [], 'community_pages' => [], 'resources' => []];
                                                    }
                                                    
                                                    // Ensure all required categories exist
                                                    $linksArray = array_merge(['tourist_places' => [], 'community_pages' => [], 'resources' => []], $linksArray);
                                                @endphp
                                                
                                                <!-- Tourist Places -->
                                                <div class="card mb-3">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0">Tourist Places</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div id="tourist-places-container">
                                                            @if(isset($linksArray['tourist_places']) && is_array($linksArray['tourist_places']))
                                                                @foreach($linksArray['tourist_places'] as $url)
                                                                    <div class="row mb-2 link-row">
                                                                        <div class="col-md-11">
                                                                            <input type="text" name="tourist_places[]" class="form-control" placeholder="https://example.com/place" value="{{ $url }}">
                                                                        </div>
                                                                        <div class="col-md-1">
                                                                            <button type="button" class="btn btn-danger btn-sm remove-link-btn">×</button>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        <button type="button" class="btn btn-success btn-sm add-tourist-link">+ Add Tourist Place</button>
                                                    </div>
                                                </div>
                                                
                                                <!-- Community Pages -->
                                                <div class="card mb-3">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0">Community Pages</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div id="community-pages-container">
                                                            @if(isset($linksArray['community_pages']) && is_array($linksArray['community_pages']))
                                                                @foreach($linksArray['community_pages'] as $url)
                                                                    <div class="row mb-2 link-row">
                                                                        <div class="col-md-11">
                                                                            <input type="text" name="community_pages[]" class="form-control" placeholder="https://facebook.com/community" value="{{ $url }}">
                                                                        </div>
                                                                        <div class="col-md-1">
                                                                            <button type="button" class="btn btn-danger btn-sm remove-link-btn">×</button>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        <button type="button" class="btn btn-success btn-sm add-community-link">+ Add Community Page</button>
                                                    </div>
                                                </div>
                                                
                                                <!-- Resources -->
                                                <div class="card mb-3">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0">Resources</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div id="resources-container">
                                                            @if(isset($linksArray['resources']) && is_array($linksArray['resources']))
                                                                @foreach($linksArray['resources'] as $url)
                                                                    <div class="row mb-2 link-row">
                                                                        <div class="col-md-11">
                                                                            <input type="text" name="resources[]" class="form-control" placeholder="https://gov.example.com/resource" value="{{ $url }}">
                                                                        </div>
                                                                        <div class="col-md-1">
                                                                            <button type="button" class="btn btn-danger btn-sm remove-link-btn">×</button>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        <button type="button" class="btn btn-success btn-sm add-resource-link">+ Add Resource</button>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <input type="hidden" name="important_links" id="important-links-json">
                                            @error('important_links')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Add categorized important links for this country portal</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary px-5">Create Country Portal</button>
                                <a href="{{ route('admin.country-portals.index') }}" class="btn btn-secondary px-5 ms-2">Cancel</a>
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
    // Load countries for dropdown
    loadCountriesDropdown();
    
    // Initialize timezone select with search functionality
    initializeTimezoneSelect();
    
    // Auto-generate analytics slug from country name
    const countryNameInput = document.querySelector('input[name="country_name"]');
    const analyticsSlugInput = document.querySelector('input[name="analytics_slug"]');
    const countryCodeSelect = document.getElementById('country_code_select');
    
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
        if (this.value) {
            this.dataset.autoGenerated = 'false';
        }
    });
    
    // Auto-fill country name when country code is selected
    countryCodeSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.dataset.countryName && !countryNameInput.value) {
            countryNameInput.value = selectedOption.dataset.countryName;
            countryNameInput.dispatchEvent(new Event('input')); // Trigger slug generation
        }
        if (selectedOption.dataset.countryNameUnicode && !document.querySelector('input[name="country_name_locale"]').value) {
            document.querySelector('input[name="country_name_locale"]').value = selectedOption.dataset.countryNameUnicode;
        }
    });
    
    // Handle dynamic important links - new categorized format
    function createLinkRow(containerSelector, inputName) {
        const container = document.querySelector(containerSelector);
        const newRow = document.createElement('div');
        newRow.className = 'row mb-2 link-row';
        newRow.innerHTML = `
            <div class="col-md-11">
                <input type="text" name="${inputName}[]" class="form-control" placeholder="https://example.com">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm remove-link-btn">×</button>
            </div>
        `;
        container.appendChild(newRow);
        
        // Add remove functionality
        newRow.querySelector('.remove-link-btn').addEventListener('click', function() {
            newRow.remove();
        });
    }
    
    // Add tourist place link
    document.querySelectorAll('.add-tourist-link').forEach(btn => {
        btn.addEventListener('click', function() {
            createLinkRow('#tourist-places-container', 'tourist_places');
        });
    });
    
    // Add community page link
    document.querySelectorAll('.add-community-link').forEach(btn => {
        btn.addEventListener('click', function() {
            createLinkRow('#community-pages-container', 'community_pages');
        });
    });
    
    // Add resource link
    document.querySelectorAll('.add-resource-link').forEach(btn => {
        btn.addEventListener('click', function() {
            createLinkRow('#resources-container', 'resources');
        });
    });
    
    // Add remove functionality to existing links
    document.querySelectorAll('.remove-link-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            btn.closest('.link-row').remove();
        });
    });
    
    // Convert categorized links to JSON before form submission
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const jsonInput = document.getElementById('important-links-json');
        
        // Collect all tourist places
        const touristPlaces = Array.from(document.querySelectorAll('input[name="tourist_places[]"]'))
            .map(input => input.value.trim())
            .filter(url => url !== '');
        
        // Collect all community pages
        const communityPages = Array.from(document.querySelectorAll('input[name="community_pages[]"]'))
            .map(input => input.value.trim())
            .filter(url => url !== '');
        
        // Collect all resources
        const resources = Array.from(document.querySelectorAll('input[name="resources[]"]'))
            .map(input => input.value.trim())
            .filter(url => url !== '');
        
        // Create the categorized JSON object
        const linksObj = {
            tourist_places: touristPlaces,
            community_pages: communityPages,
            resources: resources
        };
        
        jsonInput.value = JSON.stringify(linksObj);
    });
});

function initializeTimezoneSelect() {
    const timezoneSelect = document.getElementById('timezone_select');
    
    // Check if Select2 is available
    if (typeof jQuery !== 'undefined' && jQuery.fn.select2) {
        jQuery('#timezone_select').select2({
            placeholder: 'Select or search timezone',
            allowClear: true,
            width: '100%',
            matcher: function(params, data) {
                // If there are no search terms, return all data
                if (jQuery.trim(params.term) === '') {
                    return data;
                }
                
                // Search in both timezone name and UTC offset
                const searchTerm = params.term.toLowerCase();
                const text = data.text.toLowerCase();
                
                if (text.indexOf(searchTerm) > -1) {
                    return data;
                }
                
                return null;
            }
        });
    } else {
        // Fallback: Add native search capability with size attribute for better UX
        timezoneSelect.setAttribute('size', '1');
        timezoneSelect.style.height = 'auto';
    }
}

function loadCountriesDropdown() {
    fetch('{{ route("admin.api.countries-dropdown") }}')
        .then(response => response.json())
        .then(countries => {
            const select = document.getElementById('country_code_select');
            const oldValue = '{{ old("country_code") }}';
            
            countries.forEach(country => {
                const option = document.createElement('option');
                option.value = country.countryCode;
                option.textContent = `${country.countryCode} - ${country.countryNameInEnglish}`;
                option.dataset.countryName = country.countryNameInEnglish;
                option.dataset.countryNameUnicode = country.countryNameInUnicode;
                
                if (oldValue && oldValue === country.countryCode) {
                    option.selected = true;
                }
                
                select.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error loading countries:', error);
            // Fallback to manual input
            const select = document.getElementById('country_code_select');
            select.outerHTML = '<input type="text" name="country_code" class="form-control" value="{{ old("country_code") }}" maxlength="10" required placeholder="Enter country code (e.g., IN, US, GB)">';
        });
}
</script>
@endsection
