@extends('layouts.admin.admin')
@section('title', 'View Bilateral Portal')

@section('content')
<!--start page wrapper -->
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Admin</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.bilateral-portals.index') }}"><i class="bx bx-globe"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">View Bilateral Portal</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    
    <div class="row">
        <div class="col-xl-10 mx-auto">
            <h6 class="mb-0 text-uppercase">{{ $bilateralPortal->title }}</h6>
            <hr/>
            
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Portal Details</h5>
                        <div>
                            <a href="{{ route('admin.bilateral-portals.edit', $bilateralPortal) }}" class="btn btn-warning btn-sm">
                                <i class="bx bx-edit"></i> Edit
                            </a>
                            <a href="{{ route('admin.bilateral-portals.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bx bx-arrow-back"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="bx bx-info-circle"></i> Basic Information</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Title:</strong></td>
                                            <td>{{ $bilateralPortal->title }}</td>
                                        </tr>
                                        @if($bilateralPortal->slogan)
                                        <tr>
                                            <td><strong>Slogan:</strong></td>
                                            <td>{{ $bilateralPortal->slogan }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td><strong>Slug:</strong></td>
                                            <td><code>{{ $bilateralPortal->slug }}</code></td>
                                        </tr>
                                        @if($bilateralPortal->content_country_code)
                                        <tr>
                                            <td><strong>Content Code:</strong></td>
                                            <td><span class="badge bg-info">{{ $bilateralPortal->content_country_code }}</span></td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td><strong>Created:</strong></td>
                                            <td>{{ $bilateralPortal->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Updated:</strong></td>
                                            <td>{{ $bilateralPortal->updated_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="bx bx-world"></i> Countries</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <h6 class="text-primary">Primary Country</h6>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-primary me-2">{{ $bilateralPortal->primaryCountry->country_code ?? 'N/A' }}</span>
                                                <strong>{{ $bilateralPortal->primaryCountry->country_name ?? 'Unknown' }}</strong>
                                            </div>
                                            @if($bilateralPortal->primaryCountry && $bilateralPortal->primaryCountry->country_name_locale)
                                                <small class="text-muted">{{ $bilateralPortal->primaryCountry->country_name_locale }}</small>
                                            @endif
                                        </div>
                                        
                                        <div class="col-12">
                                            <h6 class="text-secondary">Secondary Country</h6>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-secondary me-2">{{ $bilateralPortal->secondaryCountry->country_code ?? 'N/A' }}</span>
                                                <strong>{{ $bilateralPortal->secondaryCountry->country_name ?? 'Unknown' }}</strong>
                                            </div>
                                            @if($bilateralPortal->secondaryCountry && $bilateralPortal->secondaryCountry->country_name_locale)
                                                <small class="text-muted">{{ $bilateralPortal->secondaryCountry->country_name_locale }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($bilateralPortal->header_image || $bilateralPortal->footer_image)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0"><i class="bx bx-image"></i> Images</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @if($bilateralPortal->header_image)
                                        <div class="col-md-6">
                                            <h6>Header Image</h6>
                                            <p class="text-muted">{{ $bilateralPortal->header_image }}</p>
                                        </div>
                                        @endif
                                        @if($bilateralPortal->footer_image)
                                        <div class="col-md-6">
                                            <h6>Footer Image</h6>
                                            <p class="text-muted">{{ $bilateralPortal->footer_image }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if($bilateralPortal->connections)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0"><i class="bx bx-link"></i> Connections</h6>
                                </div>
                                <div class="card-body">
                                    <pre class="bg-light p-3 rounded"><code>{{ is_string($bilateralPortal->connections) ? $bilateralPortal->connections : json_encode($bilateralPortal->connections, JSON_PRETTY_PRINT) }}</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if($bilateralPortal->header_scripts || $bilateralPortal->footer_scripts)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card border-dark">
                                <div class="card-header bg-dark text-white">
                                    <h6 class="mb-0"><i class="bx bx-code"></i> Scripts</h6>
                                </div>
                                <div class="card-body">
                                    @if($bilateralPortal->header_scripts)
                                    <div class="mb-3">
                                        <h6>Header Scripts</h6>
                                        <pre class="bg-light p-3 rounded"><code>{{ is_string($bilateralPortal->header_scripts) ? $bilateralPortal->header_scripts : json_encode($bilateralPortal->header_scripts, JSON_PRETTY_PRINT) }}</code></pre>
                                    </div>
                                    @endif
                                    
                                    @if($bilateralPortal->footer_scripts)
                                    <div>
                                        <h6>Footer Scripts</h6>
                                        <pre class="bg-light p-3 rounded"><code>{{ is_string($bilateralPortal->footer_scripts) ? $bilateralPortal->footer_scripts : json_encode($bilateralPortal->footer_scripts, JSON_PRETTY_PRINT) }}</code></pre>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <div>
                            <form action="{{ route('admin.bilateral-portals.destroy', $bilateralPortal) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this bilateral portal? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bx bx-trash"></i> Delete Portal
                                </button>
                            </form>
                        </div>
                        <div>
                            <a href="{{ route('admin.bilateral-portals.edit', $bilateralPortal) }}" class="btn btn-warning">
                                <i class="bx bx-edit"></i> Edit Portal
                            </a>
                            <a href="{{ route('admin.bilateral-portals.index') }}" class="btn btn-secondary">
                                <i class="bx bx-list-ul"></i> All Portals
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
