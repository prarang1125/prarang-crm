@extends('layouts.admin.admin')
@section('title', 'Tag Category Listing')

@section('content')
<!--start page wrapper -->
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Admin</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/tagcategory/tag-category-listing')}}"><i class="bx bx-user"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Tag Category Listing</li>
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
            <h6 class="mb-0 text-uppercase">Tag Category Listing</h6>
            <hr/>
            <div class="card">
                <div class="card-body d-flex justify-content-end align-items-end">
                    <!-- Search Form -->
                    <form action="{{ url('admin/tagcategory/tag-category-listing') }}" method="GET" class="d-flex me-3">
                        <input type="text" name="search" class="form-control me-2" placeholder="Search by Tag Name" value="{{ request()->input('search') }}">
                        <button type="submit" class="btn btn-secondary">Search</button>
                    </form>

                    @if(request()->has('search'))
                        <a class="btn btn-primary me-1" href="{{ url()->current() }}">
                            <i class="bx bx-refresh"></i>
                        </a>
                    @endif

                    <a href="{{ url('/admin/tagcategory/tag-category-register') }}" class="btn btn-primary">Add New Tag Category</a>
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
                                $index = ($mtagcategorys->currentPage() - 1) * $mtagcategorys->perPage() + 1;
                            @endphp
                            @foreach ($mtagcategorys as $mtagcategory)
                                <tr>
                                    <th scope="row" class="">{{ $index }}</th>
                                    <td class="">{{ $mtagcategory->tagCategoryInEnglish }}</td>
                                    <td class="">{{ $mtagcategory->tagCategoryInUnicode }}</td>

                                    <td class="">
                                        <a href="{{ route('admin.tag-category-edit', $mtagcategory->tagCategoryId) }}" class="btn btn-sm btn-primary edit-user">Edit</a>

                                        <form action="{{ route('admin.tag-category-delete', $mtagcategory->tagCategoryId) }}" method="POST" style="display:inline;">
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
                        {{ $mtagcategorys->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->
@endsection





