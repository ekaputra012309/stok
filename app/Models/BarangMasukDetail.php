<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasukDetail extends Model
{
    use HasFactory;
    protected $table = 'barang_masuk_details';
    // Define fillable fields for mass assignment
    protected $fillable = [
        'barang_masuk_id',
        'barang_id',
        'qty',
        'qty_verified',
    ];

    /**
     * Get the barang masuk (header) record that owns this detail.
     */
    public function barangMasuk()
    {
        return $this->belongsTo(BarangMasuk::class);
    }

    /**
     * Get the barang (item) associated with this detail.
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
