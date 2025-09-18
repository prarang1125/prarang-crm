@extends('layouts.admin.admin')
@section('title', 'Post Analytics Listing')

@section('content')
<!--start page wrapper -->
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Admin</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/postanalytics/post-analytics-listing')}}"><i class="bx bx-user"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Post Analytics Listing</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="col-xl-9 mx-auto w-100">
            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success mt-3">
                    {{ session('success') }}
                </div>
            @endif
            <h6 class="mb-0 text-uppercase">Post Analytics Listing</h6>
            <hr/>
            <div class="card">
                <div class="card-body">
                    <form action="{{ url('admin/postanalytics/post-analytics-listing') }}" method="GET">
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label for="search" class="form-label">Search</label>
                                <input type="text" name="search" class="form-control" placeholder="Search by Title/SubTitle" value="{{ request()->input('search') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="inputGeography" class="form-label">Geography</label>
                                <select id="inputGeography1" class="form-select" name="geography">
                                    <option value="">Choose...</option>
                                    @foreach($geographyOptions as $geographyOption)
                                        <option value="{{ $geographyOption->id }}" @if(request()->input('geography') == $geographyOption->id) selected @endif>{{ $geographyOption->labelInEnglish }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label id="inputLanguageLabel1" for="inputLanguageScript" class="form-label">Area</label>
                                <select id="inputLanguageScript1" class="form-select" name="area">
                                    <option value="">Choose...</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="from_date" class="form-label">From Date</label>
                                <input type="date" class="form-control" id="from_date" name="from_date" value="{{ request()->input('from_date') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="to_date" class="form-label">To Date</label>
                                <input type="date" class="form-control" id="to_date" name="to_date" value="{{ request()->input('to_date') }}">
                            </div>
                            <div class="col-md-2">
                                <label for="per_page" class="form-label">Per Page</label>
                                <select class="form-select" name="per_page">
                                    <option value="10" @if(request()->input('per_page') == 10) selected @endif>10</option>
                                    <option value="30" @if(request()->input('per_page', 30) == 30) selected @endif>30</option>
                                    <option value="50" @if(request()->input('per_page') == 50) selected @endif>50</option>
                                    <option value="100" @if(request()->input('per_page') == 100) selected @endif>100</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary" style="margin-top: 28px;">
                                    Search
                                </button>
                                <a href="{{ url('admin/postanalytics/post-analytics-listing') }}" class="btn btn-secondary" style="margin-top: 28px;">Reset</a>
                            </div>
                        </div>
                    </form>

                    <div class="row mt-3">
                        <div class="col-md-3">
                             <a class="dt-button buttons-excel buttons-html5 btn btn-success" tabindex="0" aria-controls="datatable-default" href="{{ route('postanalytics.export', array_merge(request()->query(), ['format' => 'xlsx'])) }}"><span>Excel</span></a>
                             <a href="{{ route('postanalytics.export', array_merge(request()->query(), ['format' => 'csv'])) }}" class="btn btn-success"><i class="lni lni-files"></i> CSV</a>
                        </div>
                    </div>

                    <table class="table mb-0 table-hover mt-4">
                        <thead class="thead-light">
                            <tr>
                                @php
                                    $sort_by = request()->get('sort_by', 'dateOfCreation');
                                    $sort_order = request()->get('sort_order', 'desc');
                                @endphp
                                <th><a href="{{ request()->fullUrlWithQuery(['sort_by' => 'chittiId', 'sort_order' => $sort_by == 'chittiId' && $sort_order == 'asc' ? 'desc' : 'asc']) }}">S.No</a></th>
                                <th>Geography</th>
                                <th>Area</th>
                                <th><a href="{{ request()->fullUrlWithQuery(['sort_by' => 'comments_count', 'sort_order' => $sort_by == 'comments_count' && $sort_order == 'asc' ? 'desc' : 'asc']) }}">Comments</a></th>
                                <th><a href="{{ request()->fullUrlWithQuery(['sort_by' => 'likes_count', 'sort_order' => $sort_by == 'likes_count' && $sort_order == 'asc' ? 'desc' : 'asc']) }}">Likes</a></th>
                                <th><a href="{{ request()->fullUrlWithQuery(['sort_by' => 'prarangApplication', 'sort_order' => $sort_by == 'prarangApplication' && $sort_order == 'asc' ? 'desc' : 'asc']) }}">App Visits</a></th>
                                <th><a href="{{ request()->fullUrlWithQuery(['sort_by' => 'SubTitle', 'sort_order' => $sort_by == 'SubTitle' && $sort_order == 'asc' ? 'desc' : 'asc']) }}">Sub Title</a></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($chittis as $chitti)
                                <tr>
                                    <td>{{ $chitti->chittiId }}</td>
                                    @if ($chitti->geographyMappings->isNotEmpty())
                                        @foreach ($chitti->geographyMappings as $mapping)
                                            @php
                                                $option = $geographyOptions->firstWhere('id', $mapping->geographyId);
                                            @endphp
                                            <td data-gmid="{{ $mapping->geographyId }}">
                                                @if($option)
                                                    {{ $option->labelInEnglish }}
                                                @else
                                                    {{ $mapping->geographyId }}
                                                @endif
                                            </td>
                                            <td data-areaid="{{ $mapping->areaId }}">
                                                @if ($mapping->geographyId == 5 && $mapping->region)
                                                    {{ $mapping->region->regionnameInEnglish }}
                                                @elseif ($mapping->geographyId == 6 && $mapping->city)
                                                    {{ $mapping->city->citynameInEnglish }}
                                                @elseif ($mapping->geographyId == 7 && $mapping->country)
                                                    {{ $mapping->country->countryNameInEnglish }}
                                                @else
                                                    {{ $mapping->areaId }}
                                                @endif
                                            </td>
                                        @endforeach
                                    @else
                                        <td>-</td>
                                        <td>-</td>
                                    @endif
                                    <td>{{ $chitti->comments_count ?? 0 }}</td>
                                    <td>{{ $chitti->likes_count ?? 0 }}</td>
                                    <td>{{ $chitti->prarangApplication ?? 0 }}</td>
                                    <td>{{ $chitti->SubTitle ?? '--' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end mt-4">
                        {{ $chittis->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const geographySelect = document.getElementById('inputGeography1');
        const areaSelect = document.getElementById('inputLanguageScript1');
        const areaLabel = document.getElementById('inputLanguageLabel1');

        const regions = @json($regions);
        const cities = @json($cities);
        const countries = @json($countries);

        function updateAreaOptions() {
            const selectedGeography = geographySelect.value;
            let options = [];
            let label = 'Area';
            
            areaSelect.innerHTML = '<option value="">Choose...</option>';

            if (selectedGeography == 5) { // Region
                options = regions.map(region => `<option value="${region.regionId}" ${'{{ request()->input("area") }}' == region.regionId ? 'selected' : ''}>${region.regionnameInEnglish}</option>`);
                label = 'Select Region';
            } else if (selectedGeography == 6) { // City
                options = cities.map(city => `<option value="${city.cityId}" ${'{{ request()->input("area") }}' == city.cityId ? 'selected' : ''}>${city.citynameInEnglish}</option>`);
                label = 'Select City';
            } else if (selectedGeography == 7) { // Country
                options = countries.map(country => `<option value="${country.countryId}" ${'{{ request()->input("area") }}' == country.countryId ? 'selected' : ''}>${country.countryNameInEnglish}</option>`);
                label = 'Select Country';
            }

            areaSelect.innerHTML += options.join('');
            areaLabel.textContent = label;
        }

        geographySelect.addEventListener('change', updateAreaOptions);
        
        // Initial call to populate area options if a geography is already selected
        if (geographySelect.value) {
            updateAreaOptions();
        }
    });
</script>
@endsection
