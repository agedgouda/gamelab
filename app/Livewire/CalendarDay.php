<?php

namespace App\Livewire;


use LivewireUI\Modal\ModalComponent;

class CalendarDay extends ModalComponent
{
    public $weekday;
    public $weekdayString;
    public $times = [];
    public $selectedDateTimes = [];
    public $backgroundClass;

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
        } else {
            $this->selectedDateTimes = array_filter($this->selectedDateTimes, fn($dt) => $dt !== $dateTime);
        }

        //\Log::info($this->selectedDateTimes );
    }

    public function getBackgroundClass($day, $time)
    {
        $dateTime = "{$day} {$time}";

        // Return 'bg-red-500 text-white' if it's in selectedDateTimes and not in selectedTimes
        if (in_array($dateTime, $this->selectedDateTimes)) {
            return 'bg-red-800 text-white';
        } 
        // Return 'bg-black text-white' if it's in selectedTimes
        elseif (in_array($time, $this->weekday->selectedTimes)) {
            return 'bg-black text-white';
        } 
        // Return default empty string if neither condition is met
        return '';
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
