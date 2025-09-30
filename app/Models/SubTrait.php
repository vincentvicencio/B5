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
        // Using TraitModel since 'Trait' is a reserved keyword in PHP
        return $this->belongsTo(TraitModel::class);
    }

    /**
     * A SubTrait can have many Questions.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}
