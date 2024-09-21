@extends('backend/template/app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit User</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.index') }}">User</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Edit User</h3>
                        </div>
                        <form action="{{ route('user.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" placeholder="Nama Lengkap" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" placeholder="Email" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Kosongkan jika password tidak dirubah">
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="toggle-password">
                                                <i class="fas fa-eye" id="password-icon"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Kosongkan jika password tidak dirubah">
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="toggle-password-confirmation">
                                                <i class="fas fa-eye" id="password-icon-confirmation"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href="{{ route('user.index') }}" class="btn btn-secondary">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        $(document).ready(function() {
            $('#toggle-password').click(function() {
                const passwordField = $('#password');
                const passwordIcon = $('#password-icon');
                const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
                passwordField.attr('type', type);
                passwordIcon.toggleClass('fa-eye fa-eye-slash');
            });

            $('#toggle-password-confirmation').click(function() {
                const passwordConfirmationField = $('#password_confirmation');
                const passwordIconConfirmation = $('#password-icon-confirmation');
                const type = passwordConfirmationField.attr('type') === 'password' ? 'text' : 'password';
                passwordConfirmationField.attr('type', type);
                passwordIconConfirmation.toggleClass('fa-eye fa-eye-slash');
            });

            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });
        });
    </script>
</div>
@endsection