<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitee extends Model
{
    protected $fillable = ['name', 'email', 'message_status', 'event_id'];


    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
    public function event()
    {
        return $this->belongsTo(event::class);
    }
}
