<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TraitModel extends Model
{
    use HasFactory;
    
    protected $table = 'traits';

    protected $fillable = [
        'title',
        'description',
        'trait_display_color',
        'max_raw_score', 
    ];

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($trait) {
            $trait->subTraits->each(function ($subTrait) {
                 $subTrait->delete();
            });
        });
    }

    public function subTraits(): HasMany
    {
        // This handles N SubTraits per Trait
        return $this->hasMany(SubTrait::class, 'trait_id');
    }
}
