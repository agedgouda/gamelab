<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On; 

use App\Models\Game;
use App\Models\User;
use App\Models\Event;
use App\Models\Invitee;
use App\Models\ProposedDate;

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
    public $events = [];
    public $isEditMode = false;
    public $eventId = '';
    public $selectedDateTimes = [];
   

    public function updatedSearch()
    {
        $this->games = Game::where('name', 'like', '%' . $this->search . '%')
            ->limit(10) // Limit results for performance
            ->get();
    }

    public function selectGame($gameId)
    {
        $this->game = Game::find($gameId);
        $this->search = ''; // Clear the search box
        $this->games = []; // Clear the suggestions
    }
    
    public function removeGame() {
        $this->game = '';
    }

    public function addDateTime()
    {
        // Push a new date and time pair into the array
        $this->dateTimes[] = [
            'date' => $this->date,
            'time' => $this->time,
            'available' => [],
            'notAvailable' => [],
        ];
        // Reset the input fields after adding
        $this->reset(['date', 'time']);
    }

    public function removeDateTime($index)
    {
        // Remove the selected date and time pair from the array
        unset($this->dateTimes[$index]);
        $this->dateTimes = array_values($this->dateTimes); // Reindex the array
    }

    public function createEvent() {

       
        $event = Event::create([
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'game_id' => $this->game->id,
            'user_id' => auth()->id(),
        ]);


        foreach ($this->dateTimes as $dateTime) {
            $event->proposedDates()->create([
                'date_time' => \Carbon\Carbon::parse($dateTime['date'] . ' ' . $dateTime['time']),
            ]);
        }

        $invitee = Invitee::create([
            'event_id' => $event->id,
            'name' => $event->user->name,
            'email' => $event->user->email,
            'message_status' => 'Organizer'
        ]);


        return redirect()->to('/schedule');
    }

    public function updateEvent() {
        // Find the event
        $event = Event::find($this->eventId);
    
        if ($event) {
            // Update the event details
            $event->update([
                'title' => $this->title,
                'description' => $this->description,
                'location' => $this->location,
            ]);
    
            // Get all existing proposed dates for the event
            $existingDates = ProposedDate::where('event_id', $event->id)->get();
    
            // Convert the current $this->dateTimes to an array of formatted datetime strings
            $updatedDates = array_map(function ($dateTime) {
                return $dateTime['date'] . ' ' . $dateTime['time'];
            }, $this->dateTimes);
    
            // Update or create new proposed dates from the form
            foreach ($this->dateTimes as $dateTime) {
                ProposedDate::updateOrCreate(
                    ['event_id' => $event->id, 'date_time' => $dateTime['date'] . ' ' . $dateTime['time']]
                );
            }
        }
        return redirect()->to('/schedule');
    }
    
    
    public function edit($eventId) {
        $this->isEditMode = true;
        $event = Event::with('proposedDates.availabilities.user')->find($eventId);
    
        if ($event) {
            $this->eventId = $event->id;
            $this->title = $event->title;
            $this->description = $event->description;
    
            // Clear the existing date times
            $this->dateTimes = [];
    
            // Populate the dateTimes array with the proposed dates
            foreach ($event->proposedDates as $date) {
                $availabilitiesForDate = $date->availabilities;
                [$available, $notAvailable] = collect($availabilitiesForDate)->partition(fn($availability) => $availability['is_available'] == 1);

                $this->dateTimes[] = [
                    'date' => \Carbon\Carbon::parse($date->date_time)->format('Y-m-d'),
                    'time' => \Carbon\Carbon::parse($date->date_time)->format('H:i'),
                    'available' => $available,
                    'notAvailable' => $notAvailable,
                ];
            }
            $this->game = Game::find($event->game->id);

            $this->selectedDateTimes = array_map(function ($item) {
                // Combine date and time and convert to desired format
                $dateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $item['date'] . ' ' . $item['time']);
                return $dateTime->format('Y-m-d 00:00:00 g:i A');
            }, $this->dateTimes);
            $this->dispatch('event-edited', selectedDateTimes: $this->selectedDateTimes);
            
        }
    }

    public function resetForm()
    {
        // Reset form fields
        $this->title = '';
        $this->description = '';
        $this->date = '';
        $this->time = '';
        $this->dateTimes = [];
        $this->eventId = '';
        $this->game = '';
        $this->selectedDateTimes = [];

        // Reset the editing mode
        $this->isEditMode = false;
    }

    public function transformDateTimes(array $dateTimes)
    {
        $newDateTimes = array_map(function ($dateTime) {
            // Split the date and time parts
            [$date, $time] = explode(' ', $dateTime, 2);
    
            // Format the date
            $formattedDate = \Carbon\Carbon::parse($date)->toDateString();
    
            return [
                'date' => $formattedDate,
                'time' => \DateTime::createFromFormat('h:i A',$time)->format('H:i'),
            ];
        }, $dateTimes);
    
        $this->dateTimes = array_merge($this->dateTimes, $newDateTimes);

    }
    

    #[On('add-datetimes')] 
    public function updateDateTimes($dateTimes)
    {
        $this->transformDateTimes($dateTimes);

    }


    public function render()
    {   
        $this->userId = auth()->id(); 
        $this->events = Event::with(['game', 'proposedDates'])
        ->whereHas('proposedDates', function ($query) {
            $query->where('date_time', '>=', now());
        })
        ->whereDoesntHave('proposedDates', function ($query) {
            $query->where('date_time', '<', now());
            })
        ->get();

        return view('livewire.schedule-game');
    }

}
