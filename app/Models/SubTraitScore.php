<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubTraitScore extends Model
{
    use HasFactory;

    protected $table = 'sub_trait_scores';
    protected $primaryKey = 'subtraitscore_id'; // Explicitly define the primary key name

    protected $fillable = [
        'assessment_id',
        'sub_trait_id',
        'score_pct',
        'interpretation',
    ];

    /**
     * The SubTraitScore belongs to a SubTrait.
     */
    public function subTrait(): BelongsTo
    {
        return $this->belongsTo(SubTrait::class, 'sub_trait_id', 'subtrait_id');
    }

    /**
     * The SubTraitScore belongs to an Assessment.
     */
    public function assessment(): BelongsTo
    {
        // Assuming the Assessment model uses the standard 'id' as its primary key
        return $this->belongsTo(Assessment::class, 'assessment_id', 'id');
    }
}
