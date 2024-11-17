<?php

namespace App\Livewire;

use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Game;

class ListGames extends Component
{
    use WithPagination;

    public $search = ''; 

    public function render()
    {
        $games = Game::query()
            ->when($this->search, function ($query) {
                return $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        return view('livewire.game.list-games', [
            'games' => $games,
            'search' => $this->search
        ]);       
    }

    public function updatedSearch()
    {
        $this->resetPage(); // Reset pagination when the search term changes
    }
}
