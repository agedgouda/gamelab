<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\Game;

class GameList extends Component
{
    public $games; 
    public $game;

    public function mount()
    {
        $this->loadGames();
    }

    public function loadGames()
    {
        // Reload the games directly from the database to ensure reactivity
        $this->games = Auth::user()->games()->get();
    }

    public function removeGame($gameId)
    {
        Auth::user()->games()->detach($gameId);
        $this->loadGames(); // Reload games after detaching
    }

    public function render()
    {
        return view('livewire.profile.game-list');
    }

    #[On('select-game')]
    public function selectGame($gameId)
    {
        if (!Auth::user()->games()->where('game_id', $gameId)->exists()) {
            Auth::user()->games()->attach($gameId);
        }

        $this->loadGames(); // Reload games after attaching
    }
}
