<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    public function polls()
    {
        return $this->belongsToMany(Poll::class, 'poll_user')->withPivot('points')->withTimestamps();
    }

    public function availabilities()
    {
        return $this->hasMany(UserDateAvailability::class);
    }

    public function invitees()
    {
        return $this->hasMany(Invitee::class, 'email', 'email');
    }
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function games()
    {
        return $this->belongsToMany(Game::class);
    }

    public function friends()
    {
        return $this->belongsToMany(User::class, 'friend_user', 'user_id', 'friend_id')
                    ->withTimestamps();
    }

    public function friendOf()
    {
        return $this->belongsToMany(User::class, 'friend_user', 'friend_id', 'user_id')
                    ->withTimestamps();
    }
}
