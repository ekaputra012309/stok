<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;
    protected $table = 'barang';

    // Define the fillable fields
    protected $fillable = [
        'user_id',
        'deskripsi',
        'part_number',
        'stok',
        'limit',
        'lokasi_id',
        'satuan_id',
    ];

    // Define the relationship with Satuan
    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }

    // Optionally, define the relationship with User if needed
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
