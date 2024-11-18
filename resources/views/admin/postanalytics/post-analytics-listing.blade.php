@extends('layouts.admin.admin')
@section('title', 'Post Analytics Listing')

@section('content')
<!--start page wrapper -->
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Admin</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/postanalytics/post-analytics-listing')}}"><i class="bx bx-user"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Post Analytics Listing</li>
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
            <h6 class="mb-0 text-uppercase">Post Analytics Listing</h6>
            <hr/>
            <div class="card">
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label for="inputGeography" class="form-label">Geography</label>
                            <select id="inputGeography" class="form-select @error('geography') is-invalid @enderror" name="geography">
                                <option selected disabled>Select</option>
                                {{-- @foreach($geographyOptions as $geographyOption)
                                    <option value="{{ $geographyOption->id }}">{{ $geographyOption->labelInEnglish }}</option>
                                @endforeach --}}
                            </select>
                            @error('geography')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label id="inputLanguageLabel" for="inputLanguageScript" class="form-label">Select</label>
                            <select id="inputLanguageScript" class="form-select @error('c2rselect') is-invalid @enderror" name="c2rselect">
                                <option selected disabled>Select</option>
                            </select>
                            @error('c2rselect')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-3" style="margin-right: 60px;">
                            <label for="to" class="form-label">Date</label>
                            <input type="date" class="form-control @error('to') is-invalid @enderror" id="to" name="to" value="{{ old('to', date('Y-m-d')) }}">
                            @error('to')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary" style="margin-top: 28px;">
                                Search
                            </button>
                        </div>

                        <div class="col-md-1 mr-2">
                            <a href="#" class="btn btn-success" style="margin-top: 28px"><i class="lni lni-files"></i></a>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-2">
                            <a class="dt-button buttons-excel buttons-html5 btn btn-success" tabindex="0" aria-controls="datatable-default" href="#"><span>Excel</span></a>
                        </div>
                        <div class="col-md-6"></div>
                        <div class="col-md-4">
                            <label for="search" class="form-label">serach: </label>
                            <input type="search" class="" placeholder="" aria-controls="datatable-default">
                        </div>
                    </div>

                    <table class="table mb-0 table-hover mt=4">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="">Date</th>
                                <th scope="col" class="">Geography</th>
                                <th scope="col" class="">Area</th>
                                <th scope="col" class="">Maker</th>
                                <th scope="col" class="">Checker</th>
                                <th scope="col" class="">Uploader</th>
                                <th scope="col" class="">Comments</th>
                                <th scope="col" class="">Likes</th>
                                <th scope="col" class="">App Visits</th>
                                <th scope="col" class="">Sub Title</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->
@endsection
