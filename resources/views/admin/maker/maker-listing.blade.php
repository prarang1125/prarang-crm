@extends('layouts.admin.admin')
@section('title', 'Maker Listing')

@section('content')
    <style>
        /* Italic Tag */
        .mx-auto .d-flex i {
            font-weight: 700;
            font-size: 20px;
        }

        /* Button */
        .mx-auto>.d-flex a {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 5px;
        }

        /* Button */
        .mx-auto>.d-flex a {
            display: flex;
            justify-content: center;
            align-items: center;
            padding-bottom: 11px !important;
            padding-left: 8px !important;
        }

        /* Button */
        .wrapper .page-wrapper .page-content .row .mx-auto>.d-flex a {
            padding-top: 14px !important;
        }
    </style>
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
                        <li class="breadcrumb-item active" aria-current="page">Maker Listing</li>
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
                {{-- <h6 class="mb-0 text-uppercase">Maker Listing</h6> --}}
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 text-uppercase">Maker Listing</h6>
                    <a class="nav-link dropdown-toggle-nocaret position-relative btn btn-outline-primary p-1"
                        href="{{ route('admin.post-return-from-checker-listing') }}" role="button">
                        @if ($notification > 0)
                            <span class="alert-count">{{ $notification }}</span>
                        @endif
                        <i class="bx bx-bell"></i>
                    </a>
                </div>
                <hr />
                <div class="card">
                    <div class="card-body d-flex justify-content-end align-items-end">
                        <!-- Search Form -->
                        <form action="{{ url('admin/maker/maker-listing') }}" method="GET" class="d-flex me-3">
                            <input type="text" name="search" class="form-control me-2"
                                placeholder="Search by Title or Subtitle" value="{{ request()->input('search') }}">
                            <button type="submit" class="btn btn-secondary">Search</button>
                        </form>
                        @if (request()->has('search'))
                            <a class="btn btn-primary me-1" href="{{ url()->current() }}">
                                <i class="bx bx-refresh"></i>
                            </a>
                        @endif
                        <a href="{{ url('/admin/maker/maker-register') }}" class="btn btn-primary">Add New Maker</a>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <table class="table mb-0 table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="">Sr.</th>
                                    <th scope="col" class="">Title</th>
                                    <th scope="col" class="">Maker</th>
                                    <th scope="col" class="">Created at</th>
                                    <th scope="col" class="">Geography</th>
                                    <th scope="col" class="">Area</th>
                                    <th></th>
                                    <th scope="col" class="">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $index = ($chittis->currentPage() - 1) * $chittis->perPage() + 1;
                                @endphp
                                @foreach ($chittis as $chitti)
                                    {{-- @dd($chitti); --}}
                                    <tr>
                                        <th scope="row" class="" data-id="{{ $chitti->chittiId }}">
                                            {{ $index }}</th>
                                        <td class="">
                                            {{ $chitti->Title }}
                                            </a></td>
                                        <td>{{ $chitti->userName ?? 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($chitti->dateOfCreation)->format('d M, Y h:i a') }}</td>

                                        <td>
                                            @if (array_key_exists($chitti->geographyId, config('geography')))
                                                {{ config('geography')[$chitti->geographyId] }}
                                            @endif
                                        </td>
                                        <td>
                                            {{ $chitti->geography }}
                                        </td>


                                        <td>
                                            @if ($chitti->finalStatus === 'approved')
                                                <i class="bx bx-check-circle text-success mt-2"></i>
                                            @else
                                                <i class="bx bx-info-circle text-warning mt-2"></i>
                                            @endif
                                        </td>
                                        <td class="">

                                            @if ($chitti->checkerStatus != 'maker_to_checker' && $chitti->checkerStatus != 'sent_to_uploader')
                                                <a href="{{ route('admin.maker-edit', $chitti->chittiId) }}"
                                                    class="btn btn-sm btn-primary edit-user">Edit</a>
                                                <a href="{{ route('admin.maker-delete', $chitti->chittiId) }}"
                                                    onclick="return confirm('Are you want to delete this post.')"
                                                    class="btn btn-sm btn-danger"><i class="bx bx-trash"></i></a>
                                            @else
                                                <x-post.maker.change-title :chitti="$chitti" />
                                            @endif

                                        </td>
                                    </tr>
                                    @php $index++; @endphp
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
@endsection
