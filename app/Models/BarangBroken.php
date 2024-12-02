<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangBroken extends Model
{
    use HasFactory;
    protected $table = 'barang_broken';

    protected $fillable = [
        'user_id',
        'invoice_number',
        'tanggal_broken',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(BarangBrokenDetail::class, 'barang_broken_id');
    }
}
