@extends('layouts.admin.admin')
@section('title', 'Tag Edit')
@section('content')
<!--start page wrapper -->
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Admin</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/tag/tag-listing')}}"><i class="bx bx-user"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Tag Edit</li>
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
                <h6 class="mb-0 text-uppercase">Tag Edit</h6>
                <hr/>
                <form  action="{{ route('admin.tag-update', $mtags->tagId) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <label for="tagInEnglish" class="form-label">First Name</label>
                            <input type="text" class="form-control  @error('tagInEnglish') is-invalid @enderror" id="tagInEnglish" name="tagInEnglish" value="{{ old('tagInEnglish', $mtags->tagInEnglish) }}" >
                            @error('tagInEnglish')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="tagInUnicode" class="form-label">Last Name</label>
                            <input type="text" class="form-control @error('tagInUnicode') is-invalid @enderror" id="tagInUnicode" name="tagInUnicode" value="{{ old('tagInUnicode', $mtags->tagInUnicode) }}" >
                            @error('tagInUnicode')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="tagCategoryId" class="form-label">Role</label>
                            <select id="tagCategoryId" class="form-select @error('tagCategoryId') is-invalid @enderror" name="tagCategoryId">
                                <option selected disabled>Choose...</option>
                                @foreach($mtagcategorys as $mtagcategory)
                                    <option value="{{ $mtagcategory->tagCategoryId }}" {{ $mtagcategory->tagCategoryId == $mtags->tagCategoryId ? 'selected' : '' }}>
                                        {{ $mtagcategory->tagCategoryInEnglish }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tagCategoryId')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
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
