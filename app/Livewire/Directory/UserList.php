<?php

namespace App\Livewire\Directory;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\User;

class UserList extends Component
{
    use WithPagination;

    public $search = ''; 

    public function render()
    {
        $users = User::paginate(25);

        $friends = auth()->user()->friends->pluck('id');
        $friendOf = auth()->user()->friendOf->pluck('id');

        return view('livewire.directory.user-list',[
            'users' => $users,
            'friends' => $friends,
            'friendOf' => $friendOf,
            'search' => $this->search
        ]);

    }
}
