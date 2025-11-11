<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Competition extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'start_date',
        'end_date',
        'vote_value',
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class);
    }

}
