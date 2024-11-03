<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    use HasFactory;
    protected $table = 'satuan';

    // Define the fillable fields
    protected $fillable = [
        'user_id',
        'name',
    ];

    // Define the relationship with Barang
    public function barangs()
    {
        return $this->hasMany(Barang::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
