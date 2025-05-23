@extends('layouts.admin.admin')
@section('title', 'Live Checker City Listing')

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
                            <a
                                href="{{ url('admin/postanalyticschecker/post-analytics-checker-city-listing') }}">
                                {{-- <i class="bx bx-user"></i> --}}
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Live Checker City Listing</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-xl-9 mx-auto w-100">
                <!-- Success Message -->
                @if (session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                @endif
                <h6 class="mb-0 text-uppercase">Live Checker City Listing</h6>
                <hr />
                <div class="card">
                    <div class="card-body d-flex justify-content-end align-items-end">
                        <!-- Search Form -->
                        <form action="{{ url('admin/postanalyticschecker/post-analytics-checker-city-listing') }}"
                            method="GET" class="d-flex me-3">
                            <input type="text" name="search" class="form-control me-2" placeholder="Search by Post Name"
                                value="{{ request()->input('search') }}">
                            <button type="submit" class="btn btn-secondary">Search</button>
                        </form>
                        @if (request()->has('search'))
                            <a class="btn btn-primary me-1" href="{{ url()->current() }}">
                                <i class="bx bx-refresh"></i>
                            </a>
                        @endif
                    </div>
                    <div class="card-body">
                        <table class="table mb-0 table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="text-center">#</th>
                                    <th scope="col" class="text-center">City Name In English</th>
                                    {{-- <th scope="col" class="text-center">City Name In Hindi</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $index = ($mcitys->currentPage() - 1) * $mcitys->perPage() + 1;
                                @endphp
                                @foreach ($mcitys as $mcity)
                                    <tr>
                                        <th scope="row" class="text-center">{{ $index }}</th>
                                        <td class="text-center">
                                            <a href="{{ route('admin.post-analytics-checker-listing', ['cityCode' => $mcity->geographycode]) }}"
                                                class="text-primary">
                                                {{ $mcity->geography }}
                                            </a>
                                        </td>
                                        {{-- <td class="text-center">
                                            <a href="{{ route('admin.post-analytics-checker-listing', ['cityCode' => $mcity->cityCode]) }}"
                                                class="text-primary">
                                                {{ $mcity->citynameInUnicode }}
                                            </a>
                                        </td> --}}
                                    </tr>
                                    @php $index++;  @endphp
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end mt-4">
                            {{ $mcitys->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end page wrapper -->
@endsection
