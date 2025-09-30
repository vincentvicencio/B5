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
        'subtrait_id',
    ];

    /**
     * A Question belongs to a single SubTrait.
     */
    public function subTrait(): BelongsTo
    {
        return $this->belongsTo(SubTrait::class);
    }
}
