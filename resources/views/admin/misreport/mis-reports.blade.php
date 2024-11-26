@extends('layouts.admin.admin')
@section('title', 'New Mis Report')

@section('content')
<!--start page wrapper -->
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Admin</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    {{-- <li class="breadcrumb-item"><a href="{{ url('admin/maker/maker-listing')}}"><i class="bx bx-user"></i></a>
                    </li> --}}
                    <li class="breadcrumb-item active" aria-current="page">Mis Report</li>
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
                <h6 class="mb-0 text-uppercase text-primary">Create New Mis Report</h6>
                <hr/>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label class="mb-0 text-primary">Use date filters and download excel file of the MIS Report.</label>
                    </div>
                </div>
                <form  action="" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="inputGeography" class="form-label">Name of User-City/ Geography</label>
                            <select id="inputGeography" class="form-select @error('geography') is-invalid @enderror" name="geography">
                                <option selected disabled>All</option>
                                {{-- @foreach($geographyOptions as $geographyOption)
                                    <option value="{{ $geographyOption->id }}">{{ $geographyOption->labelInEnglish }}</option>
                                @endforeach --}}
                            </select>
                            @error('geography')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="startDate" class="form-label">Start Date</label>
                            <input type="date" id="startDate" name="start_date" class="form-control" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="endDate" class="form-label">End Date</label>
                            <input type="date" id="endDate" name="end_date" class="form-control" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="modal-footer mt-3">
                        <button type="submit" class="btn btn-primary">Generate MIS Report</button>
                        <a href="" class="btn btn-primary">reset</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->
@endsection


