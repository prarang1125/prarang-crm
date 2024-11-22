@extends('layouts.admin.admin')
@section('title', 'Portals')

@section('content')
<!--start page wrapper -->
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Admin</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                   
                    <li class="breadcrumb-item active" aria-current="page">Portal List</li>
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
                <h6 class="mb-0 text-uppercase text-primary">Portals</h6>
                <hr/>
                <div class="">
                    <div class="d-flex justify-content-end align-items-end">
                        <a class="btn btn-info btn-sm" href="{{ route('portal.create') }}">New Portal</a>
                    </div>
                    <div class="card-body">
                        <table class="table mb-0 table-hover table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="">Sr.</th>
                                    <th scope="col" class="">CityId</th>
                                    <th scope="col" class="">CityName</th>
                                    <th scope="col" class="">CityCode </th>
                                    <th scope="col" class="">Local Name</th>
                                    <th scope="col" class="">Local Language</th>                                   
                                    <th scope="col" class="">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($portals as $portal)
                                <tr>
                                    <td>{{ $loop->iteration + ($portals->currentPage() - 1) * $portals->perPage() }}</td>
                                    <td>{{ $portal->city_id }}</td>
                                    <td>{{ $portal->city_name }}</td>
                                    <td>{{ $portal->city_code }}</td>
                                    <td>{{ $portal->city_name_local }}</td>
                                    <td>{{ $portal->local_lang }}</td>
                                    <td>
                                        <a class="btn btn-info btn-sm" href="{{ route('portal.edit', $portal->id) }}">Edit</a>
                                        <form action="{{ route('portal.destroy', $portal->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                               
                            </tbody>
                        </table>
                        <div class="mt-3 text-end">
                            {{ $portals->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection


