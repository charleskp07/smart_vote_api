<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vote extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "candidate_id",
        'full_name',
        'phone_number',
        'vote_number',
        'payment_reference',
        'payment_status',
        'amount',
    ];

    public function candidate() : BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

}


