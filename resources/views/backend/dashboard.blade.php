@php
    $role = App\Models\Privilage::getRoleKodeForAuthenticatedUser();
@endphp

@extends('backend/template/app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <!-- You can add breadcrumb links here if needed -->
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">            
            <!-- <div class="row">
                <div class="col-lg-4">
                    Daily Income Card
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Pendapatan per Hari</h3>
                        </div>
                        <div class="card-body">
                            <div class="info-box">
                                <span class="info-box-icon"><i class="fas fa-calendar-day"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-number">
                                        <h3 class="font-weight-bold">Rp  }}</h3>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    Monthly Income Card
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Pendapatan per Bulan</h3>
                        </div>
                        <div class="card-body">
                            <div class="info-box">
                                <span class="info-box-icon"><i class="fas fa-calendar-week"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-number">
                                        <h3 class="font-weight-bold">Rp </h3>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    Yearly Income Card
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">Pendapatan per Tahun</h3>
                        </div>
                        <div class="card-body">
                            <div class="info-box">
                                <span class="info-box-icon"><i class="fas fa-calendar-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-number">
                                        <h3 class="font-weight-bold">Rp </h3>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->

            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $barang }}</h3>

                        <p>Item Barang {{$role}}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="{{ $role !== 'superadmin' || $role !== 'admin' ? route('barang.index') : route('dashboard') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $barang_masuk }}</h3>

                        <p>Barang Masuk Today</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="{{ route('barang_masuk.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $barang_keluar }}</h3>

                        <p>Barang Keluar Today</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="{{ route('barang_keluar.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $barang }}</h3>

                        <p>Barang Broken Today</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="{{ route('dashboard') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            
        </div>
    </section>
</div>
@endsection
