<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangBrokenDetail extends Model
{
    use HasFactory;
    protected $table = 'barang_broken_items';

    protected $fillable = [
        'barang_broken_id',
        'barang_id',
        'user_id',
        'qty',
    ];

    public function barangbroken()
    {
        return $this->belongsTo(BarangBroken::class, 'barang_broken_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
