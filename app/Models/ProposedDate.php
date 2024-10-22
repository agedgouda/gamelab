<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposedDate extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'date_time']; // Updated to include event_id

    public function event()
    {
        return $this->belongsTo(Event::class); // Relationship with Event
    }

    public function availabilities()
    {
        return $this->hasMany(UserDateAvailability::class);
    }

}
