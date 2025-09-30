<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TraitScore extends Model
{
    use HasFactory;

    protected $table = 'trait_scores';
    protected $primaryKey = 'traitscore_id'; // Explicitly define the primary key name

    protected $fillable = [
        'assessment_id',
        'trait_id',
        'score_pct',
        'interpretation',
    ];

    /**
     * The TraitScore belongs to a Trait.
     */
    public function trait(): BelongsTo
    {
        return $this->belongsTo(TraitModel::class, 'trait_id', 'trait_id');
    }

    /**
     * The TraitScore belongs to an Assessment.
     */
    public function assessment(): BelongsTo
    {
        // Assuming the Assessment model uses the standard 'id' as its primary key
        return $this->belongsTo(Assessment::class, 'assessment_id', 'id');
    }
}
