<?php
// app/Models/Admin.php
// UPDATE model yang sudah ada — tambah name, email, is_active ke fillable

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table      = 'admins';
    protected $primaryKey = 'admin_id';

    protected $fillable = [
        'name',       // ← tambahan baru
        'username',
        'email',      // ← tambahan baru
        'password',
        'role',
        'is_active',  // ← tambahan baru
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
