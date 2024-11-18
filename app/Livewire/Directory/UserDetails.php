<?php

namespace App\Livewire\Directory;

use Livewire\Component;

use App\Models\User;
class UserDetails extends Component
{
    public $userId;

    public function addFriend($userId){
        auth()->user()->friends()->attach($userId);
    }

    public function render()
    {
        $user = User::findOrFail($this->userId);
        return view('livewire.directory.user-details',[
            'user' => $user
        ]);
    }
}
