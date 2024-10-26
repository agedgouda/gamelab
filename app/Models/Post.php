<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'content',
        'parent_post_id',
        'postable_type',
        'postable_id',

    ];

    /**
     * Get the parent postable model (Game or Event).
     */
    public function postable()
    {
        return $this->morphTo();
    }

    public function polls()
    {
        return $this->morphMany(Poll::class, 'pollable'); 
    }

    /**
     * Get the user that created the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the responses (comments) for this post.
     */
    public function responses()
    {
        return $this->hasMany(Post::class, 'parent_post_id');
    }
}
