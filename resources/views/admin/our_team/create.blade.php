@extends('layouts.admin.admin')
@section('title', 'Our Teams')

@section('content')

    <!--start page wrapper -->
    <div class="page-content">
        <!--breadcrumb-->
        <div class="container">
            <h5>Add New Team Member</h5>
            <br>
            <form action="{{ route('our-team.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="profile_image" class="form-label">Profile Image</label>
                    <input type="file" class="form-control" id="profile_image" name="profile_image" required>
                </div>
                <div class="mb-3">
                    <label for="display_name" class="form-label">Display Name</label>
                    <input type="text" class="form-control" id="display_name" name="display_name" required>
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
                    <input type="text" class="form-control" id="role" name="role" required>
                </div>
                <div class="mb-3">
                    <label for="linkedin_link" class="form-label">LinkedIn Link</label>
                    <input type="url" class="form-control" id="linkedin_link" name="linkedin_link">
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>

    </div>
@endsection
