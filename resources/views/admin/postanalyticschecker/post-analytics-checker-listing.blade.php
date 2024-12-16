@extends('layouts.admin.admin')
@section('title', 'Post Analytics Checker Listing')

@section('content')
<!--start page wrapper -->
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Admin</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/postanalyticschecker/post-analytics-checker-city-listing')}}"><i class="bx bx-user"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Post Analytics Checker Listing</li>
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
            <h6 class="mb-0 text-uppercase">Post Analytics Checker Listing</h6>
            <hr/>
            <div class="card">
                <div class="card-body">
                    <table class="table mb-0 table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="">Post Number</th>
                                <th scope="col" class="">Title</th>
                                <th scope="col" class="">Name of Analytics Maker</th>
                                <th scope="col" class="">Upload Date</th>
                                <th scope="col" class="">No. of days from Upload</th>
                                <th scope="col" class="">Area</th>
                                <th scope="col" class="">Ad</th>
                                <th scope="col" class="">Total Viewership</th>
                                <th scope="col" class="">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $index = 1;  @endphp
                            @foreach ($chittis as $chitti)
                                <tr>
                                    <td class="">{{ $index }}</td>
                                    <td class="">
                                        <a class="text-primary">{{$chitti->Title}}
                                        </a>
                                    </td>
                                    <td class="">{{ $muserMaker ?? 'N/A' }} </td>
                                    <td class="">{{ \Carbon\Carbon::parse($chitti->created_at)->format('Y-m-d') }}</td>
                                    <td class="">{{ (int) \Carbon\Carbon::parse($chitti->created_at)->diffInDays(now()) }}</td>
                                    <td class="">{{ $chitti->city->cityNameInEnglish ?? 'N/A' }} </td>
                                    <td class="">{{ 'N/A' }} </td>
                                    <td class="">{{ $chitti->totalViewerCount }}</td>

                                    @if ($chitti->postStatusMakerChecker == 'approved')
                                        <td class="">
                                            <a href="{{ route('admin.post-analytics-checker-edit', ['id' => $chitti->chittiId, 'city' => $chitti->city->cityCode ?? 'N/A']) }}" class="text-primary">
                                                {{ 'Approved' }}
                                            </a>
                                            {{-- <span class="custom-approved">{{ 'Approved' }}</span> --}}
                                        </td>
                                    @else
                                    <td class="">
                                        <a href="{{ route('admin.post-analytics-checker-edit', ['id' => $chitti->chittiId, 'city' => $chitti->city->cityCode ?? 'N/A']) }}" class="text-primary">
                                            {{ 'Review' }}
                                        </a>
                                    </td>
                                    @endif
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
