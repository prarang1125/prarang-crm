@extends('layouts.admin.admin')
@section('title', 'Advertisements')
@section('content')
<div class="page-content">
    {{-- Breadcrumb --}}
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">
            Advertisements
        </div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.ads.index') }}">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Advertisement List
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>
    </div>
    @endif
    {{-- Main Card --}}
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="mb-0 text-uppercase text-primary">
                        Advertisement Management
                    </h5>
                </div>
                <a href="{{ route('admin.ads.create') }}"
                    class="btn btn-primary">
                    <i class="bx bx-plus"></i>
                    Add Advertisement
                </a>
            </div>
            <hr>
            {{-- Advertisement Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Ad Id</th>
                            <th>Partner ID</th>
                            <th>City ID</th>
                            <th>Media</th>
                            <th>Ad Title</th>
                            <th>CTA Title</th>
                            <th>Ad Type</th>
                            <th>Status</th>
                            <th width="150">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $ad)
                        <tr>
                            <td> {{ $ad->id }}</td>
                            <td>{{ $ad->partner_id }}</td>
                            <td>{{ $ad->city_id }} </td>
                            <td>
                                @if ($ad->creative_link)
                                @if ($ad->ad_type === 'image')
                                <a href="{{ $ad->ad_link }}" target="_blank" rel="noopener noreferrer">
                                    <img src="{{ $ad->creative_link }}" alt="{{ $ad->ad_title }}"
                                        style=" width: 100px; height: 60px;  object-fit: cover; border-radius: 5px; border: 1px solid #ddd;">
                                </a>
                                @elseif ($ad->ad_type === 'video')
                                <a href="{{ $ad->creative_link }}" target="_blank"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="bx bx-video"></i>
                                    Video
                                </a>
                                @endif
                                @else
                                <span class="text-muted">
                                    No Creative
                                </span>
                                @endif
                            </td>
                            <td> {{ $ad->ad_title }}</td>
                            <td>{{ $ad->cta_title }}</td>
                            <td>
                                @if ($ad->ad_type === 'image')
                                <span> Image </span>
                                @elseif ($ad->ad_type === 'video')
                                <span>Video</span>
                                @else
                                <span>{{ $ad->ad_type }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($ad->status)
                                <span class="badge bg-success"> Active </span>
                                @else
                                <span class="badge bg-danger"> Inactive </span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.ads.edit', $ad->id) }}" class="btn btn-sm btn-primary">
                                    Edit
                                </a>
                                <form action="{{ route('admin.ads.destroy', $ad->id) }}"
                                    method="POST" class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this advertisement?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9"
                                class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bx bx-image-alt" style="font-size: 40px;">
                                    </i>
                                    <p class="mb-0"> No advertisements found. </p>
                                    <a href="{{ route('admin.ads.create') }}" class="btn btn-primary btn-sm mt-2">
                                        Create Advertisement
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection