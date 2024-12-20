<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangLimit extends Model
{
    use HasFactory;
    protected $table = 'barang_limit';

    protected $fillable = [
        'barang_id',
        'user_id',
        'qtyLimit',
        'status',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
