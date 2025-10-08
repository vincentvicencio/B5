<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

// NOTE: If you are managing user admin accounts, this model should likely extend
// Illuminate\Foundation\Auth\User and use the Notifiable trait,
// but for simple data management, we'll keep it simple here.

class Admin extends Authenticatable
{
    use HasFactory;

    // Define the table name (optional, if it doesn't follow standard naming)
    protected $table = 'admins';

    // Fields that are mass assignable
    protected $fillable = [
        'username',
        'employee_code',
        'first_name',
        'last_name',
        'email',
        'password',
        'updated_by', // Storing the ID of the user who made the last change
    ];

    // Fields that should be hidden for arrays/API responses.
    protected $hidden = [
        'password',
    ];

    // Fields that should be cast to native types
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }
}