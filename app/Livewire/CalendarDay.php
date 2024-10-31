<?php

namespace App\Livewire;


use LivewireUI\Modal\ModalComponent;

class CalendarDay extends ModalComponent
{
    public $weekday;
    public $weekdayString;
    public $times = [];
    public $selectedDateTimes = [];

    public function mount()
    {
        $start = new \DateTime('09:00');
        $end = new \DateTime('21:00');
        $interval = new \DateInterval('PT30M'); 
        while ($start <= $end) {
            $this->times[] = $start->format('g:i A'); // Format as needed
            $start->add($interval);
        }
        $this->weekday = json_decode($this->weekdayString);
    }

    public function addSelectedDateTime($dateTime)
    {
        if (!in_array($dateTime, $this->selectedDateTimes)) {
            $this->selectedDateTimes[] = $dateTime;
        }
    }

    public function close()
    {
        $this->closeModalWithEvents([
            Calendar::class => ['add-datetimes', [$this->selectedDateTimes]],
            ScheduleGame::class => ['add-datetimes', [$this->selectedDateTimes]],
        ]);
    }



    public function render()
    {
        return view('livewire.calendar-day');
    }
}
