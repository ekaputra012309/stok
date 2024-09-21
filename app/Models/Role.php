<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $table = 'roles';
    // Define fillable columns
    protected $fillable = [
        'kode_role',
        'nama_role',
        // Add other columns as needed
    ];
}
