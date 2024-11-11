<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Game;
use App\Models\User;
use App\Models\Event;
use App\Models\Invitee;
use App\Models\ProposedDate;
use Carbon\Carbon;

class ScheduleGame extends Component
{
    public $game;
    public $games = [];
    public $search = ''; 
    public $title = ''; 
    public $description = ''; 
    public $location = ''; 
    public $dateTimes = [];
    public $date;
    public $time;
    public $isEditMode = false;
    public $eventId = null;
    public $selectedDateTimes = [];

    public function updatedSearch()
    {
        $this->games = Game::where('name', 'like', '%' . $this->search . '%')
            ->limit(10)
            ->get();
    }

    public function selectGame($gameId)
    {
        $this->game = Game::find($gameId);
        $this->resetSearch();
    }

    public function removeGame()
    {
        $this->game = null;
    }

    public function resetSearch()
    {
        $this->search = '';
        $this->games = [];
    }

    public function addDateTime()
    {
        $this->dateTimes[] = [
            'date' => $this->date,
            'time' => $this->time,
            'available' => [],
            'notAvailable' => [],
        ];
        $this->reset(['date', 'time']);
    }

    public function removeDateTime($index)
    {
        unset($this->dateTimes[$index]);
        $this->dateTimes = array_values($this->dateTimes);
    }

    public function store()
    {
        $event = Event::create([
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'game_id' => $this->game->id,
            'user_id' => auth()->id(),
        ]);

        $this->saveProposedDates($event);
        $this->createOrganizerInvitee($event);

        return redirect()->to('/events');
    }

    public function update()
    {
        $event = Event::find($this->eventId);

        if ($event) {
            $event->update([
                'title' => $this->title,
                'description' => $this->description,
                'location' => $this->location,
            ]);

            $this->updateProposedDates($event);
        }

        return redirect()->to('/schedule');
    }

    protected function saveProposedDates($event)
    {
        foreach ($this->dateTimes as $dateTime) {
            $event->proposedDates()->create([
                'date_time' => Carbon::parse("{$dateTime['date']} {$dateTime['time']}"),
            ]);
        }
    }

    protected function createOrganizerInvitee($event)
    {
        Invitee::create([
            'event_id' => $event->id,
            'name' => $event->user->name,
            'email' => $event->user->email,
            'message_status' => 'Organizer',
        ]);
    }

    protected function updateProposedDates($event)
    {
        $updatedDates = collect($this->dateTimes)->map(fn($dt) => "{$dt['date']} {$dt['time']}");

        foreach ($this->dateTimes as $dateTime) {
            ProposedDate::updateOrCreate(
                ['event_id' => $event->id, 'date_time' => "{$dateTime['date']} {$dateTime['time']}"]
            );
        }
    }

    public function mount()
    {
        if ($this->eventId) {
            $this->loadEventForEditing();
        }
    }

    protected function loadEventForEditing()
    {
        $event = Event::with('proposedDates.availabilities.user')->find($this->eventId);

        if ($event) {
            $this->fillEventDetails($event);
            $this->dispatch('event-edited', ['selectedDateTimes' => $this->selectedDateTimes]);
        }
    }

    protected function fillEventDetails($event)
    {
        $this->eventId = $event->id;
        $this->title = $event->title;
        $this->description = $event->description;
        $this->location = $event->location;
        $this->game = $event->game;

        $this->dateTimes = $event->proposedDates->map(function ($date) {
            $available = $date->availabilities->filter(fn($a) => $a->is_available);
            $notAvailable = $date->availabilities->filter(fn($a) => !$a->is_available);

            return [
                'date' => Carbon::parse($date->date_time)->format('Y-m-d'),
                'time' => Carbon::parse($date->date_time)->format('H:i'),
                'available' => $available,
                'notAvailable' => $notAvailable,
            ];
        })->toArray();
    }

    public function cancel()
    {
        return redirect()->to('/events/' . $this->eventId);
    }

    public function resetForm()
    {
        $this->reset(['title', 'description', 'location', 'date', 'time', 'dateTimes', 'eventId', 'game', 'selectedDateTimes']);
        $this->isEditMode = false;
    }

    protected function transformDateTimes(array $dateTimes)
    {
        $this->dateTimes = array_merge($this->dateTimes, array_map(fn($dt) => [
            'date' => Carbon::parse(explode(' ', $dt, 2)[0])->toDateString(),
            'time' => Carbon::createFromFormat('h:i A', explode(' ', $dt, 2)[1])->format('H:i'),
        ], $dateTimes));
    }

    #[On('add-datetimes')]
    public function updateDateTimes($dateTimes)
    {
        $this->transformDateTimes($dateTimes);
    }

    public function render()
    {
        $this->userId = auth()->id();
        return view('livewire.schedule-game');
    }

}
