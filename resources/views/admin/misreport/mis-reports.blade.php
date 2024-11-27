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
                <form  action="{{ route('admin.mis-report-generate') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="inputGeography" class="form-label">Name of User-City/ Geography</label>
                            <select id="inputGeography" class="form-select @error('geography') is-invalid @enderror" name="geography">
                                <option selected>All</option>
                                @foreach($misreports as $misreport)
                                    <option value="{{ $misreport->Id }}">
                                        {{ $misreport->userCity->cityNameInEnglish ?? 'N/A' }}
                                    </option>
                                @endforeach
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
                        <a href="" class="btn btn-primary">Reset</a>
                    </div>
                </form>
                <form  action="" method="POST" enctype="multipart/form-data">
                    <div class="table-responsive mt-4">
                        <table class="table mb-0 table-hover mt=4">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="">UserName</th>
                                    <th scope="col" class="">MobileNumber</th>
                                    <th scope="col" class="">EmailId</th>
                                    <th scope="col" class="">DateOfJoining</th>
                                    <th scope="col" class="">AppDownloadDate</th>
                                    <th scope="col" class="">TotalColorFeeds</th>
                                    <th scope="col" class="">AppUsageTime</th>
                                    <th scope="col" class="">FeedsComments</th>
                                    <th scope="col" class="">FeedsLikes</th>
                                    <th scope="col" class="">FeedShares</th>
                                    <th scope="col" class="">SavedBank</th>
                                    <th scope="col" class="">LucknowColorFeeds</th>
                                    <th scope="col" class="">MeerutColorFeeds</th>
                                    <th scope="col" class="">RampurColorFeeds</th>
                                    <th scope="col" class="">JaunpurColorFeeds</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="">John Doe</td>
                                    <td class="">1234567890</td>
                                    <td class="">john.doe@example.com</td>
                                    <td class="">2023-01-15</td>
                                    <td class="">2023-02-01</td>
                                    <td class="">50</td>
                                    <td class="">120 minutes</td>
                                    <td class="">25</td>
                                    <td class="">100</td>
                                    <td class="">30</td>
                                    <td class="">Yes</td>
                                    <td class="">20</td>
                                    <td class="">15</td>
                                    <td class="">10</td>
                                    <td class="">5</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer mt-3">
                        <button type="submit" class="btn btn-primary">Download MIS Report</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->
@endsection


