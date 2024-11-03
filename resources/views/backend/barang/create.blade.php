@extends('backend/template/app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Satuan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <!-- <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li> -->
                        <li class="breadcrumb-item"><a href="{{ route('satuan.index') }}">Satuan</a></li>
                        <li class="breadcrumb-item active">Add</li>
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
                            <h3 class="card-title">Tambah Satuan</h3>
                            {{-- <div class="card-tools">
                                    <a href="{{ route('satuan.add') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add Data
                            </a>
                        </div> --}}
                    </div>
                    <form action="{{ route('satuan.store') }}" method="POST" id="satuan-form">
                        @csrf
                        @auth
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        @endauth

                        <div class="card-body">
                            <div class="form-group col-md-4">
                                <label for="name">Nama Satuan</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Nama Satuan" required>
                            </div>                            
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="reset" class="btn btn-danger">Reset</button>
                            <a href="{{ route('satuan.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
@endsection