@extends('layouts.admin.admin')
@section('title', 'Advertisement Posts')
@section('content')
<div class="page-content">
    <div class="card">
        <div class="card-body">
            <h5 class="text-primary">
                Advertisement -> Posts
            </h5>
            <hr>
            <div class="mb-3"> Ad ID:{{ $ad->id }} </div>
            <div class="mb-3"> Ad Title: {{ $ad->ad_title }} </div>
            
            <hr>
            <h6 class="mb-3"> This Advertisement is Running On </h6>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Chitti ID</th>
                            <th>Title</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($chittis as $chitti)
                        <tr>
                            <td>{{ $chitti->chittiId }}</td>
                            <td>{{ $chitti->Title }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted">
                                This advertisement is not running on any Chitti.
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
