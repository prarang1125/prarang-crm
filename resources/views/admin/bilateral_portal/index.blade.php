@extends('layouts.admin.admin')
@section('title', 'Bilateral Portal Listing')

@section('content')
<!--start page wrapper -->
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Admin</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.bilateral-portals.index') }}"><i class="bx bx-globe"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Bilateral Portals</li>
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

            <h6 class="mb-0 text-uppercase">Bilateral Portal Listing</h6>
            <hr/>
            <div class="card">
                <div class="card-body d-flex justify-content-end align-items-end">
                    <!-- Search Form -->
                    <form action="{{ route('admin.bilateral-portals.index') }}" method="GET" class="d-flex me-3">
                        <input type="text" name="search" class="form-control me-2" placeholder="Search by title, slug, or country" value="{{ $search }}">
                        <button type="submit" class="btn btn-secondary">Search</button>
                    </form>
                    @if(request()->has('search'))
                        <a class="btn btn-primary me-1" href="{{ route('admin.bilateral-portals.index') }}">
                            <i class="bx bx-refresh"></i>
                        </a>
                    @endif
                    <a href="{{ route('admin.bilateral-portals.create') }}" class="btn btn-primary">Add New Portal</a>
                </div>
                <div class="card-body">
                    @if($bilateralPortals->count() > 0)
                        <table class="table mb-0 table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="text-center">#</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Primary Country</th>
                                    <th scope="col">Secondary Country</th>
                                    <th scope="col">Slug</th>
                                    <th scope="col">Content Code</th>
                                    <th scope="col">Created At</th>
                                    <th scope="col" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bilateralPortals as $index => $portal)
                                    <tr>
                                        <th scope="row" class="text-center">{{ $bilateralPortals->firstItem() + $index }}</th>
                                        <td>
                                            <strong>{{ $portal->title }}</strong>
                                            @if($portal->slogan)
                                                <br><small class="text-muted">{{ $portal->slogan }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $portal->primaryCountry->country_code ?? 'N/A' }}</span>
                                            {{ $portal->primaryCountry->country_name ?? 'Unknown' }}
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $portal->secondaryCountry->country_code ?? 'N/A' }}</span>
                                            {{ $portal->secondaryCountry->country_name ?? 'Unknown' }}
                                        </td>
                                        <td>
                                            <code>{{ $portal->slug }}</code>
                                        </td>
                                        <td>
                                            @if($portal->content_country_code)
                                                <span class="badge bg-info">{{ $portal->content_country_code }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $portal->created_at->format('M d, Y') }}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.bilateral-portals.show', $portal) }}" class="btn btn-sm btn-info" title="View">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                                <a href="{{ route('admin.bilateral-portals.edit', $portal) }}" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.bilateral-portals.destroy', $portal) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this bilateral portal?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
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
                            {{ $bilateralPortals->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bx bx-globe" style="font-size: 3rem; color: #ccc;"></i>
                            <h5 class="mt-3 text-muted">No Bilateral Portals Found</h5>
                            <p class="text-muted">Start by creating your first bilateral portal.</p>
                            <a href="{{ route('admin.bilateral-portals.create') }}" class="btn btn-primary">
                                <i class="bx bx-plus"></i> Create Bilateral Portal
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
