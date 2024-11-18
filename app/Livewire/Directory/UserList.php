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


        return view('livewire.directory.user-list',[
            'users' => $users,
            'search' => $this->search
        ]);

    }
}
