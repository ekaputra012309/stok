<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangTemplateDetail extends Model
{
    use HasFactory;
    protected $table = 'barang_template_items';

    protected $fillable = [
        'barang_template_id',
        'barang_id',
        'user_id',
        'qty',
    ];

    public function barangTemplate()
    {
        return $this->belongsTo(BarangTemplate::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
