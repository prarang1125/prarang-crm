@extends('layouts.admin.admin')
@section('title', 'View Country Portal')

@section('content')
<!--start page wrapper -->
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Admin</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.country-portals.index') }}"><i class="bx bx-world"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">View Country Portal</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    
    <div class="row">
        <div class="col-xl-12 mx-auto">
            <h6 class="mb-0 text-uppercase">{{ $countryPortal->country_name }} Portal Details</h6>
            <hr/>
            
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <span class="badge bg-primary me-2">{{ $countryPortal->country_code }}</span>
                            {{ $countryPortal->country_name }}
                            @if($countryPortal->country_name_locale)
                                <small class="text-muted">({{ $countryPortal->country_name_locale }})</small>
                            @endif
                        </h5>
                        <div>
                            <a href="{{ route('admin.country-portals.edit', $countryPortal) }}" class="btn btn-warning btn-sm">
                                <i class="bx bx-edit"></i> Edit
                            </a>
                            <a href="{{ route('admin.country-portals.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bx bx-arrow-back"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="bx bx-info-circle"></i> Basic Information</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Country Name:</strong></td>
                                            <td>{{ $countryPortal->country_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Country Code:</strong></td>
                                            <td><span class="badge bg-primary">{{ $countryPortal->country_code }}</span></td>
                                        </tr>
                                        @if($countryPortal->country_name_locale)
                                        <tr>
                                            <td><strong>Local Name:</strong></td>
                                            <td>{{ $countryPortal->country_name_locale }}</td>
                                        </tr>
                                        @endif
                                        @if($countryPortal->locale_lang)
                                        <tr>
                                            <td><strong>Language:</strong></td>
                                            <td><span class="badge bg-info">{{ $countryPortal->locale_lang }}</span></td>
                                        </tr>
                                        @endif
                                        @if($countryPortal->slogan)
                                        <tr>
                                            <td><strong>Slogan:</strong></td>
                                            <td><em>"{{ $countryPortal->slogan }}"</em></td>
                                        </tr>
                                        @endif
                                        @if($countryPortal->timezone)
                                        <tr>
                                            <td><strong>Timezone:</strong></td>
                                            <td>{{ $countryPortal->timezone }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td><strong>Created:</strong></td>
                                            <td>{{ $countryPortal->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Updated:</strong></td>
                                            <td>{{ $countryPortal->updated_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Analytics & Technical -->
                        <div class="col-md-6">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="bx bx-bar-chart"></i> Analytics & Technical</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        @if($countryPortal->anlytics_code)
                                        <tr>
                                            <td><strong>Analytics Code:</strong></td>
                                            <td><code>{{ $countryPortal->anlytics_code }}</code></td>
                                        </tr>
                                        @endif
                                        @if($countryPortal->analytics_slug)
                                        <tr>
                                            <td><strong>Analytics Slug:</strong></td>
                                            <td><code>{{ $countryPortal->analytics_slug }}</code></td>
                                        </tr>
                                        @endif
                                        @if($countryPortal->maps)
                                        <tr>
                                            <td><strong>Maps URL:</strong></td>
                                            <td><a href="{{ $countryPortal->maps }}" target="_blank">{{ Str::limit($countryPortal->maps, 50) }}</a></td>
                                        </tr>
                                        @endif
                                        @if($countryPortal->embassy_link)
                                        <tr>
                                            <td><strong>Embassy Link:</strong></td>
                                            <td><a href="{{ $countryPortal->embassy_link }}" target="_blank">{{ Str::limit($countryPortal->embassy_link, 50) }}</a></td>
                                        </tr>
                                        @endif
                                    </table>
                                    
                                    @if(!$countryPortal->anlytics_code && !$countryPortal->analytics_slug && !$countryPortal->maps && !$countryPortal->embassy_link)
                                        <p class="text-muted text-center">No analytics or technical data configured</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Bilateral Portals Usage -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0"><i class="bx bx-globe"></i> Bilateral Portal Usage</h6>
                                </div>
                                <div class="card-body">
                                    @php
                                        $primaryPortals = $countryPortal->primaryBilateralPortals;
                                        $secondaryPortals = $countryPortal->secondaryBilateralPortals;
                                        $totalPortals = $primaryPortals->count() + $secondaryPortals->count();
                                    @endphp
                                    
                                    @if($totalPortals > 0)
                                        <div class="row">
                                            @if($primaryPortals->count() > 0)
                                            <div class="col-md-6">
                                                <h6 class="text-primary">As Primary Country ({{ $primaryPortals->count() }})</h6>
                                                <ul class="list-group list-group-flush">
                                                    @foreach($primaryPortals as $portal)
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            {{ $portal->title }}
                                                            <a href="{{ route('admin.bilateral-portals.show', $portal) }}" class="btn btn-sm btn-outline-primary">View</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            @endif
                                            
                                            @if($secondaryPortals->count() > 0)
                                            <div class="col-md-6">
                                                <h6 class="text-secondary">As Secondary Country ({{ $secondaryPortals->count() }})</h6>
                                                <ul class="list-group list-group-flush">
                                                    @foreach($secondaryPortals as $portal)
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            {{ $portal->title }}
                                                            <a href="{{ route('admin.bilateral-portals.show', $portal) }}" class="btn btn-sm btn-outline-secondary">View</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            @endif
                                        </div>
                                    @else
                                        <p class="text-muted text-center">This country is not used in any bilateral portals yet.</p>
                                        <div class="text-center">
                                            <a href="{{ route('admin.bilateral-portals.create') }}" class="btn btn-primary">
                                                <i class="bx bx-plus"></i> Create Bilateral Portal
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- JSON Data -->
                    @if($countryPortal->weather || $countryPortal->news || $countryPortal->local_metrics || $countryPortal->important_links)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0"><i class="bx bx-data"></i> Additional Data</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @if($countryPortal->weather)
                                        <div class="col-md-6 mb-3">
                                            <h6>Weather Widget Code</h6>
                                            <pre class="bg-light p-3 rounded"><code>{{ $countryPortal->weather }}</code></pre>
                                        </div>
                                        @endif
                                        
                                        @if($countryPortal->news)
                                        <div class="col-md-6 mb-3">
                                            <h6>News Sources</h6>
                                            <pre class="bg-light p-3 rounded"><code>{{ is_string($countryPortal->news) ? $countryPortal->news : json_encode($countryPortal->news, JSON_PRETTY_PRINT) }}</code></pre>
                                        </div>
                                        @endif
                                        
                                        @if($countryPortal->local_metrics)
                                        <div class="col-md-6 mb-3">
                                            <h6>Local Metrics</h6>
                                            <pre class="bg-light p-3 rounded"><code>{{ is_string($countryPortal->local_metrics) ? $countryPortal->local_metrics : json_encode($countryPortal->local_metrics, JSON_PRETTY_PRINT) }}</code></pre>
                                        </div>
                                        @endif
                                        
                                        @if($countryPortal->important_links)
                                        <div class="col-md-12 mb-3">
                                            <h6>Important Links</h6>
                                            @php
                                                $links = is_string($countryPortal->important_links) 
                                                    ? json_decode($countryPortal->important_links, true) 
                                                    : $countryPortal->important_links;
                                            @endphp
                                            
                                            @if(is_array($links))
                                                @if(isset($links['tourist_places']) && is_array($links['tourist_places']) && count($links['tourist_places']) > 0)
                                                <div class="mb-3">
                                                    <strong class="text-primary">Tourist Places:</strong>
                                                    <ul class="list-group mt-2">
                                                        @foreach($links['tourist_places'] as $url)
                                                            <li class="list-group-item">
                                                                <a href="{{ $url }}" target="_blank" class="text-decoration-none">
                                                                    {{ $url }} <i class="bx bx-link-external ms-1"></i>
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                @endif
                                                
                                                @if(isset($links['community_pages']) && is_array($links['community_pages']) && count($links['community_pages']) > 0)
                                                <div class="mb-3">
                                                    <strong class="text-success">Community Pages:</strong>
                                                    <ul class="list-group mt-2">
                                                        @foreach($links['community_pages'] as $url)
                                                            <li class="list-group-item">
                                                                <a href="{{ $url }}" target="_blank" class="text-decoration-none">
                                                                    {{ $url }} <i class="bx bx-link-external ms-1"></i>
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                @endif
                                                
                                                @if(isset($links['resources']) && is_array($links['resources']) && count($links['resources']) > 0)
                                                <div class="mb-3">
                                                    <strong class="text-info">Resources:</strong>
                                                    <ul class="list-group mt-2">
                                                        @foreach($links['resources'] as $url)
                                                            <li class="list-group-item">
                                                                <a href="{{ $url }}" target="_blank" class="text-decoration-none">
                                                                    {{ $url }} <i class="bx bx-link-external ms-1"></i>
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                @endif
                                                
                                                @if((!isset($links['tourist_places']) || count($links['tourist_places']) == 0) && 
                                                    (!isset($links['community_pages']) || count($links['community_pages']) == 0) && 
                                                    (!isset($links['resources']) || count($links['resources']) == 0))
                                                    <p class="text-muted">No important links available.</p>
                                                @endif
                                            @else
                                                <pre class="bg-light p-3 rounded"><code>{{ json_encode($links, JSON_PRETTY_PRINT) }}</code></pre>
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <div>
                            @if($totalPortals == 0)
                            <form action="{{ route('admin.country-portals.destroy', $countryPortal) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this country portal? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bx bx-trash"></i> Delete Country
                                </button>
                            </form>
                            @else
                            <button type="button" class="btn btn-danger" disabled title="Cannot delete country that is used in bilateral portals">
                                <i class="bx bx-trash"></i> Delete Country (Used in {{ $totalPortals }} portal{{ $totalPortals > 1 ? 's' : '' }})
                            </button>
                            @endif
                        </div>
                        <div>
                            <a href="{{ route('admin.country-portals.edit', $countryPortal) }}" class="btn btn-warning">
                                <i class="bx bx-edit"></i> Edit Country
                            </a>
                            <a href="{{ route('admin.country-portals.index') }}" class="btn btn-secondary">
                                <i class="bx bx-list-ul"></i> All Countries
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->
@endsection
