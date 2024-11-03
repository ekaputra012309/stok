@extends('backend/template/app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Barang</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <!-- <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li> -->
                        <li class="breadcrumb-item"><a href="{{ route('barang.index') }}">Barang</a></li>
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
                            <h3 class="card-title">Tambah Barang</h3>
                            {{-- <div class="card-tools">
                                    <a href="{{ route('barang.add') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add Data
                            </a>
                        </div> --}}
                    </div>
                    <form action="{{ route('barang.store') }}" method="POST" id="barang-form">
                        @csrf
                        @auth
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        @endauth

                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="part_number">Part Number</label>
                                    <input type="text" class="form-control" id="part_number" name="part_number" placeholder="Part Number" required>
                                </div>
                                <div class="form-group col-md-8">
                                    <label for="deskripsi">Deskripsi</label>
                                    <input type="text" class="form-control" id="deskripsi" name="deskripsi" placeholder="Deskripsi" required>
                                </div>                                
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="satuan_id">UOM</label>
                                    <select class="form-control select2bs4" id="satuan_id" name="satuan_id" required>
                                        @foreach ($satuans as $satuan)
                                            <option value="{{$satuan->id}}">{{$satuan->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>                        
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="reset" class="btn btn-danger">Reset</button>
                            <a href="{{ route('barang.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(function() {
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        });
    });
</script>
</div>
@endsection