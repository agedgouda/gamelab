<div>
     <div class="flex">
        <div class="overflow-x-auto w-full">
            <div class="inline-block min-w-full overflow-hidden">
                    <div class="w-full flex flex-row bg-red-200">
                        <div class="w-full h-full" >
                            <div class="w-full h-full p-2 flex flex-col text-center border border-b-0">
                                {{ \Carbon\Carbon::create()->month($month)->format('F');}} {{ $year}}
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
                        @foreach($currentWeekDays as $day)
                        <div class="w-full h-full" >

                            <div class="w-full h-36 p-2 flex border flex-col {{ $day ? $day->isToday() ? 'bg-yellow-100' : ' bg-white ' : 'bg-gray-100'  }} ">
                                        
                                <div class="flex items-center">
                                    <p class="text-sm ">
                                        {{ $day->format('j') }}
                                    </p>
                                    <p class="text-xs text-gray-600 ml-4">
                                       
                                    </p>
                                </div>
                                <div class="p-2 my-2 flex-1 overflow-y-auto">
                                    <div class="grid grid-cols-1 grid-flow-row gap-2">
                                        @if($events)
                                        @foreach($events as $event)
                                            <div
                                                @if($dragAndDropEnabled)
                                                    draggable="true"
                                                @endif
                                                ondragstart="onLivewireCalendarEventDragStart(event, '{{ $event['id'] }}')">
                                                @include($eventView, [
                                                    'event' => $event,
                                                ])
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
