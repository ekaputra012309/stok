<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Privilage extends Model
{
    use HasFactory;
    protected $table = 'privilages';
    protected $fillable = ['role_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public static function getRoleNameForAuthenticatedUser()
    {
        $privilage = self::where('user_id', Auth::id())->first();
        return $privilage ? $privilage->role->nama_role : null;
    }

    public static function getRoleKodeForAuthenticatedUser()
    {
        $privilage = self::where('user_id', Auth::id())->first();
        return $privilage ? $privilage->role->kode_role : null;
    }
}
