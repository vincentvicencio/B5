<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assessment extends Model
{
    // Override default primary key
    protected $primaryKey = 'assessment_id';

    // Since Laravel assumes 'id' as the key, we specify the key type.
    protected $keyType = 'int';

    protected $fillable = [
        'respondent_id',
        'date_completed',
        'overall_score',
        'interpretation',
        'all_response',
    ];

    protected $casts = [
        'date_completed' => 'datetime',
        'all_response' => 'json',
        'overall_score' => 'decimal:2',
    ];

    /**
     * An assessment has many trait scores.
     */
    public function traitScores(): HasMany
    {
        return $this->hasMany(TraitScore::class, 'assessment_id', 'assessment_id');
    }

    /**
     * An assessment has many subtrait scores.
     */
    public function subTraitScores(): HasMany
    {
        return $this->hasMany(SubTraitScore::class, 'assessment_id', 'assessment_id');
    }

    /**
     * An assessment belongs to a respondent. (Assuming a Respondent model exists)
     */
    public function respondent(): BelongsTo
    {
        return $this->belongsTo(Respondent::class, 'respondent_id');
    }
}
