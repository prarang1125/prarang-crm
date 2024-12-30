@extends('layouts.admin.admin')
@section('title', 'Post Analytics Maker Listing')

@section('content')
<!--start page wrapper -->
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Admin</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/postanalyticsmaker/post-analytics-maker-city-listing')}}">
                        {{-- <i class="bx bx-user"></i></a> --}}
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Post Analytics Maker Listing</li>
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
            <h6 class="mb-0 text-uppercase">Post Analytics Maker Listing</h6>
            <hr/>
            <div class="card">
                <div class="card-body d-flex justify-content-end align-items-end">
                    <!-- Search Form -->
                    <form action="{{ url('admin/postanalyticsmaker/post-analytics-maker-listing') }}" method="GET" class="d-flex me-3">
                        <input type="hidden" name="cityCode" value="{{ request()->query('cityCode') }}">
                        <input type="text" name="search" class="form-control me-2" placeholder="Search by Title" value="{{ request()->query('search') }}" autocomplete="off">
                        <button type="submit" class="btn btn-secondary">Search</button>
                    </form>

                    @if(request()->has('search'))
                        <a class="btn btn-primary me-1" href="{{ url()->current() }}?cityCode={{ request()->query('cityCode') }}">
                            <i class="bx bx-refresh"></i> Clear
                        </a>
                    @endif
                </div>

                <div class="card-body">
                    <table class="table mb-0 table-hover">
                        <thead class="thead-light">
                            <tr>
                                {{-- <th scope="col" class="">#</th> --}}
                                <th scope="col" class="">Post Number</th>
                                <th scope="col" class="">Title</th>
                                <th scope="col" class="">Upload Date</th>
                                <th scope="col" class="">No. of days from Upload</th>
                                <th scope="col" class="">Area</th>
                                <th scope="col" class="">Ad</th>
                                <th scope="col" class="">Total Viewership</th>
                                <th scope="col" class="">Status</th>
                                <th scope="col" class="">Sent Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $index = ($chittis->currentPage() - 1) * $chittis->perPage() + 1;
                            @endphp
                            @foreach ($chittis as $chitti)
                                <tr>
                                    {{-- <th scope="row" class="text-center">{{ $index }}</th> --}}
                                    <td class="">{{ $index }}</td>
                                    <td class="">
                                        <a href="{{ route('admin.post-analytics-maker-create', ['id' => $chitti->chittiId, 'city' => $chitti->cityCode ?? 'N/A']) }}" class="text-primary">
                                            {{ $chitti->Title }}
                                        </a>
                                    </td>
                                    <td class="">{{ $chitti->createDate }}</td>
                                    <td class="">{{ (int) \Carbon\Carbon::parse($chitti->createDate)->diffInDays(now()) }}</td>
                                    <td class="">{{ $chitti->citynameInEnglish ?? 'N/A' }} </td>
                                    <td class="">{{ 'Ad' }}</td>
                                    <td class="">{{ $chitti->totalViewerCount }}</td>
                                    <td class="">{{ $chitti->finalStatus}}</td>
                                    <td class="">{{ $chitti->postViewershipDateTo ?? '' }}</td>
                                </tr>
                                @php $index++;  @endphp
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end mt-4">
                        {{ $chittis->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->
@endsection
