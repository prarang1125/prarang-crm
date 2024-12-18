@extends('layouts.admin.admin')
@section('title', 'Language Script Listing')

@section('content')
<!--start page wrapper -->
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Admin</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/languagescript/languagescript-listing')}}"><i class="bx bx-user"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Language Script</li>
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
            <h6 class="mb-0 text-uppercase">User Listing</h6>
            <hr/>
            <div class="card">
                <div class="card-body d-flex justify-content-end align-items-end">
                    <form action="{{ url('admin/languagescript/languagescript-listing') }}" method="GET" class="d-flex me-3">
                        <input type="text" name="search" class="form-control me-2" placeholder="Search by Language Script Name" value="{{ request()->input('search') }}">
                        <button type="submit" class="btn btn-secondary">Search</button>
                    </form>
                    @if(request()->has('search'))
                        <a class="btn btn-primary me-1" href="{{ url()->current() }}">
                            <i class="bx bx-refresh"></i>
                        </a>
                    @endif
                    <a href="{{ url('admin/languagescript/languagescript-register') }}" class="btn btn-primary">Add New Language Script</a>
                </div>
                <div class="card-body">
                    <table class="table mb-0 table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="text-center">#</th>
                                <th scope="col" class="">Language Name</th>
                                <th scope="col" class="">Language Name Unicode</th>
                                <th scope="col" class="">Language Name In Unicode</th>
                                <th scope="col" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $index = ($languagescripts->currentPage() - 1) * $languagescripts->perPage() + 1;
                            @endphp
                            @foreach ($languagescripts as $languagescript)
                                <tr>
                                    <th scope="row" class="text-center">{{ $index }}</th>
                                    <td class="">{{ $languagescript->language  }}</td>
                                    <td class="">{{ $languagescript->languageInUnicode }}</td>
                                    <td class="">{{ $languagescript->languageUnicode }}</td>

                                    <td class="text-center">
                                        <a href="{{ route('admin.languagescript-edit', $languagescript->id) }}" class="btn btn-sm btn-primary edit-user">Edit</a>

                                        <form action="{{ route('admin.languagescript-delete', $languagescript->id) }}" method="POST" style="display:inline;">
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
                        {{ $languagescripts->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->
@endsection






