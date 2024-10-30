<?php

namespace App\Livewire;

use Livewire\Component;
use Carbon\Carbon;

class Calendar extends Component
{

    public $month;
    public $year;
    public $currentWeekStart;
    public $currentWeekDays = [];
    public $hours = [];
    public $selectedTimes = [];
    public $dateTimes = [];
    public $events = [];
    
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



    
    public function render()
    {
        return view('livewire.calendar');
    }
}
