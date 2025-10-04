@extends('layouts.admin.admin')
@section('title', 'City Edit')

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
                    <li class="breadcrumb-item active" aria-current="page">City Edit</li>
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
                <h6 class="mb-0 text-uppercase text-primary">Edit City</h6>
                <hr/>
                <form  action="{{ route('admin.scities-update' , $s_city->cityId) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <label for="citynameInEnglish" class="form-label">City Name In English</label>
                            <input type="text" class="form-control  @error('citynameInEnglish') is-invalid @enderror" id="citynameInEnglish" name="citynameInEnglish" value="{{ old('citynameInEnglish' ,$s_city->citynameInEnglish) }} " >
                            @error('citynameInEnglish')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="citynameInUnicode" class="form-label">City Name In Hindi</label>
                            <input type="text" class="form-control  @error('citynameInUnicode') is-invalid @enderror" id="citynameInUnicode" name="citynameInUnicode" value="{{ old('citynameInUnicode' , $s_city->citynameInUnicode) }}" >
                            @error('citynameInUnicode')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                            @error('image')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                            @if ($s_city->image)
                                <div class="mt-2">
                                    <p>Current Image:</p>
                                    <!-- Display the image -->
                                    <img src="{{ Storage::url($s_city->image) }}" alt="City Image" width="100">
                                    <!-- Display the image name -->
                                    {{-- <p>Image Name: {{ Storage::url($s_city->Image_Name) }}</p> --}}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <label for="content" class="form-label mt-3">Description</label>
                            <textarea class="@error('content') is-invalid @enderror" name="content" id="editor">{{ old('content', $s_city->text) }}</textarea>
                            @error('content')
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
<script>
    const uploadUrl = "{{ route('admin.ckeditor-upload') }}";
    const csrfToken = "{{ csrf_token() }}";
</script>
@endsection

