<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangTemplate extends Model
{
    use HasFactory;
    protected $table = 'barang_template';

    protected $fillable = [
        'user_id',
        'nama_template',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(BarangTemplateDetail::class);
    }
}
