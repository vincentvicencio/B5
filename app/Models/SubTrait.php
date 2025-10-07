<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubTrait extends Model
{
    use HasFactory;

    protected $fillable = [
        'subtrait_name',
        'trait_id',
        'max_raw_score',
    ];

    /**
     * A SubTrait belongs to a single Trait.
     */
    public function trait(): BelongsTo
    {
        // Explicitly defining the foreign key 'trait_id'
        return $this->belongsTo(TraitModel::class, 'trait_id');
    }

    /**
     * A SubTrait can have many Questions.
     */
    public function questions(): HasMany
    {
        // FIX: Explicitly define the foreign key as 'subtrait_id' 
        // to resolve the "Unknown column 'sub_trait_id'" error.
        return $this->hasMany(Question::class, 'subtrait_id');
    }
}
