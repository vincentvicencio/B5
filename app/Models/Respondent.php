<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Respondent extends Model
{
    use HasFactory;

    protected $primaryKey = 'respondent_id';

    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'email',
        'phone_number',
    ];

    // 🔗 Relationship to assessments table
   public function assessments()
{
    return $this->hasMany(\App\Models\Assessment::class, 'respondent_id', 'respondent_id');
}
}
