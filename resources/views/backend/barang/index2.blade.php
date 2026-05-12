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
                        <h1>Stok Barang PT. RBI</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <!-- <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li> -->
                            {{-- <li class="breadcrumb-item"><a href="#">Layout</a></li> --}}
                            <li class="breadcrumb-item active">Stok Barang PT. RBI</li>
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

                            <div class="card-body table-responsive">
                                <table id="stokRBI" class="table table-bordered table-striped w-100">
                                    <thead>
                                        <tr>
                                            <th>Part Number</th>
                                            <th>Deskripsi</th>
                                            <th>Lokasi Part</th>
                                            <th>Stok</th>
                                            <th>Limit</th>
                                            <th>Uom</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script>
            $(document).ready(function() {

                $('#stokRBI').DataTable({
                    processing: true,
                    ajax: {
                        url: 'https://rfgims.com/api/barang',
                        type: 'GET',
                        dataSrc: 'data'
                    },
                    columns: [{
                            data: 'part_number'
                        },
                        {
                            data: 'deskripsi'
                        },

                        // relation lokasi
                        {
                            data: 'lokasi',
                            render: function(data, type, row) {
                                return data ? data.nama_lokasi : '-';
                            }
                        },

                        // stok
                        {
                            data: 'stok',
                            defaultContent: 0
                        },

                        // limit
                        {
                            data: 'limit_stok',
                            defaultContent: 0
                        },

                        // relation satuan
                        {
                            data: 'satuan',
                            render: function(data, type, row) {
                                return data ? data.name : '-';
                            }
                        }
                    ],

                    responsive: true,
                    lengthChange: true,
                    autoWidth: false,
                    scrollCollapse: true,
                    paging: true,
                    pageLength: 20
                });

            });
        </script>
    </div>
@endsection
