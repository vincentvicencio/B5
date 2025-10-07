<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TraitModel extends Model
{
    use HasFactory;
    
    protected $table = 'traits';

    protected $fillable = [
        'title',
        'description',
        'trait_display_color',
        // CRITICAL: Ensure 'max_raw_score' is included for mass assignment
        'max_raw_score', 
    ];

    public function subTraits(): HasMany
    {
        // This handles N SubTraits per Trait
        return $this->hasMany(SubTrait::class, 'trait_id');
    }
}
