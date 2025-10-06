@extends('layouts.admin.admin')
@section('title', 'Country Portal Management')

@section('content')
<!--start page wrapper -->
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Admin</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.country-portals.index') }}"><i class="bx bx-world"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Country Portals</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="col-xl-12 mx-auto w-100">
            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success mt-3">
                    {{ session('success') }}
                </div>
            @endif
            
            <!-- Error Message -->
            @if(session('error'))
                <div class="alert alert-danger mt-3">
                    {{ session('error') }}
                </div>
            @endif

            <h6 class="mb-0 text-uppercase">Country Portal Management</h6>
            <hr/>
            <div class="card">
                <div class="card-body d-flex justify-content-end align-items-end">
                    <!-- Search Form -->
                    <form action="{{ route('admin.country-portals.index') }}" method="GET" class="d-flex me-3">
                        <input type="text" name="search" class="form-control me-2" placeholder="Search by country name, code, or language" value="{{ $search }}">
                        <button type="submit" class="btn btn-secondary">Search</button>
                    </form>
                    @if(request()->has('search'))
                        <a class="btn btn-primary me-1" href="{{ route('admin.country-portals.index') }}">
                            <i class="bx bx-refresh"></i>
                        </a>
                    @endif
                    <a href="{{ route('admin.country-portals.create') }}" class="btn btn-primary">Add New Country</a>
                </div>
                <div class="card-body">
                    @if($countryPortals->count() > 0)
                        <table class="table mb-0 table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="text-center">#</th>
                                    <th scope="col">Country Name</th>
                                    <th scope="col">Code</th>
                                    <th scope="col">Locale Name</th>
                                    <th scope="col">Language</th>
                                    <th scope="col">Timezone</th>
                                    <th scope="col">Slogan</th>
                                    <th scope="col">Created At</th>
                                    <th scope="col" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($countryPortals as $index => $country)
                                    <tr>
                                        <th scope="row" class="text-center">{{ $countryPortals->firstItem() + $index }}</th>
                                        <td>
                                            <strong>{{ $country->country_name }}</strong>
                                            @if($country->analytics_slug)
                                                <br><small class="text-muted">Slug: {{ $country->analytics_slug }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $country->country_code }}</span>
                                        </td>
                                        <td>
                                            @if($country->country_name_locale)
                                                {{ $country->country_name_locale }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($country->locale_lang)
                                                <span class="badge bg-info">{{ $country->locale_lang }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($country->timezone)
                                                <small>{{ $country->timezone }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($country->slogan)
                                                <small>{{ Str::limit($country->slogan, 30) }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $country->created_at->format('M d, Y') }}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.country-portals.show', $country->id) }}" class="btn btn-sm btn-info" title="View">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                                <a href="{{ route('admin.country-portals.edit', $country->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.country-portals.destroy', $country->id) }}" method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this country portal?')">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $countryPortals->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bx bx-world" style="font-size: 3rem; color: #ccc;"></i>
                            <h5 class="mt-3 text-muted">No Country Portals Found</h5>
                            <p class="text-muted">Start by creating your first country portal.</p>
                            <a href="{{ route('admin.country-portals.create') }}" class="btn btn-primary">
                                <i class="bx bx-plus"></i> Create Country Portal
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->
@endsection
