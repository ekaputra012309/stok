@extends('backend/template/app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Barang Limit</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('barang_limit.index') }}">Barang Limit</a></li>
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
                            <h3 class="card-title">Tambah Barang Limit</h3>
                        </div>

                        <form action="{{ route('barang_limit.store') }}" method="POST" id="barang-masuk-form">
                            @csrf
                            @auth
                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                            @endauth

                            <div class="card-body">
                                <!-- Container for Dynamic Barang Items -->
                                <div id="items-container">
                                    <div class="item-row row">
                                        <div class="form-group col-md-2">
                                            <label for="barang_id">Barang</label>
                                            <select class="form-control select2bs4 barang-select" name="barang_id" required>
                                                <option value="" data-deskripsi="" disabled selected>Select Part Number</option>
                                                @foreach ($barangs as $barang)
                                                    <option value="{{ $barang->id }}" data-deskripsi="{{ $barang->deskripsi }}">{{ $barang->part_number }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="deskripsi">Deskripsi</label>
                                            <input type="text" class="form-control deskripsi-input" name="deskripsi_display" placeholder="Deskripsi" readonly>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="qtyLimit">Qty limit</label>
                                            <input type="number" class="form-control qty-input @error('qtyLimit') is-invalid @enderror" name="qtyLimit" placeholder="Quantity" required min="1">
                                            @error('qtyLimit')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="reset" class="btn btn-danger">Reset</button>
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back</a>
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

            $(document).on('change', '.barang-select', function() {
                const deskripsi = $(this).find('option:selected').data('deskripsi');
                $(this).closest('.item-row').find('.deskripsi-input').val(deskripsi);
            });
        });
    </script>

</div>
@endsection
