<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\BoardGameGeekService;


class Game extends Model
{
    protected $fillable = [
        'name',
        'bgg_id',
        'thumbnail',
    ];

    protected $BggDataService;

    protected static function boot()
    {
        parent::boot();

        static::retrieved(function (Game $game) {
            $game->checkAndSetThumbnail();
        });

        static::creating(function (Game $game) {
            $game->checkAndSetThumbnail();
        });
    }


    public function polls()
    {
        return $this->morphMany(Poll::class, 'pollable'); 
    }

    public function posts()
    {
        return $this->morphMany(Poll::class, 'pollable'); 
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    private function checkAndSetThumbnail()
    {
        if (empty($this->thumbnail) && $this->bgg_id) {
            $bggData = app(BoardGameGeekService::class)->fetchGameData($this->bgg_id);

            if (!empty($bggData['thumbnail'])) {
                $this->thumbnail = $bggData['thumbnail'];
                $this->save(); // Persist to database
            }
        }
    }

}
