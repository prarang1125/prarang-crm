@extends('layouts.admin.admin')
@section('title', 'Live City Listing')

@section('content')
<!--start page wrapper -->
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Admin</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/livecity/live-city-listing')}}"><i class="bx bx-user"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Live City Listing</li>
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
            <h6 class="mb-0 text-uppercase">Live City Listing</h6>
            <hr/>
            <div class="card">
                <div class="card-body d-flex justify-content-end align-items-end">
                    <!-- Search Form -->
                    <form action="{{ url('admin/livecity/live-city-listing') }}" method="GET" class="d-flex me-3">
                        <input type="text" name="search" class="form-control me-2" placeholder="Search by Live City Name" value="{{ request()->input('search') }}">
                        <button type="submit" class="btn btn-secondary">Search</button>
                    </form>
                    <a href="{{ url('/admin/livecity/live-city-register') }}" class="btn btn-primary">Add New Live City</a>
                </div>
                <div class="card-body">
                    <table class="table mb-0 table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="text-center">#</th>
                                <th scope="col" class="text-center">City Name In English</th>
                                <th scope="col" class="text-center">City Name In Hindi</th>
                                <th scope="col" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $index = ($mcitys->currentPage() - 1) * $mcitys->perPage() + 1;
                            @endphp
                            @foreach ($mcitys as $mcity)
                                <tr>
                                    <th scope="row" class="text-center">{{ $index }}</th>
                                    <td class="text-center">{{ $mcity->cityNameInEnglish }}</td>
                                    <td class="text-center">{{ $mcity->cityNameInUnicode }}</td>

                                    <td class="text-center">
                                        <a href="{{ route('admin.live-city-edit', $mcity->cityId) }}" class="btn btn-sm btn-primary edit-user">Edit</a>

                                        <form action="{{ route('admin.live-city-delete', $mcity->cityId) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger delete-user">Delete</button>
                                        </form>
                                    </td>
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





