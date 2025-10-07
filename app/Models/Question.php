<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_text',
        // FIX: The database column is likely named 'subtrait_id' (one word), 
        // which caused the 'Unknown column sub_trait_id' error.
        'subtrait_id', 
    ];

    /**
     * A Question belongs to a single SubTrait.
     */
    public function subTrait(): BelongsTo
    {
        // Explicitly defining the foreign key to match the database column name
        return $this->belongsTo(SubTrait::class, 'subtrait_id');
    }
}
