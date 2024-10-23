<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['game_id', 'title', 'description'];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function proposedDates()
    {
        return $this->hasMany(ProposedDate::class);
    }

    public function invitees()
    {
        return $this->hasMany(Invitee::class);
    }
}

