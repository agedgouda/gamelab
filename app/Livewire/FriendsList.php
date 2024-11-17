<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\User;

class FriendsList extends Component
{
    public $friends; 
    public $user;

    public function mount()
    {
        $this->loadFriends();
    }

    public function loadFriends()
    {
        $this->friends = Auth::user()->friends()->get();
    }

    public function removeFriend($friendId)
    {
        Auth::user()->friends()->detach($friendId);
        $this->loadFriends(); // Reload games after detaching
    }

    public function render()
    {
        return view('livewire.profile.friends-list');
    }

    #[On('select-user')]
    public function selectGame($userId)
    {
        if (!Auth::user()->friends()->where('friend_id', $userId)->exists()) {
            Auth::user()->friends()->attach($userId);
        }

        $this->loadFriends(); // Reload games after attaching
    }
}
