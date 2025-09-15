<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory;
    protected $table = 'barang_masuk';
    // Define fillable fields for mass assignment
    protected $fillable = [
        'purchase_order_id',
        'user_id',
        'tanggal_masuk',
        'note',
    ];

    /**
     * Get the details for this barang masuk record.
     */
    public function details()
    {
        return $this->hasMany(BarangMasukDetail::class, 'barang_masuk_id');
    }

    /**
     * Get the purchase order associated with this barang masuk.
     */
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id', 'id');
    }

    /**
     * Get the user who processed this barang masuk.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
