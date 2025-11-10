<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vote extends Model
{
    protected $fillable = [
        "candidate_id",
        'id',
        'full_name',
        'phone_number',
        'vote_number',
        
    ];

    public function candidate() : BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

}


