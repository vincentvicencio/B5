<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Interpretation extends Model
{
    use HasFactory;

    protected $fillable = [
        'interpretation_type_id', // <-- NEW: Added the foreign key here
        'trait_level',
        'interpretation',
    ];

    /**
     * An Interpretation belongs to an InterpretationType (Trait or Sub-Trait scale).
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(InterpretationType::class, 'interpretation_type_id');
    }

    /**
     * An Interpretation can be used in many TraitScoreMatrix entries.
     */
    public function traitScoreMatrices(): HasMany
    {
        return $this->hasMany(TraitScoreMatrix::class);
    }

    /**
     * An Interpretation can be used in many SubTraitScoreMatrix entries.
     */
    public function subTraitScoreMatrices(): HasMany
    {
        return $this->hasMany(SubTraitScoreMatrix::class);
    }
}
