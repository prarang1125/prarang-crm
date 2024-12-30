@extends('layouts.admin.admin')
@section('title', 'Post Listing')

@section('content')
    <!--start page wrapper -->
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Admin</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('admin/maker/maker-listing') }}"><i
                                    class="bx bx-user"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Post Listing</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-xl-9 mx-auto w-100">
                <!-- Success Message -->
                @if (session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                @endif
                <h6 class="mb-0 text-uppercase">Post Listing</h6>
                <hr />
                <div class="card">
                    <div class="card-body d-flex justify-content-end align-items-end">
                        <!-- Search Form -->
                        <form action="{{ url('admin/post/post-listing') }}" method="GET" class="d-flex">
                            <input type="text" name="search" class="form-control me-2" placeholder="Search by Post Name"
                                value="{{ request()->input('search') }}">
                            <button type="submit" class="btn btn-secondary">Search</button>
                        </form>
                        @if (request()->has('search'))
                            <a class="btn btn-primary me-1" href="{{ url()->current() }}">
                                <i class="bx bx-refresh"></i>
                            </a>
                        @endif
                        {{-- <a href="{{ url('/admin/maker/maker-register') }}" class="btn btn-primary">Add New Maker</a> --}}
                    </div>
                    <div class="card-body">
                        <table class="table mb-0 table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="">Chitti No.</th>
                                    <th scope="col" class="">Title</th>
                                    <th scope="col" class="">Sent Time </th>
                                    <th scope="col" class="">Geography</th>
                                    <th scope="col" class="">Area</th>
                                    {{-- <th scope="col" class="">Status</th> --}}
                                    <th scope="col" class="">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $index = ($chittis->currentPage() - 1) * $chittis->perPage() + 1;
                                @endphp
                                @foreach ($chittis as $chitti)
                                    <tr>
                                        <th scope="row" class="" data-id="{{ $chitti->chittiId }}">
                                            {{ $index }}</th>
                                        <td class=""><a href="{{ route('post-preview', $chitti->chittiId) }}"
                                                class="text-primary">
                                                {{ $chitti->Title }}
                                            </a></td>
                                        <td class="">{{ $chitti->dateOfCreation }}</td>
                                        <td>
                                            @if (array_key_exists($chitti->geographyId, config('geography')))
                                                {{ config('geography')[$chitti->geographyId]}}
                                            @endif
                                        </td>
                                        <td>
                                            {{ $chitti->geography  }}
                                        </td>
                                        {{-- <td>{{ $chitti->makerStatus }}</td> --}}

                                        <td class="">
                                            {{-- <a href="{{ route('admin.checker-listing', $chitti->chittiId) }}" class="btn btn-sm btn-primary edit-user">Sent_to_checker</a> --}}
                                            {{-- <form action="{{ route('admin.send-to-checker', $chitti->chittiId) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-primary" name="action"
                                                    value="send_to_checker">Send to Checker</button>
                                            </form> --}}

                                            <form action="{{ route('admin.post-delete', $chitti->chittiId) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-sm btn-danger delete-user">Delete</button>
                                            </form>
                                            {{-- <a href="{{ route('admin.maker-update', $chitti->chittiId) }}" class="btn btn-sm btn-primary update-user mt-3">Send to checker</a> --}}
                                        </td>
                                    </tr>
                                    @php $index++;  @endphp
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end mt-4">
                            {{ $chittis->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end page wrapper -->
@endsection
