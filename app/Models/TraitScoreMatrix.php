<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TraitScoreMatrix extends Model
{
    use HasFactory;

    protected $fillable = [
        'trait_id',
        'min_score',
        'max_score',
        'interpretation_id',
    ];

    /**
     * A score matrix entry belongs to a specific Trait.
     */
    public function trait(): BelongsTo
    {
        // Using TraitModel since 'Trait' is a reserved keyword in PHP
        return $this->belongsTo(TraitModel::class);
    }

    /**
     * A score matrix entry is linked to a specific Interpretation.
     */
    public function interpretation(): BelongsTo
    {
        return $this->belongsTo(Interpretation::class);
    }
}
