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

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($subTrait) {
            // Retrieve all associated questions and delete them one by one.
            // This is more reliable than $subTrait->questions()->delete() for cascade chains.
            $subTrait->questions->each(function ($question) {
                $question->delete();
            });
        });
    }

    /**
     * A SubTrait belongs to a single Trait.
     */
    public function trait(): BelongsTo
    {
        // Explicitly defining the foreign key 'trait_id'
        return $this->belongsTo(TraitModel::class, 'trait_id');
    }

    public function questions(): HasMany
    {
        // Explicitly define the foreign key as 'subtrait_id' 
        return $this->hasMany(Question::class, 'subtrait_id');
    }
}
