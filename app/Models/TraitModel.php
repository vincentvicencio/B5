<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TraitModel extends Model // Renamed to TraitModel to avoid conflict with PHP reserved keyword 'Trait'
{
    use HasFactory;
    
    // Explicitly set the table name since the model name is TraitModel
    protected $table = 'traits';

    protected $fillable = [
        'title',
        'description',
        'trait_display_color',
        'max_raw_score',
    ];

    /**
     * A Trait can have many SubTraits.
     */
    public function subTraits(): HasMany
    {
        return $this->hasMany(SubTrait::class);
    }
}
