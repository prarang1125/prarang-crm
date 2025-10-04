@extends('layouts.admin.admin')
@section('title', 'Our Teams')

@section('content')
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
    <!--start page wrapper -->
    <div class="page-content">
        <!--breadcrumb-->

        <div class="row">
            <div class="card" style="padding-top: 15px;">
                <div class="col-xl-9 mx-auto w-100">

                    <div class="container">
                        <h6>Our Team</h6>
                        <p class="text-end">
                            <a href="{{ route('our-team.create') }}" class="btn btn-primary mb-3">Add New Member</a>
                        </p>
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sr.</th>
                                    <th>Profile Image</th>
                                    <th>Display Name</th>
                                    <th>Role</th>
                                    <th>LinkedIn</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($teams as $team)
                                    <tr>
                                        <td>{{ $team->id }}</td>
                                        <td><img src="{{ Storage::url($team->profile_image) }}"
                                                alt="{{ $team->display_name }}" style="width: 50px; height: 50px;"></td>
                                        <td>{{ $team->display_name }}</td>
                                        <td>{{ $team->role }}</td>
                                        <td><a href="{{ $team->linkedin_link }}" target="_blank">LinkedIn</a></td>
                                        <td>
                                            <a href="{{ route('our-team.edit', $team->id) }}"
                                                class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('our-team.destroy', $team->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
