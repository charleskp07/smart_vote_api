<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Candidate extends Model
{
    use SoftDeletes;

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
        'accumulated_vote',
    ];

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }
}
