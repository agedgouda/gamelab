<?php

namespace App\Livewire\Calendar;

use Livewire\Component;
use Livewire\Attributes\On; 

use App\Models\Event;

use Carbon\Carbon;

class CalendarWeek extends Component
{

    public $month;
    public $year;
    public $currentWeekStart;
    public $currentWeekDays = [];
    public $hours = [];
    public $selectedTimes = [];
    public $dateTimes = [];
    public $eventId;
    public $dateTimesCollection;
    public $selectedDateTimes = [];
    
    public function mount()
    {

        $now = Carbon::now();
        $this->month = $now->month;
        $this->year = $now->year;
        $this->currentWeekStart = $now->startOfWeek(Carbon::SUNDAY)->toDateString();
        $this->hours = array_map(function ($i) {
            return ($i + 9) . ':00 ' . ($i < 3 ? 'AM' : 'PM');
        }, range(0, 15));

        if($this->eventId) {
            $event = Event::findOrFail($this->eventId);
            $this->selectedDateTimes = collect($event->proposedDates->pluck('date_time'))->map(function ($dateTime) {
                return Carbon::parse($dateTime)->format('Y-m-d 00:00:00 g:i A');
            })->toArray();
        }

        $this->makeDateTimesCollection();
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
            $dateString = $day->toDateString();

            // Get times and format them
            $formattedTimes = array_map(function ($time) {
                // Extract only the part after the space (removing "00:00:00")
                $timeParts = explode(' ', $time); // This will split the string into parts
                return isset($timeParts[1]) ? $timeParts[1] . ' ' . $timeParts[2] : $time; // Return only the "2:00 PM"
            }, $this->dateTimesCollection[$dateString] ?? []);

            return (object) [
                'day' => $day,
                'selectedTimes' => $formattedTimes,
            ];
        })->toArray();
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
    
    #[On('event-edited')]
    public function editCalendar($selectedDateTimes) {
        $this->selectedDateTimes = $selectedDateTimes;
        $this->makeDateTimesCollection();

        $this->generateCalendar();
        $this->updateWeekDays();
    }

    #[On('add-datetimes')] 
    public function updateDateTimes($dateTimes)
    {
        
        
        $dateTimes = array_map(function ($dateTime) {
            // Remove the "T", microseconds, and timezone, leaving just the date, time, and AM/PM
            return preg_replace('/T\d{2}:\d{2}:\d{2}\.\d{6}Z/', ' 00:00:00', $dateTime);
        }, $dateTimes);
        \Log::info($dateTimes);
        $this->selectedDateTimes = array_unique(array_merge($this->selectedDateTimes, $dateTimes));
        \Log::info($this->selectedDateTimes);
        $this->makeDateTimesCollection();
        $this->generateCalendar();
    }

    public function makeDateTimesCollection()
    {
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
        return view('livewire.calendar.calendar-week');
    }
}
