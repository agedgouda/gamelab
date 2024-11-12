<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Game;


class ChooseGame extends Component
{
    public $game;
    public $games = [];
    public $search = '';
    
    public function updatedSearch()
    {
        $this->games = Game::where('name', 'like', '%' . $this->search . '%')
            ->limit(10)
            ->get();
    }

    public function selectGame($gameId)
    {
        $this->dispatch('select-game', gameId: $gameId  );
        $this->resetSearch();
    }

    public function resetSearch()
    {
        $this->search = '';
        $this->games = [];
    }

    public function render()
    {
        return view('livewire.choose-game');
    }
}
