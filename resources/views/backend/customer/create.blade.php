@extends('backend/template/app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Customer</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <!-- <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li> -->
                        <li class="breadcrumb-item"><a href="{{ route('customer.index') }}">customer</a></li>
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
                            <h3 class="card-title">Tambah Customer</h3>
                            {{-- <div class="card-tools">
                                    <a href="{{ route('customer.add') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add Data
                            </a>
                        </div> --}}
                    </div>
                    <form action="{{ route('customer.store') }}" method="POST" id="customer-form">
                        @csrf
                        @auth
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        @endauth

                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="name">Nama Customer</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Nama customer" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="alamat">Alamat</label>
                                    <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Alamat" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="phone">No Telp</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="No Telp" required>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="reset" class="btn btn-danger">Reset</button>
                            <a href="{{ route('customer.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
@endsection