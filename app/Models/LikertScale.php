<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LikertScale extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'label',
    ];

    // Assuming no direct relationships are needed for LikertScale based on the diagram, 
    // it likely serves as a lookup table.
}
