<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Game;
use App\Models\User;
use App\Models\Poll;
use App\Models\PollUser;

class PollComponent extends Component
{
    public $pollableType;
    public $pollableId;
    public $score;

    public function render()
    {
        $this->score = PollUser::where('poll_id', function ($query) {
            $query->select('id')
                  ->from('polls')
                  ->where('pollable_id', $this->pollableId)
                  ->where('pollable_type', 'like', '%'.$this->pollableType);
        })
        ->avg('points');
        return view('livewire.poll-component');
    }

    public function enterPoll($points)
    {
        if (!auth()->check()) {
            session()->flash('message', 'You must be logged in to do that.');
            session()->flash('route', url()->previous());
            return redirect()->route('login');
        }

        $userId = auth()->id(); // Assuming user is authenticated

        // Determine the full model class based on the type passed ('game' or 'post')
        $this->pollableClass = $this->pollableType === 'game' ? Game::class : Post::class;

        // Find or create the poll for the specific game or post
        $poll = Poll::firstOrCreate([
            'pollable_type' => $this->pollableClass,
            'pollable_id' => $this->pollableId,
        ]);

        // Check if the user has already voted in this poll
        $pollUser = PollUser::where('poll_id', $poll->id)
                            ->where('user_id', $userId)
                            ->first();

        if ($pollUser) {
            // Update the points if the user already voted
            $pollUser->update(['points' => $points]);
        } else {
            // Create a new poll_user entry if it's the first time voting
            PollUser::create([
                'poll_id' => $poll->id,
                'user_id' => $userId,
                'points' => $points,
            ]);
        }
    }

}
