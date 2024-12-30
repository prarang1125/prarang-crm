@extends('layouts.admin.admin')
@section('title', 'Send to Checker')

@section('content')
<!--start page wrapper -->
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Admin</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    {{-- <li class="breadcrumb-item"><a href="{{ url('admin/postanalyticsmaker/post-analytics-maker-listing')}}"><i class="bx bx-user"></i></a>
                    </li> --}}
                    <li class="breadcrumb-item active" aria-current="page">Send to Maker</li>
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
                <h6 class="mb-0 text-uppercase text-primary">Send to Checker</h6>
                <hr/>

                <form  action="{{ route('accounts.acc-chitti-uploader-sendtouploader', ['id' => $chitti->chittiId]) }}?uploaderId={{ $chitti->uploaderId }}&City={{ $chitti->areaId }}&sendtouploader=sendtouploader" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row mt-1">
                        <div class="col-md-6">
                            <label for="accaccreturnChittiToCheckerWithRegion" class="form-label">What's wrong?</label>
                            <textarea
                                class="form-control @error('accreturnChittiToCheckerWithRegion') is-invalid @enderror"
                                id="accreturnChittiToCheckerWithRegion"
                                name="accreturnChittiToCheckerWithRegion"
                            >{{ old('accreturnChittiToCheckerWithRegion') }}</textarea>
                            @error('accreturnChittiToCheckerWithRegion')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer mt-3">
                        <button type="submit" class="btn btn-primary">Send to Checker</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->
@endsection

