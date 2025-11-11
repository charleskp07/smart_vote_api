<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Competition extends Model
{

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'start_date',
        'end_date',
        'vote_value',

    ];

    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
