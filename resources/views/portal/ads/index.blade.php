@extends('layouts.admin.admin')
@section('title', 'Portal Ads')
@section('content')
<div class="page-content">
    <!--breadcrumb-->
    <div class="d-sm-flex align-items-center mb-3 page-breadcrumb d-none">
        <div class="pe-3 breadcrumb-title">Admin</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="mb-0 p-0 breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('admin/portal-ads') }}"><i class="bx bx-user"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Portal Ads</li>
                </ol>
            </nav>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <section>
        <div class="card">
            <div class="card-body">

                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="mb-0">Portal Ads List</h5>
                    <a href="{{ url('admin/portal-ads/create') }}" class="btn btn-primary">
                        <i class="bx bx-plus"></i> Add New
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Portal</th>
                                <th>Title</th>
                                <th>Logo</th>
                                <th>Interaction URL</th>
                                <th>Non Interaction URL</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ads as $key => $ad)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $ad->portal_name ?? '-' }}</td>
                                    <td>{{ $ad->title }}</td>
                                    <td>
                                        @if ($ad->ad_logo)
                                            <img src="{{ $ad->ad_logo }}" alt="logo" width="50" height="50" style="object-fit:cover; border-radius:4px;">
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($ad->interaction_url)
                                            <a href="{{ $ad->interaction_url }}" target="_blank">{{ Str::limit($ad->interaction_url, 25) }}</a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($ad->non_interaction_url)
                                            <a href="{{ $ad->non_interaction_url }}" target="_blank">{{ Str::limit($ad->non_interaction_url, 25) }}</a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($ad->status == 1)
                                            <span class="bg-success badge">Active</span>
                                        @else
                                            <span class="bg-danger badge">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($ad->created_at)->format('d M Y') }}</td>
                                    <td class="text-center">
                                        <a href="{{ url('admin/portal-ads/' . $ad->id . '/edit') }}"
                                            class="btn btn-sm btn-warning" title="Edit">
                                            <i class="bx bx-edit"></i>
                                        </a>

                                        <form action="{{ url('admin/portal-ads/' . $ad->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Are you sure you want to delete this ad?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No portal ads found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>

</div>
@endsection