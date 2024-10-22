<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDateAvailability extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'proposed_date_id', 'is_available'];

    // Define relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define relationship to ProposedDate
    public function proposedDate()
    {
        return $this->belongsTo(ProposedDate::class);
    }
}