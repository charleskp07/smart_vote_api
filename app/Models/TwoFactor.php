<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TwoFactor extends Model
{
    protected $fillable = [
        'email',
        'code',
    ];

}
