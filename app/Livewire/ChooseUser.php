<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class ChooseUser extends Component
{
    public $userId;    
    public $user;
    public $users = [];
    public $search = '';

    public function mount($userId = null) {
        $this->userId = $userId;
    }

    public function updatedSearch()
    {
        if($this->search == '') {
            $this->resetSearch(); 
        }
        else {
            $this->users = User::where('name', 'like', '%' . $this->search . '%')
            ->when($this->userId, function ($query) {
                return $query->where('id', '!=', $this->userId) // Exclude the user with $this->userId
                    ->whereNotIn('id', function ($query) {
                        $query->select('friend_id')
                            ->from('friend_user')
                            ->where('user_id', $this->userId); // Exclude users that are friends with $this->userId
                    });
            })
            ->limit(10)
            ->get();
        }
    }

    public function selectUser($userId)
    {
        $this->dispatch('select-user', userId: $userId  );
        $this->resetSearch();
    }

    public function resetSearch()
    {
        $this->search = '';
        $this->users = [];
    }
    public function render()
    {
        return view('livewire.choose-user');
    }
}
