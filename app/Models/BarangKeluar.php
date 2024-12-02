<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    use HasFactory;
    protected $table = 'barang_keluar';

    protected $fillable = [
        'user_id',
        'invoice_number',
        'po_number',
        'customer_id',
        'tanggal_keluar',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function details()
    {
        return $this->hasMany(BarangKeluarDetail::class, 'barang_keluar_id');
    }
}
