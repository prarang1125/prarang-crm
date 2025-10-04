@extends('layouts.admin.admin')
@section('title', 'Our Teams')

@section('content')

    <!--start page wrapper -->
    <div class="page-content">

        <div class="container">
            <h1>Edit Team Member</h1>
            <form action="{{ route('our-team.update', $team->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="profile_image" class="form-label">Profile Image</label>
                    <input type="file" class="form-control" id="profile_image" name="profile_image">
                    <img src="{{ Storage::url($team->profile_image) }}" alt="Current Profile Image"
                        style="width: 100px; height: 100px; margin-top: 10px;">
                </div>
                <div class="mb-3">
                    <label for="display_name" class="form-label">Display Name</label>
                    <input type="text" class="form-control" id="display_name" name="display_name"
                        value="{{ $team->display_name }}" required>
                </div>
                <div class="mb-3">
                    <label for="user' class="form-label">Username</label>
                    <select class="form-control" name="userId" id="user">
                        <option value="">Select User</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->firstName }} {{ $user->lastName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <input type="text" class="form-control" id="role" name="role" value="{{ $team->role }}"
                        required>
                </div>
                <div class="mb-3">
                    <label for="linkedin_link" class="form-label">LinkedIn Link</label>
                    <input type="url" class="form-control" id="linkedin_link" name="linkedin_link"
                        value="{{ $team->linkedin_link }}">
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
        <!--breadcrumb-->

    </div>
@endsection
