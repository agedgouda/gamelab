<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    protected $fillable = [
        'pollable_type',
        'pollable_id',
    ];

    public function pollable()
    {
        return $this->morphTo();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'poll_user')
                    ->withPivot('points');
    }
}
