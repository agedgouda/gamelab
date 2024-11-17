<div>
     <div class="flex">
        <div class="overflow-x-auto w-full">
            <div class="inline-block min-w-full overflow-hidden">
                    <div class="w-full flex flex-row bg-red-200">
                        <div class="w-full h-full" >
                            <div class="w-full h-full p-2 flex flex-col text-center border border-b-0">
                                {{ \Carbon\Carbon::create()->month($month)->format('F');}} {{ $year }}
                            </div>
                        </div>
                    </div>
                    <div class="w-full flex flex-row bg-sky-200">

                        @php
                            $daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                        @endphp
                        @foreach($daysOfWeek as $day)
                        <div class="w-full h-full" >
                            <div class="w-full h-full p-2 flex flex-col border border-b-0">
                                <div class="flex justify-between">
                                    <div class="justify-left">
                                        @if($day == 'Sunday') 
                                        <button type="button" wire:click="prevWeek()">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="gray" class="h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                                            </svg>
                                        </button>                                       
                                        @endif
                                    </div> 
                                    <div class="justify-center text-center">
                                        {{ $day }}
                                    </div>
                                    <div class="justify-left"> 
                                        @if($day == 'Saturday')
                                        <button type="button" wire:click="nextWeek()">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="gray" class="h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                            </svg>
                                        </button>
                                        @endif
                                    </div> 
                                </div>
                            </div>
                        </div>
                        @endforeach

                    </div>
                    <div class="w-full flex flex-row">
                        @foreach($currentWeekDays as $weekday)
                        <div class="w-full h-full" >
                        <div 
                            @if($weekday->day->isToday() || $weekday->day > now())
                                onclick="Livewire.dispatch('openModal', { component: 'calendar.calendar-day' , arguments: { weekdayString: '{{ json_encode($weekday) }}' }})"
                                class="w-full cursor-pointer h-36 p-2 flex border flex-col {{ $day ? ($weekday->day->isToday() ? 'bg-yellow-100' : 'bg-white') : 'bg-gray-100' }}"
                            @else
                                class="w-full h-36 p-2 flex border flex-col bg-gray-100 cursor-not-allowed opacity-50"
                            @endif
                        >                                        
                                <div class="flex items-center">
                                    <p class="text-sm ">
                                        {{ $weekday->day->format('j') }}
                                    </p>
                                    <p class="text-xs text-gray-600 ml-4">
                                    </p>
                                </div>
                                <div class="my-2 flex-1 overflow-y-auto">
                                    <div class="grid grid-cols-1 grid-flow-row">
                                        @if($weekday->selectedTimes)
                                        @foreach($weekday->selectedTimes as $time)
                                            <div class="text-xs">
                                                {{ \Carbon\Carbon::parse($time)->format('g:i A') }}
                                            </div>
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
            </div>
        </div>
    </div>
</div>
