<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        'host',
        'user_token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
