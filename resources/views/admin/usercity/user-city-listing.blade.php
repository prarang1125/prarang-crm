@extends('layouts.admin.admin')
@section('title', 'User City Listing')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@section('content')
<!--start page wrapper -->
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Admin</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/usercity/user-city-listing')}}"><i class="bx bx-user"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">User City Listing</li>
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
            <h6 class="mb-0 text-uppercase">User City Listing</h6>
            <hr/>
            <div class="card">
                <div class="card-body d-flex justify-content-end align-items-end">
                    <!-- Search Form -->
                    <form action="{{ url('admin/usercity/user-city-listing') }}" method="GET" class="d-flex me-3">
                        <input type="text" name="search" class="form-control me-2" placeholder="Search by City Name" value="{{ request()->input('search') }}">
                        <button type="submit" class="btn btn-secondary">Search</button>
                    </form>

                    <a href="{{ url('/admin/usercity/user-city-register') }}" class="btn btn-primary">Add New User City</a>
                </div>
                <div class="card-body">
                    <table class="table mb-0 table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">City Name In English</th>
                                <th scope="col">City Name In Hindi</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $index = ($usercitys->currentPage() - 1) * $usercitys->perPage() + 1;
                            @endphp
                            @foreach($usercitys as $usercity)
                                <tr>
                                    <th scope="row">{{ $index }}</th>
                                    <td>{{ $usercity->cityNameInEnglish }}</td>
                                    <td>{{ $usercity->cityNameInHindi }}</td>
                                    <td class="text-right">
                                        <a href="{{ route('admin.user-city-edit', $usercity->cityId) }}" class="btn btn-sm btn-primary edit-user">Edit</a>

                                        <form action="{{ route('admin.user-city-delete', $usercity->cityId) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger delete-user">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @php $index++; @endphp
                            @endforeach
                        </tbody>
                    </table>
                    {{--<nav aria-label="Page navigation example">
                        <ul class="pagination round-pagination justify-content-end mt-2">
                            {{ $usercitys->links('pagination::bootstrap-5') }} <!-- This will generate the pagination links -->
                        </ul>
                    </nav> --}}
                    <div class="d-flex justify-content-end mt-4">
                        {{ $usercitys->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->
@endsection




