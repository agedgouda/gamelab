<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Game;

class CreateGame extends Component
{
    public $name = '';
    public $bggId;
    
    public function createGame () {

        Game::create([
            'name' => $this->name,
            'bgg_id' => $this->bggId,
        ]);
        return redirect()->to('/create-game');
    }
    public function render()
    {
        return view('livewire.create-game')
        ->extends('layouts.app')
        ->section('content');
    }
}
