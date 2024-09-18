@extends('layouts.admin.admin')
@section('title', 'New Language Script Register')

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
                    <li class="breadcrumb-item active" aria-current="page">Language Script Register</li>
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
                <h6 class="mb-0 text-uppercase text-primary">Create New Language Script</h6>
                <hr/>
                <form  action="{{ url('/admin/languagescript/languagescript-store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <label for="language" class="form-label">Language Name</label>
                            <input type="text" class="form-control  @error('language') is-invalid @enderror" id="language" name="language" value="{{ old('language') }}" >
                            @error('language')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="languageInUnicode" class="form-label">Language Name Unicode</label>
                            <input type="text" class="form-control  @error('languageInUnicode') is-invalid @enderror" id="languageInUnicode" name="languageInUnicode" value="{{ old('languageInUnicode') }}" >
                            @error('languageInUnicode')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="languageUnicode" class="form-label">Language Name In Unicode</label>
                            <input type="text" class="form-control  @error('languageUnicode') is-invalid @enderror" id="languageUnicode" name="languageUnicode" value="{{ old('languageUnicode') }}" >
                            @error('languageUnicode')
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

