@extends('layouts.admin.admin')
@section('title', 'Tag Register')

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
                    <li class="breadcrumb-item active" aria-current="page">Tag Register</li>
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
                <h6 class="mb-0 text-uppercase text-primary">Create New Tag</h6>
                <hr/>
                <form  action="{{ url('/admin/tag/tag-store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <label for="tagInEnglish" class="form-label">Tag In English</label>
                            <input type="text" class="form-control  @error('tagInEnglish') is-invalid @enderror" id="tagInEnglish" name="tagInEnglish" value="{{ old('tagInEnglish') }}" >
                            @error('tagInEnglish')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="tagInUnicode" class="form-label">Tag In Unicode</label>
                            <input type="text" class="form-control @error('tagInUnicode') is-invalid @enderror" id="tagInUnicode" name="tagInUnicode" value="{{ old('tagInUnicode') }}" >
                            @error('tagInUnicode')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="tagCategoryId" class="form-label">Tag Category</label>
                            <select id="tagCategoryId" class="form-select @error('tagCategoryId') is-invalid @enderror" name="tagCategoryId">
                                <option selected disabled>Choose...</option>
                                @foreach($mtagcategorys as $mtagcategory)
                                    <option value="{{ $mtagcategory->tagCategoryId }}" {{ old('tagCategoryId') ==  $mtagcategory->tagCategoryId  ? 'selected' : '' }}>{{ $mtagcategory->tagCategoryInEnglish }}</option>
                                @endforeach
                            </select>
                            @error('tagCategoryId')
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
