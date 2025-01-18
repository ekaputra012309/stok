<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customers';

    // Define the fillable fields
    protected $fillable = [
        'user_id',
        'name',
        'alamat',
        'phone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
