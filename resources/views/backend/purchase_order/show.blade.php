@php
    $role = App\Models\Privilage::getRoleKodeForAuthenticatedUser();
@endphp

@extends('backend.template.app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Purchase Order Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('purchase_order.index') }}">Purchase Orders</a></li>
                        <li class="breadcrumb-item active">Details</li>
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
                            <h3 class="card-title">Invoice: {{ $PurchaseOrder->invoice_number }}</h3>
                        </div>

                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Invoice Number</th>
                                    <td>{{ $PurchaseOrder->invoice_number }}</td>
                                </tr>
                                <tr>
                                    <th>User</th>
                                    <td>{{ $PurchaseOrder->user->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal PO</th>
                                    <td>{{ $PurchaseOrder->created_at->translatedFormat('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Status Order</th>
                                    <td>{{ $PurchaseOrder->status_order }}</td>
                                </tr>
                            </table>

                            <h4 class="mt-4">Items</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered mt-3">
                                    <thead>
                                        <tr>
                                            <th>Part Number</th>
                                            <th>Barang</th>
                                            <th>Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($PurchaseOrder->items as $item)
                                            <tr>
                                                <td><strong>({{ $item->barang->part_number }})</strong></td>
                                                <td> {{ $item->barang->deskripsi }}</td>
                                                <td>{{ $item->qty .' '.$item->barang->satuan->name }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2" class="text-right">Total</th>
                                            <th>{{ number_format($PurchaseOrder->items->sum(fn($item) => $item->qty), 0, ',', '.') }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            @if (in_array($role, ['superadmin', 'owner']))
                            <h4 class="mt-4">Approval</h4>
                            <form action="{{ route('purchase_order.approve', $PurchaseOrder->id) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="status_order">Approval Status</label>
                                    <select name="status_order" id="status_order" class="form-control" required>
                                        <option value="" disabled selected>Select Approval Status</option>
                                        <option value="Approved" {{ $PurchaseOrder->status_order === 'Approved' ? 'selected' : '' }}>Approve</option>
                                        <option value="Rejected" {{ $PurchaseOrder->status_order === 'Rejected' ? 'selected' : '' }}>Not Approve</option>
                                    </select>
                                </div>

                                <div class="form-group" id="note-container" style="{{ $PurchaseOrder->status_order === 'Rejected' ? '' : 'display: none;' }}">
                                    <label for="note">Note</label>
                                    <textarea class="form-control" name="note" id="note" rows="3" placeholder="Enter a note if not approved">{{ old('note', $PurchaseOrder->note) }}</textarea>
                                </div>

                                <button type="submit" class="btn btn-primary">Submit Approval</button>
                            </form>

                            <script>
                                document.getElementById('status_order').addEventListener('change', function() {
                                    const noteContainer = document.getElementById('note-container');
                                    const noteInput = document.getElementById('note');
                                    
                                    if (this.value === 'Rejected') {
                                        noteContainer.style.display = 'block';
                                    } else if (this.value === 'Approved') {
                                        noteContainer.style.display = 'none';
                                        noteInput.value = '-'; // Set note to "Approved" when approved
                                    } else {
                                        noteContainer.style.display = 'none';
                                        noteInput.value = ''; // Clear note input if no option selected
                                    }
                                });
                            </script>
                            @endif
                        </div>

                        <div class="card-footer">
                            <a href="{{ route('purchase_order.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
