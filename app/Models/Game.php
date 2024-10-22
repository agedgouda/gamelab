<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'name',
        'bgg_id',
    ];

    public function polls()
    {
        return $this->morphMany(Poll::class, 'pollable'); 
    }
}
