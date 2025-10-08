<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InterpretationType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * An InterpretationType has many associated Interpretation records.
     */
    public function interpretations(): HasMany
    {
        return $this->hasMany(Interpretation::class);
    }
}
