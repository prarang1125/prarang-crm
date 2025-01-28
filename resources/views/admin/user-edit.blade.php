@extends('layouts.admin.admin')
@section('title', 'User Edit')
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
                    <li class="breadcrumb-item active" aria-current="page">User Edit</li>
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
                <h6 class="mb-0 text-uppercase">User Edit</h6>
                <hr/>
                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <form  action="{{ route('admin.user-update', $user->userId) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <label for="inputFirstName" class="form-label">First Name</label>
                            <input type="text" class="form-control  @error('firstName') is-invalid @enderror" id="inputFirstName" name="firstName" value="{{ old('firstName', $user->firstName) }}" >
                            @error('firstName')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="inputLastName" class="form-label">Last Name</label>
                            <input type="text" class="form-control @error('lastName') is-invalid @enderror" id="inputLastName" name="lastName" value="{{ old('lastName', $user->lastName) }}" >
                            @error('lastName')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">EmailID</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->emailId) }}" >
                        @error('email')
                            <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="inputRole" class="form-label">Role</label>
                            <select id="inputRole" class="form-select @error('roleId') is-invalid @enderror" name="roleId">
                                <option selected disabled>Choose...</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->roleID }}" {{ $role->roleID == $user->roleId ? 'selected' : '' }}>
                                        {{ $role->roleName }}
                                    </option>
                                @endforeach
                            </select>
                            @error('roleId')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- TODO::need TO Improve this code  -->
                        <div class="col-md-6">
                            <label for="languageId" class="form-label">Language Script</label>
                            <select id="languageId" class="form-select @error('languageId') is-invalid @enderror" name="languageId">
                                <option selected disabled>Choose...</option>
                                @foreach ($languagescripts as $languagescript)
                                    <option value="{{ $languagescript->id }}"
                                        {{ old('languageId', $user->languageId) == $languagescript->id ? 'selected' : '' }}>
                                        {{ $languagescript->language }}
                                    </option>
                                @endforeach
                            </select>
                            @error('languageId')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="isActive" class="form-label">Status</label>
                            <select id="isActive" class="form-select @error('isActive') is-invalid @enderror" name="isActive">
                                <option disabled>Choose...</option>
                                <option value="1" {{ old('isActive', $user->isActive) == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('isActive', $user->isActive) == '0' ? 'selected' : '' }}>Deactive</option>
                            </select>
                            @error('isActive')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Password</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input type="password" name="password" class="form-control" value="" />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Confirm Password</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input type="password" name="password_confirmation" class="form-control" value="" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer mt-3">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->
@endsection
