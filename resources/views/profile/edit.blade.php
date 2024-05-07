@extends('layouts.main')
@section('title', 'Profile Edit')
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="mb-2">
            <h2 class="mt-3 text-custom">Profile</h2>
        </div>
        @include('include/error-alert')
        <div class="row">
            <div class="col-12 col-xl-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h4 class="text-custom">Profile Information</h4>
                        {{-- @if (Auth::user()->is_faculty)
                            <form method="post" action="{{ route('profile.update') }}">
                            @else
                            @endif --}}
                        <form method="post" action="{{ route('profile.update') }}">
                            @csrf
                            @method('patch')
                            @if (Auth::user()->is_faculty)
                                <div class="form-group mt-2">
                                    <label class="form-label">Faculty Id.</label>
                                    <input type="text" class="form-control" name="text"
                                        value="{{ Auth::user()->regno }}" readonly>
                                </div>
                            @endif
                            <div class="form-group mt-2">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" value="{{ $user->name }}">
                            </div>
                            <div class="form-group mt-2">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="{{ $user->email }}">
                            </div>
                            <div class="form-group mt-3">
                                <input type="submit" class="btn btn-primary w-25" value="Save">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="text-custom">Update Password</h4>
                        <form method="post" action="{{ route('password.update') }}">
                            @csrf
                            @method('put')
                            <div class="form-group">
                                <label class="form-label">Current Password
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="password" class="form-control" name="current_password">
                                <div class="mt-2 text-danger">
                                    @if ($errors->updatePassword->has('current_password'))
                                        <span>{{ $errors->updatePassword->first('current_password') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group ">
                                <label class="form-label">New Password
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="password" class="form-control" name="password">
                                <div class="mt-2 text-danger">
                                    @if ($errors->updatePassword->has('password'))
                                        <span>{{ $errors->updatePassword->first('password') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Confirm Password
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="password" class="form-control" name="password_confirmation" id="password">
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group mt-3">
                                        <input type="submit" class="btn btn-primary" value="Change Password">
                                    </div>
                                </div>
                                <div class="col-6 d-flex justify-content-end align-items-center">
                                    <label for="checkbox" class="form-check user-select-none">Show Password
                                        <input type="checkbox" class="form-check-input" value="remember_me"
                                            name="remember-me" id="checkbox">
                                        <span class="form-check-label" id="#"></span>
                                    </label>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
