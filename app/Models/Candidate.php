<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidate extends Model
{
    protected $fillable = [
        'competition_id',
        'photo',
        'first_name',
        'last_name',
        'gender',
        'birth_date',
        'height',
        'weight',
        'nationality',
        'description',
    ];

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }
}
