@extends('layouts.admin.admin')
@section('title', 'City Listing')

@section('content')
<!--start page wrapper -->
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Admin</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/scities/scities-listing')}}"><i class="bx bx-user"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">City Listing</li>
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
            <h6 class="mb-0 text-uppercase">City Listing</h6>
            <hr/>
            <div class="card">
                <div class="card-body d-flex justify-content-end align-items-end">
                    <!-- Search Form -->
                    <form action="{{ url('admin/scities/scities-listing') }}" method="GET" class="d-flex me-3">
                        <input type="text" name="search" class="form-control me-2" placeholder="Search by City Name" value="{{ request()->input('search') }}">
                        <button type="submit" class="btn btn-secondary">Search</button>
                    </form>
                    <a href="{{ url('/admin/scities/scities-register') }}" class="btn btn-primary">Add City</a>
                </div>
                <div class="card-body">
                    <table class="table mb-0 table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="">#</th>
                                <th scope="col" class="">City Name In English</th>
                                <th scope="col" class="">City Name In Hindi</th>
                                <th scope="col" class="">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $index = ($scities->currentPage() - 1) * $scities->perPage() + 1;
                            @endphp
                            @foreach ($scities as $scity)
                                <tr>
                                    <th scope="row" class="">{{ $index }}</th>
                                    <td class="">{{ $scity->citynameInEnglish }}</td>
                                    <td class="">{{ $scity->citynameInUnicode }}</td>

                                    <td class="">
                                        <a href="{{ route('admin.scities-edit', $scity->cityId) }}" class="btn btn-sm btn-primary edit-user scities-ml-negative-7">Edit</a>

                                        <form action="{{ route('admin.scities-delete', $scity->cityId) }}" method="POST" style="display:inline;">
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
                        {{ $scities->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->
@endsection





