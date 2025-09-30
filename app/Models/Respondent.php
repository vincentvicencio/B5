<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Respondent extends Model
{
    use HasFactory;

    // Define the custom primary key
    protected $primaryKey = 'respondent_id';

    // Specify the attributes that are mass assignable
    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'email',
        'phone_number',
        'type',
    ];
}