<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollUser extends Model
{
    use HasFactory;

    // Specify the table if it's not the plural form of the model name
    protected $table = 'poll_user';

    // Fillable attributes
    protected $fillable = ['poll_id', 'user_id', 'points'];

    // Define relationships
    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
