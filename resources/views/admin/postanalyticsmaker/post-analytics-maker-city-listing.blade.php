@extends('layouts.admin.admin')
@section('title', 'Live Maker City Listing')

@section('content')
<!--start page wrapper -->
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Admin</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/postanalyticsmaker/post-analytics-maker-city-listing')}}"><i class="bx bx-user"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Live Maker City Listing</li>
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
            <h6 class="mb-0 text-uppercase">Live Maker City Listing</h6>
            <hr/>
            <div class="card">
                <div class="card-body">
                    <table class="table mb-0 table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="text-center">#</th>
                                <th scope="col" class="text-center">City Name In English</th>
                                <th scope="col" class="text-center">City Name In Hindi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $index = 1;  @endphp
                            @foreach ($mcitys as $mcity)
                                <tr>
                                    <th scope="row" class="text-center">{{ $index }}</th>
                                    <td class="text-center">
                                        <a href="{{ route('admin.post-analytics-maker-listing', ['cityCode' => $mcity->cityCode]) }}" class="text-primary">
                                            {{ $mcity->cityNameInEnglish }}
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.post-analytics-maker-listing', ['cityCode' => $mcity->cityCode]) }}" class="text-primary">
                                            {{ $mcity->cityNameInUnicode }}
                                        </a>
                                    </td>
                                </tr>
                                @php $index++;  @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->
@endsection
