<?php

namespace App\Livewire\Directory;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserDetails extends Component
{
    use WithPagination;

    public $userId;
    public $user;

    public function mount($userId)
    {
        $this->userId = $userId;
        $this->user = User::findOrFail($this->userId);
    }

    public function addFriend()
    {
        $authUser = Auth::user();
        if (!$authUser->friends()->where('id', $this->userId)->exists()) {
            $authUser->friends()->attach($this->userId);
        }
    }

    public function removeFriend()
    {
        $authUser = Auth::user();
        if ($authUser->friends()->where('id', $this->userId)->exists()) {
            $authUser->friends()->detach($this->userId);
        }
    }

    public function render()
    {
        return view('livewire.directory.user-details', [
            'events' => $this->user->events()->paginate(5),
        ]);
    }
}
