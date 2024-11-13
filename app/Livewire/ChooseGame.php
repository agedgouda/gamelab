<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Game;


class ChooseGame extends Component
{
    public $game;
    public $games = [];
    public $search = '';
    public $userId;

    public function mount($userId = null) {
        $this->userId = $userId;
    }
  
    public function updatedSearch()
    {
        if($this->search == '') {
            $this->resetSearch(); 
        }
        else {
            $this->games = Game::where('name', 'like', '%' . $this->search . '%')
            ->when($this->userId, function ($query) {
                $query->whereDoesntHave('users', function ($q) {
                    $q->where('user_id', $this->userId);
                });
            })
            ->limit(10)
            ->get();
        }
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
