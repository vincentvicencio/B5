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

    // --- Database Variable Information ---
    // You will need to ensure your database connection is configured in the .env file.
    // The columns in your 'admins' table should match the 'fillable' array above.
    // A migration file would look something like this:
    /*
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('employee_code')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('updated_by')->nullable();
            $table->timestamps(); // creates 'created_at' and 'updated_at' columns
        });
    */
}