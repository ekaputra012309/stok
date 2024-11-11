@extends('backend/template/app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Transaksi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Transaksi</li>
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
                            <h3 class="card-title">Laporan Transaksi</h3>
                        </div>
                        <form action="{{ route('transaksi.laporan.cetak') }}" target="_blank" method="GET">
                            @csrf
                            @auth
                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                            @endauth                            

                            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                <div class="row w-100">
                                    <div class="col-lg-4 col-md-6 col-sm-8 col-12 mx-auto">
                                        <div class="form-group">
                                            <label for="daterange">Tanggal Transaksi</label>
                                        </div>
                                        <div class="form-group">
                                            <button type="button" class="btn btn-default w-100" id="daterange-btn">
                                                <i class="far fa-calendar-alt"></i> Pilih Tanggal
                                            </button>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" id="daterange" class="form-control" readonly >
                                            <input type="hidden" name="startDate" id="startDate" class="form-control" readonly> 
                                            <input type="hidden" name="endDate" id="endDate" class="form-control" readonly>
                                        </div>
                                        <div class="form-group">
                                            <select name="type_transaksi" id="type_transaksi" class="form-control select2bs4" required>
                                                <option value="">Pilih Jenis Transaksi</option>
                                                <option value="barang_masuk">Barang Masuk</option>
                                                <option value="barang_keluar">Barang Keluar</option>
                                                <option value="barang_broken">Barang Broken</option>
                                            </select>
                                        </div>
                                        <div class="form-group text-center">
                                            <button type="submit" class="btn btn-primary w-100" id="printButton" disabled>Print Laporan</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(function () {
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });

            $('#daterange-btn').daterangepicker(
                {
                    ranges   : {
                        'Today'       : [moment(), moment()],
                        'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month'  : [moment().startOf('month'), moment().endOf('month')],
                        'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    startDate: moment().subtract(29, 'days'),
                    endDate  : moment()
                },
                function (start, end) {
                    $('#daterange').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                    $('#startDate').val(start.format('YYYY-MM-DD'));
                    $('#endDate').val(end.format('YYYY-MM-DD'));

                    // Enable the print button if date range is selected
                    if ($('#startDate').val() && $('#endDate').val()) {
                        $('#printButton').prop('disabled', false); // Enable button
                    } else {
                        $('#printButton').prop('disabled', true); // Disable button
                    }
                }
            );

            // Check if date range is already selected on page load
            if ($('#startDate').val() && $('#endDate').val()) {
                $('#printButton').prop('disabled', false); // Enable button if date range is pre-filled
            }
        });
    </script>
</div>
@endsection
