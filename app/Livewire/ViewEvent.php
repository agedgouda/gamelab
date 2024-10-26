<?php

namespace App\Livewire;


use Livewire\Component;
use App\Services\BoardGameGeekService;
use App\Models\Event;
use App\Models\UserDateAvailability;
use Illuminate\Support\Facades\Mail;
use App\Mail\GameDaySelected;


class ViewEvent extends Component
{
    //public $eventId;
    public $event; 
    public $eventId;
    public $bggGameData;
    public $userAvailabilities = [];
    //public $activeTab = 'game-details';
    public $activeTab = 'posts';
    public $setDateUsers = [];
    public $isProcessing = false;

    protected $BggDataService;

    public function __construct() {
        $this->boardGameGeekService = new boardGameGeekService();
    }

    public function mount($eventId) 
    {
        $this->eventID = $eventId;
        $this->event = Event::with('proposedDates.availabilities.user','game')->find($eventId);
        $this->bggGameData = $this->boardGameGeekService->fetchGameData($this->event->game->bgg_id);


        foreach ($this->event->proposedDates as $date) {
            foreach ($date->availabilities as $availability) {
                $this->userAvailabilities[] = [
                    'date_id' => $date->id,
                    'user_id' => $availability->user_id,
                    'is_available' => $availability->is_available,
                    'user_name' => $availability->user->name,
                ];
            }
        }
    }
  
    public function enterAvailability($isAvailable, $proposedDateId)
    {
        if (!auth()->check()) {
            session()->flash('message', 'You must be logged in to do that.');
            session()->flash('route', url()->previous());
            return redirect()->route('login');
        }

        
        $userAvailability = UserDateAvailability::where('user_id', auth()->id())
            ->where('proposed_date_id', $proposedDateId)
            ->first();

        if ($userAvailability) {
            $userAvailability->update(['is_available' => $isAvailable]);
        } else {
            $userAvailability  = UserDateAvailability::create([
                'user_id' => auth()->id(),
                'proposed_date_id' => $proposedDateId,
                'is_available' => $isAvailable,
            ]);
        }
        if (!empty($this->userAvailabilities)) {
            // Find the index of the existing entry
            $index = array_search(true, array_map(function($availability) use ($userAvailability, $proposedDateId) {
                return $availability['user_id'] === $userAvailability->user_id && $availability['date_id'] === $proposedDateId;
            }, $this->userAvailabilities));
        } else {
            $index = false; // If the array is empty, treat as not found
        }

        if ($index !== false) {
            // If the user is found in the array, update their availability
            $this->userAvailabilities[$index]['is_available'] = $userAvailability->is_available;
        } else {
            // If the user is not found, add them to the array
            $this->userAvailabilities[] = [
                'date_id' => $proposedDateId,
                'user_id' => $userAvailability->user_id,
                'is_available' => $isAvailable,
                'user_name' => $userAvailability->user->name,
            ];
        }

    }

    public function setEventDate($selectedEventDayId) {
        $this->isProcessing = true;
        $this->event->date_selected_id = $selectedEventDayId;
        $this->event->save();
        $this->setDateUsers = $this->event->proposedDates->firstWhere('id', $selectedEventDayId)?->availabilities;
        $players = $this->setDateUsers->pluck('user');
        foreach($players as $player) {
            \Log::info($player);
            Mail::to($player->email)->send(new GameDaySelected($this->event, $player));
        }
        $this->isProcessing = false;

    }


    public function render()
    {

        return view('livewire.view-event');
    }
}
