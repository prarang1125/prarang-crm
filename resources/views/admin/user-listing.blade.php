@extends('layouts.admin.admin')
@section('title', 'User Listing')

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
                    <li class="breadcrumb-item"><a href="{{ url('admin/user-listing')}}"><i class="bx bx-user"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">User Listing</li>
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
                     <!-- Search Form -->
                     <form action="{{ url('admin/user-listing') }}" method="GET" class="d-flex me-3">
                        <input type="text" name="search" class="form-control me-2" placeholder="Search by User Name" value="{{ request()->input('search') }}">
                        <button type="submit" class="btn btn-secondary">Search</button>
                    </form>

                    @if(request()->has('search'))
                        <a class="btn btn-primary me-1" href="{{ url()->current() }}">
                            <i class="bx bx-refresh"></i>
                        </a>
                    @endif

                    <a href="{{ url('/admin/user-register') }}" class="btn btn-primary">Add New User</a>
                </div>
                <div class="card-body">
                    <table class="table mb-0 table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">First Name</th>
                                <th scope="col">Last Name</th>
                                <th scope="col">Email ID</th>
                                <th scope="col">Role</th>
                                <th scope="col">Language</th>
                                <th scope="col">Stutus</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $index = ($users->currentPage() - 1) * $users->perPage() + 1;
                            @endphp
                            @foreach($users as $user)
                                <tr>
                                    <th scope="row">{{ $index }}</th>
                                    <td>{{ $user->firstName }}</td>
                                    <td>{{ $user->lastName }}</td>
                                    <td>{{ $user->emailId }}</td>
                                    <td>{{ $user->role ? $user->role->roleName : 'N/A' }}</td>
                                    <td class="{{ $user->languageId? 'text-success':'text-success' }}">{{ $user->languageId? 'English':'Hindi' }} </td>

                                    <td class="{{ $user->isActive? 'text-success':'text-danger' }}">{{ $user->isActive? 'Active':'Deactive' }}
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('admin.user-edit', $user->userId) }}" class="btn btn-sm btn-primary edit-user">Edit</a>

                                        <form action="{{ route('admin.users-delete', $user->userId) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger delete-user">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @php $index++; @endphp
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end mt-4">
                        {{ $users->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->
@endsection




