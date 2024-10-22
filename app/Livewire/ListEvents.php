<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;

class ListEvents extends Component
{
    public $events = [];
    public $eventId;

    public function setSelectedEvent($eventId)
    {
        $this->eventId = $eventId;
    }

    public function render()
    {
        $this->events = Event::with(['game', 'proposedDates'])
        ->whereHas('proposedDates', function ($query) {
            $query->where('date_time', '>=', now());
        })
        ->whereDoesntHave('proposedDates', function ($query) {
            $query->where('date_time', '<', now());
            })
        ->get();

        return view('livewire.list-events');
    }
}
