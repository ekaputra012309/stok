<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;
    protected $table = 'purchase_order';

    protected $fillable = [
        'user_id',
        'invoice_number',
        'vendor',
        'status_order',
        'approveby',
        'note',
    ];

    public function user1()
    {
        return $this->belongsTo(User::class, 'approveby', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}
