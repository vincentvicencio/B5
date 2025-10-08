<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubTraitScoreMatrix extends Model
{
    use HasFactory;

    protected $fillable = [
        'subtrait_id',
        'min_score',
        'max_score',
        'interpretation_id',
    ];

    /**
     * A score matrix entry belongs to a specific SubTrait.
     * CRITICAL FIX: Use 'id' instead of 'subtrait_id' as the ownerKey
     */
    public function subTrait(): BelongsTo
    {
        return $this->belongsTo(SubTrait::class, 'subtrait_id', 'id');
    }

    /**
     * A score matrix entry is linked to a specific Interpretation.
     */
    public function interpretation(): BelongsTo
    {
        return $this->belongsTo(Interpretation::class);
    }
}