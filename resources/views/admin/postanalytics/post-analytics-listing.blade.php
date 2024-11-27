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
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label for="inputGeography" class="form-label">Geography</label>
                            <select id="inputGeography1" class="form-select @error('geography') is-invalid @enderror" name="geography">
                                <option selected disabled>Choose...</option>
                                @foreach($geographyOptions as $geographyOption)
                                    <option value="{{ $geographyOption->id }}">{{ $geographyOption->labelInEnglish }}</option>
                                @endforeach
                            </select>
                            @error('geography')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label id="inputLanguageLabel1" for="inputLanguageScript" class="form-label">Select</label>
                            <select id="inputLanguageScript1" class="form-select @error('c2rselect') is-invalid @enderror" name="c2rselect">
                                <option selected disabled>Choose...</option>
                            </select>
                            @error('c2rselect')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-3" style="margin-right: 60px;">
                            <label for="to" class="form-label">Date</label>
                            <input type="date" class="form-control @error('to') is-invalid @enderror" id="to" name="to" value="{{ old('to', date('Y-m-d')) }}">
                            @error('to')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary" style="margin-top: 28px;">
                                Search
                            </button>
                        </div>

                        <div class="col-md-1 mr-2">
                            <a href="{{ route('postanalytics.export', ['format' => 'csv']) }}" class="btn btn-success" style="margin-top: 28px"><i class="lni lni-files"></i></a>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-2">
                            <a class="dt-button buttons-excel buttons-html5 btn btn-success" tabindex="0" aria-controls="datatable-default" href="{{ route('postanalytics.export',['format' => 'xlsx']) }}"><span>Excel</span></a>
                        </div>
                        <div class="col-md-6"></div>
                        <div class="col-md-4">
                            <label for="search" class="form-label">serach: </label>
                            <input type="search" class="" placeholder="" aria-controls="datatable-default">
                        </div>
                    </div>

                    <table class="table mb-0 table-hover mt=4">
                        <thead class="thead-light">
                            <tr>
                                {{-- <th scope="col" class="">Date</th> --}}
                                <th scope="col" class="">S.No</th>
                                <th scope="col" class="">Geography</th>
                                <th scope="col" class="">Area</th>
                                <th scope="col" class="">Maker</th>
                                <th scope="col" class="">Checker</th>
                                <th scope="col" class="">Uploader</th>
                                <th scope="col" class="">Comments</th>
                                <th scope="col" class="">Likes</th>
                                <th scope="col" class="">App Visits</th>
                                <th scope="col" class="">Sub Title</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @foreach ($dates as $date) --}}
                            @php $index = 1;  @endphp
                            @foreach ($chittis as $chitti)
                                <tr>
                                    {{-- <td class="">{{ $date }}</td> --}}
                                    <td class="">{{ $index }}</td>
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
                                                {{ $mapping->region->regionnameInEnglish }} <!-- Show region name -->
                                            @elseif ($mapping->geographyId == 6 && $mapping->city)
                                                {{ $mapping->city->cityNameInEnglish }} <!-- Show city name -->
                                            @elseif ($mapping->geographyId == 7 && $mapping->country)
                                                {{ $mapping->country->countryNameInEnglish }} <!-- Show country name -->
                                            @else
                                                {{ $mapping->areaId }} <!-- Fallback to areaId if no match -->
                                            @endif
                                        </td>
                                    @endforeach
                                    <td class="">{{ $chitti->makerId ?? 0 }}</td>
                                    <td class="">{{ $chitti->checkerId ?? 0 }}</td>
                                    <td class="">{{ $chitti->uploaderId ?? 0 }} </td>
                                    <td class="">{{ $chitti->comments->count() ?? 0 }}
                                    <td class="">{{ $chitti->likes->count() ?? 0 }}</td>
                                    <td class="">{{ $chitti->prarangApplication ?? 0 }}</td>
                                    <td class="">{{ $chitti->SubTitle ?? '--' }}</td>
                                </tr>
                            {{-- @endforeach --}}
                            @php $index++;  @endphp
                            @endforeach
                        </tbody>
                    </table>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination round-pagination justify-content-end mt-2">
                            <li class="page-item"><a class="page-link" href="javascript:;">Previous</a>
                            </li>
                            <li class="page-item"><a class="page-link" href="javascript:;javascript:;">1</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="javascript:;">2</a>
                            </li>
                            <li class="page-item"><a class="page-link" href="javascript:;">3</a>
                            </li>
                            <li class="page-item"><a class="page-link" href="javascript:;">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->

<script>
    document.addEventListener('DOMContentLoaded', function () {
    // Select elements
    const geographySelect1 = document.getElementById('inputGeography1');
    const levelSelect1 = document.getElementById('inputLanguageScript1');
    const labelSelect1 = document.getElementById('inputLanguageLabel1');

    // Data from the backend
    const regions = @json($regions);
    const cities = @json($cities);
    const countries = @json($countries);

    geographySelect1.addEventListener('change', function () {
        const selectedValue = this.value;
        let options = [];
        let label = 'Select';

        // Clear existing options
        levelSelect1.innerHTML = '<option selected disabled>Choose...</option>';

        if (selectedValue == 5) { // Region selected
            options = regions.map(region => `<option value="${region.regionId}">${region.regionnameInEnglish}</option>`);
            label = 'Select Region';
        } else if (selectedValue == 6) { // City selected
            options = cities.map(city => `<option value="${city.cityId}">${city.cityNameInEnglish}</option>`);
            label = 'Select City';
        } else if (selectedValue == 7) { // Country selected
            options = countries.map(country => `<option value="${country.countryId}">${country.countryNameInEnglish}</option>`);
            label = 'Select Country';
        }

        // Append new options and update label
        levelSelect1.innerHTML += options.join('');
        labelSelect1.textContent = label;
    });
});
</script>
@endsection
