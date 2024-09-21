@extends('backend/template/app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Privilage</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('privilage.index') }}">Privilage</a></li>
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
                            <h3 class="card-title">Edit Privilage</h3>
                        </div>
                        <form action="{{ route('privilage.update', $privilage->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            @auth
                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                            @endauth

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8 col-12">
                                        <div class="form-group mandatory">
                                            <label for="nama-user-column" class="form-label">Nama User</label>
                                            <select name="user_id" id="user_id" class="form-control select2bs4">
                                                <option value="">Pilih</option>
                                                @foreach ($datauser as $usr)
                                                <option value="{{ $usr->id }}" {{ $usr->id == $privilage->user_id ? 'selected' : '' }}>{{ $usr->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group mandatory">
                                            <label for="role-id-column" class="form-label">Role</label>
                                            <select name="role_id" id="role_id" class="form-control select2bs4">
                                                <option value="">Pilih</option>
                                                @foreach ($datarole as $rl)
                                                <option value="{{ $rl->id }}" {{ $rl->id == $privilage->role_id ? 'selected' : '' }}>{{ $rl->nama_role }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href="{{ route('privilage.index') }}" class="btn btn-secondary">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        $(document).ready(function() {
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });
        });
    </script>
</div>
@endsection