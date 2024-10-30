@extends('layouts.admin.admin')
@section('title', 'User Register')

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
                    <li class="breadcrumb-item active" aria-current="page">User Register</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="card" style="padding-top: 15px;">
            <div class="col-xl-9 mx-auto w-100">
                <!-- Success Message -->
                @if(session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                @endif
                <h6 class="mb-0 text-uppercase text-primary">Create New User</h6>
                <hr/>
                <form  action="{{ url('/admin/users-store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <label for="inputFirstName" class="form-label">First Name</label>
                            <input type="text" class="form-control  @error('firstName') is-invalid @enderror" id="inputFirstName" name="firstName" value="{{ old('firstName') }}" >
                            @error('firstName')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="inputLastName" class="form-label">Last Name</label>
                            <input type="text" class="form-control @error('lastName') is-invalid @enderror" id="inputLastName" name="lastName" value="{{ old('lastName') }}" >
                            @error('lastName')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="inputEmail" class="form-label">Email ID/ User Name</label>
                            <input type="email" class="form-control @error('emailId') is-invalid @enderror" id="inputEmail" name="emailId" value="{{ old('emailId') }}">
                            @error('emailId')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="inputPassword" class="form-label">Password</label>
                            <input type="password" class="form-control @error('empPassword') is-invalid @enderror" id="inputPassword" name="empPassword" >
                            @error('empPassword')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="inputRole" class="form-label">Role</label>
                            <select id="inputRole" class="form-select @error('roleId') is-invalid @enderror" name="roleId">
                                <option selected disabled>Choose...</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->roleID }}" {{ old('roleId') == $role->roleID ? 'selected' : '' }}>{{ $role->roleName }}</option>
                                @endforeach
                            </select>
                            @error('roleId')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="inputLanguageScript" class="form-label">Language Script</label>
                            <select id="inputLanguageScript" class="form-select @error('languageScriptId') is-invalid @enderror" name="languageScriptId" >
                                <option selected disabled>Choose...</option>
                                @foreach($languagescripts as $languagescript)
                                    <option value="{{ $languagescript->id }}" {{ old('languageScriptId') == $languagescript->id ? 'selected' : '' }}>{{ $languagescript->languageUnicode }}</option>
                                @endforeach
                            </select>
                            @error('languageScriptId')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer mt-3">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->
@endsection
