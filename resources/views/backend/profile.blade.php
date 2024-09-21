@extends('backend/template/app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Profile</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            {{-- <li class="breadcrumb-item"><a href="#">Layout</a></li> --}}
                            <li class="breadcrumb-item active">Profile</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">

                        <div class="card p-3">
                            <section>
                                <header>
                                    <h2 class="text-lg font-medium text-gray-900">
                                        Profile Information
                                    </h2>

                                    <p class="mt-1 text-sm text-gray-600">
                                        Update your account's profile information and email address.
                                    </p>
                                </header>

                                <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                                    @csrf
                                </form>

                                <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                                    @csrf
                                    @method('patch')

                                    <div class="form-group">
                                        <label for="name">Nama Lengkap</label>
                                        <input id="name" name="name" type="text" class="form-control"
                                            placeholder="Nama Lengkap" value="{{ $user->name }}" required autofocus
                                            autocomplete="name">
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" id="email" class="form-control"
                                            value="{{ $user->email }}" required autocomplete="username">

                                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                            <div>
                                                <p class="text-sm mt-2 text-gray-800">
                                                    {{ __('Your email address is unverified.') }}

                                                    <button form="send-verification"
                                                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                        {{ __('Click here to re-send the verification email.') }}
                                                    </button>
                                                </p>

                                                @if (session('status') === 'verification-link-sent')
                                                    <p class="mt-2 font-medium text-sm text-green-600">
                                                        {{ __('A new verification link has been sent to your email address.') }}
                                                    </p>
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-4">
                                        <button type="submit" class="btn btn-primary">Save</button>

                                        @if (session('status') === 'profile-updated')
                                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                                                class="text-sm text-gray-600">{{ __('Saved.') }}</p>
                                        @endif
                                    </div>
                                </form>
                            </section>
                        </div>

                        <div class="card p-3">
                            <section>
                                <header>
                                    <h2 class="text-lg font-medium text-gray-900">
                                        Update Password
                                    </h2>

                                    <p class="mt-1 text-sm text-gray-600">
                                        Ensure your account is using a long, random password to stay secure.
                                    </p>
                                </header>

                                <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                                    @csrf
                                    @method('put')

                                    <div class="form-group">
                                        <label for="update_password_current_password">Current Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control"
                                                id="update_password_current_password"
                                                name="update_password_current_password" placeholder="Current Password"
                                                autocomplete="current-password">
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="fas fa-eye" id="toggle-current-password"></i>
                                                </span>
                                            </div>
                                        </div>
                                        @error('update_password_current_password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="update_password_password">New Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="update_password_password"
                                                name="password" placeholder="New Password" autocomplete="new-password">
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="fas fa-eye" id="toggle-new-password"></i>
                                                </span>
                                            </div>
                                        </div>
                                        @error('password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="update_password_password_confirmation">Confirm Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control"
                                                id="update_password_password_confirmation" name="password_confirmation"
                                                placeholder="Confirm Password" autocomplete="new-password">
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="fas fa-eye" id="toggle-confirm-password"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-4">
                                        <button type="submit" class="btn btn-primary">Save</button>

                                        @if (session('status') === 'password-updated')
                                            <p x-data="{ show: true }" x-show="show" x-transition
                                                x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">{{ __('Saved.') }}
                                            </p>
                                        @endif
                                    </div>
                                </form>

                            </section>
                        </div>
                        <script>
                            $(document).ready(function() {
                                // Function to toggle password visibility
                                function togglePasswordVisibility(inputId, toggleId) {
                                    $(`#${toggleId}`).click(function() {
                                        const passwordField = $(`#${inputId}`);
                                        const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
                                        passwordField.attr('type', type);
                                        $(this).toggleClass('fa-eye fa-eye-slash');
                                    });
                                }

                                // Apply the function to each password field
                                togglePasswordVisibility('update_password_current_password', 'toggle-current-password');
                                togglePasswordVisibility('update_password_password', 'toggle-new-password');
                                togglePasswordVisibility('update_password_password_confirmation', 'toggle-confirm-password');
                            });
                        </script>

                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection