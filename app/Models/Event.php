<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['game_id', 'title', 'location', 'description','user_id'];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function proposedDates()
    {
        return $this->hasMany(ProposedDate::class);
    }

    public function invitees()
    {
        return $this->hasMany(Invitee::class);
    }

    public function selectedDate()
    {
        return $this->belongsTo(ProposedDate::class, 'date_selected_id');
    }

    public function posts()
    {
        return $this->morphMany(Poll::class, 'pollable'); 
    }
}

