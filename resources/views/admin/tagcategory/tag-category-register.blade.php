@extends('layouts.admin.admin')
@section('title', 'New Tag Category Register')

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
                    <li class="breadcrumb-item active" aria-current="page">Tag Category Register</li>
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
                <h6 class="mb-0 text-uppercase text-primary">Create New Tag Category Register</h6>
                <hr/>
                <form  action="{{ url('/admin/tagcategory/tag-category-store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <label for="tagCategoryInEnglish" class="form-label">Tag Category In English</label>
                            <input type="text" class="form-control  @error('tagCategoryInEnglish') is-invalid @enderror" id="tagCategoryInEnglish" name="tagCategoryInEnglish" value="{{ old('tagCategoryInEnglish') }}" >
                            @error('tagCategoryInEnglish')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="tagCategoryInUnicode" class="form-label">Tag Category In Unicode</label>
                            <input type="text" class="form-control  @error('tagCategoryInUnicode') is-invalid @enderror" id="tagCategoryInUnicode" name="tagCategoryInUnicode" value="{{ old('tagCategoryInUnicode') }}" >
                            @error('tagCategoryInUnicode')
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

