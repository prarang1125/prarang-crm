@extends('layouts.admin.admin')
@section('title', 'Prarang Admin Home')
@section('content')
<div class="page-content">
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
        <div class="col">
            <a href="{{ url('/admin/user-listing?role=Maker') }}">
            <div class="card radius-10 bg-gradient-deepblue">
                 <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0 text-white">{{ $totalMakers }}</h5>
                        <div class="ms-auto">
                            <i class='bx bx-user fs-3 text-white'></i>
                        </div>
                    </div>
                    <div class="progress my-3 bg-light-transparent" style="height:3px;">
                        <div class="progress-bar bg-white" role="progressbar"
                            style="width: {{ $growthMakers > 100 ? 100 : $growthMakers }}%"
                            aria-valuenow="{{ $growthMakers }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <div class="d-flex align-items-center text-white">
                        <p class="mb-0">REGISTERED  Maker</p>
                        <p class="mb-0 ms-auto">
                            {{ $growthMakers > 0 ? '+' : '' }}{{ number_format($growthMakers, 1) }}%
                            <span><i class='bx bx-up-arrow-alt'></i></span>
                        </p>
                    </div>
                </div>
            </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ url('/admin/user-listing?role=Checker') }}">
            <div class="card radius-10 bg-gradient-orange">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0 text-white">{{ $totalChecker }}</h5>
                        <div class="ms-auto">
                            <i class='bx bx-user fs-3 text-white'></i>
                        </div>
                    </div>
                    <div class="progress my-3 bg-light-transparent" style="height:3px;">
                        <div class="progress-bar bg-white" role="progressbar"
                             style="width: {{ $growthChecker > 100 ? 100 : $growthChecker }}%"
                             aria-valuenow="{{ $growthChecker }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <div class="d-flex align-items-center text-white">
                        <p class="mb-0">REGISTERED Checker</p>
                        <p class="mb-0 ms-auto">
                            {{ $growthChecker > 0 ? '+' : '' }}{{ number_format($growthChecker, 1) }}%
                            <span><i class='bx bx-up-arrow-alt'></i></span>
                        </p>
                    </div>
                </div>
            </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ url('/admin/user-listing?role=Uploader') }}">
            <div class="card radius-10 bg-gradient-ohhappiness">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0 text-white">{{ $totalUploader }}</h5>
                        <div class="ms-auto">
                            <i class='bx bx-user fs-3 text-white'></i>
                        </div>
                    </div>
                    <div class="progress my-3 bg-light-transparent" style="height:3px;">
                        <div class="progress-bar bg-white" role="progressbar"
                             style="width: {{ $growthUploader > 100 ? 100 : $growthUploader }}%"
                             aria-valuenow="{{ $growthUploader }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <div class="d-flex align-items-center text-white">
                        <p class="mb-0">REGISTERED Uploader</p>
                        <p class="mb-0 ms-auto">
                            {{ $growthUploader > 0 ? '+' : '' }}{{ number_format($growthUploader, 1) }}%
                            <span><i class='bx bx-up-arrow-alt'></i></span>
                        </p>
                    </div>
                </div>
            </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ url('/admin/country/country-listing') }}">
            <div class="card radius-10 bg-gradient-deepblue">
                 <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0 text-white">{{ $totalCountries }}</h5>
                        <div class="ms-auto">
                            <i class='bx bx-world fs-3 text-white'></i>
                        </div>
                    </div>
                    <div class="progress my-3 bg-light-transparent" style="height:3px;">
                        <div class="progress-bar bg-white" role="progressbar"
                             style="width: {{ $growthCountries > 100 ? 100 : $growthCountries }}%"
                             aria-valuenow="{{ $growthCountries }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <div class="d-flex align-items-center text-white">
                        <p class="mb-0">TOTAL COUNTRY</p>
                        <p class="mb-0 ms-auto">
                            {{ $growthCountries > 0 ? '+' : '' }}{{ number_format($growthCountries, 1) }}%
                            <span><i class='bx bx-up-arrow-alt'></i></span>
                        </p>
                    </div>
                </div>
            </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ url('/admin/region/region-listing') }}">
            <div class="card radius-10 bg-gradient-orange">
                 <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0 text-white">{{ $totalRegions }}</h5>
                        <div class="ms-auto">
                            <i class='bx bx-world fs-3 text-white'></i>
                        </div>
                    </div>
                    <div class="progress my-3 bg-light-transparent" style="height:3px;">
                        <div class="progress-bar bg-white" role="progressbar"
                             style="width: {{ $growthRegions > 100 ? 100 : $growthRegions }}%"
                             aria-valuenow="{{ $growthRegions }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <div class="d-flex align-items-center text-white">
                        <p class="mb-0">TOTAL REGION</p>
                        <p class="mb-0 ms-auto">
                            {{ $growthRegions > 0 ? '+' : '' }}{{ number_format($growthRegions, 1) }}%
                            <span><i class='bx bx-up-arrow-alt'></i></span>
                        </p>
                    </div>
                </div>
            </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ url('/admin/livecity/live-city-listing') }}">
            <div class="card radius-10 bg-gradient-ohhappiness">
                 <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0 text-white">{{ $totalMcitys }}</h5>
                        <div class="ms-auto">
                            <i class='bx bx-world fs-3 text-white'></i>
                        </div>
                    </div>
                    <div class="progress my-3 bg-light-transparent" style="height:3px;">
                        <div class="progress-bar bg-white" role="progressbar"
                             style="width: {{ $growthMcitys > 100 ? 100 : $growthMcitys }}%"
                             aria-valuenow="{{ $growthMcitys }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <div class="d-flex align-items-center text-white">
                        <p class="mb-0">TOTAL CITY</p>
                        <p class="mb-0 ms-auto">
                            {{ $growthMcitys > 0 ? '+' : '' }}{{ number_format($growthMcitys, 1) }}%
                            <span><i class='bx bx-up-arrow-alt'></i></span>
                        </p>
                    </div>
                </div>
            </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ url('/admin/languagescript/languagescript-listing') }}">
            <div class="card radius-10 bg-gradient-deepblue">
                 <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0 text-white">{{ $totalLanguagescripts }}</h5>
                        <div class="ms-auto">
                            <i class='bx bx-menu fs-3 text-white'></i>
                        </div>
                    </div>
                    <div class="progress my-3 bg-light-transparent" style="height:3px;">
                        <div class="progress-bar bg-white" role="progressbar"
                             style="width: {{ $growthLanguagescripts > 100 ? 100 : $growthLanguagescripts }}%"
                             aria-valuenow="{{ $growthLanguagescripts }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <div class="d-flex align-items-center text-white">
                        <p class="mb-0">TOTAL LANGUAGE SCRIPT</p>
                        <p class="mb-0 ms-auto">
                            {{ $growthLanguagescripts > 0 ? '+' : '' }}{{ number_format($growthLanguagescripts, 1) }}%
                            <span><i class='bx bx-up-arrow-alt'></i></span>
                        </p>
                    </div>
                </div>
            </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ url('/admin/maker/maker-listing') }}">
            <div class="card radius-10 bg-gradient-orange">
                 <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0 text-white">{{ $totalChitti }}</h5>
                        <div class="ms-auto">
                            <i class='bx bx-envelope fs-3 text-white'></i>
                        </div>
                    </div>
                    <div class="progress my-3 bg-light-transparent" style="height:3px;">
                        <div class="progress-bar bg-white" role="progressbar"
                             style="width: {{ $growthChitti > 100 ? 100 : $growthChitti }}%"
                             aria-valuenow="{{ $growthChitti }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <div class="d-flex align-items-center text-white">
                        <p class="mb-0">TOTAL LETTER</p>
                        <p class="mb-0 ms-auto">
                            {{ $growthChitti > 0 ? '+' : '' }}{{ number_format($growthChitti, 1) }}%
                            <span><i class='bx bx-up-arrow-alt'></i></span>
                        </p>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>
    <!--end row-->
</div>
@endsection
