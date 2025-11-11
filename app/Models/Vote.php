<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vote extends Model
{
    protected $fillable = [
        "candidate_id",
        'full_name',
        'phone_number',
        'vote_number',
        'payment_reference',
        'payment_status',
        'amount',
        'payment_status',
      
    ];

    public function candidate() : BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

}


