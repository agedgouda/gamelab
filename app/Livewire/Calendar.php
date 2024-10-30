<?php

namespace App\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use Livewire\Attributes\On; 

class Calendar extends Component
{

    public $month;
    public $year;
    public $currentWeekStart;
    public $currentWeekDays = [];
    public $hours = [];
    public $selectedTimes = [];
    public $selectedDateTimes = []; // = ["2024-10-30 00:00:00 2:00 PM","2024-10-30 00:00:00 5:30 PM","2024-11-01 00:00:00 12:30 PM","2024-11-01 00:00:00 6:00 PM"];
    public $dateTimes = [];
    public $events = [];
    public $dateTimesCollection = [];
    
    public function mount()
    {
        $now = Carbon::now();
        $this->month = $now->month;
        $this->year = $now->year;
        $this->currentWeekStart = $now->startOfWeek()->toDateString();
        $this->hours = array_map(function ($i) {
            return ($i + 9) . ':00 ' . ($i < 3 ? 'AM' : 'PM');
        }, range(0, 15));

        $this->generateCalendar();
    }

    public function generateCalendar()
    {
        $this->updateWeekDays();
        $this->updateMonthYear();
    }

    public function updateWeekDays()
    {
        $start = Carbon::parse($this->currentWeekStart);
        $this->currentWeekDays = collect(range(0, 6))->map(function ($i) use ($start) {
            $day = $start->copy()->addDays($i);
            //$this->selectedTimes[$dateString] = $this->selectedTimes[$dateString] ?? [];
            return $day;
        });
    }

    public function updateMonthYear()
    {
        $this->month = Carbon::parse($this->currentWeekStart)->month;
        $this->year = Carbon::parse($this->currentWeekStart)->year;
    }

    public function prevWeek()
    {
        $this->currentWeekStart = Carbon::parse($this->currentWeekStart)->subWeek()->toDateString();
        $this->generateCalendar();
    }

    public function nextWeek()
    {
        $this->currentWeekStart = Carbon::parse($this->currentWeekStart)->addWeek()->toDateString();
        $this->generateCalendar();
    }
    
    #[On('add-datetimes')] 
    public function updateDateTimes($dateTimes)
    {
        $this->selectedDateTimes = array_unique(array_merge($this->selectedDateTimes, $dateTimes));

        $this->dateTimesCollection = collect($this->selectedDateTimes)
        ->map(function ($dateTime) {
            // Separate the date and time parts
            [$date, $time] = explode(' ', $dateTime, 2);
            return ['date' => $date, 'time' => $time];
        })
        ->groupBy('date')
        ->map(function ($items) {
            // Extract only the times for each date
            return $items->pluck('time')->all();
        })
        ->toArray();
    }


    
    public function render()
    {
        return view('livewire.calendar');
    }
}
