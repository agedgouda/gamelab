<div class="w-full mb-5 text-yellow-900 ">
    
    @if($events)
    <table class="w-full bg-slate-100 border-collapse border border-gray-300">
        <thead class="bg-green-100 text-yellow-900">
            <tr>
                <th class="font-semibold border border-gray-300 p-2">Game</th>
                <th class="font-semibold border border-gray-300 p-2">Location</th>
                <th class="font-semibold border border-gray-300 p-2">Dates</th>
                <th class="font-semibold border border-gray-300 p-2 text-center">
                    # Accepted
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
            <tr class="hover:bg-teal-700 text-yellow-900 hover:text-yellow-400 cursor-pointer {{ $event->date_selected_id ? 'font-bold' : '' }}" 
                @click="window.location.href = '/events/{{ $event->id }}'">
                <td class="border border-gray-300 p-2 align-middle">
                    <div class="flex items-center">
                        <img src="{{ $event->game->thumbnail }}" class="w-16 h-16 object-cover mr-2" />
                        <span>{{ $event->game->name }}</span>
                    </div>
                </td>
                <td class="border border-gray-300 p-2 align-middle">
                    {{ $event->location }}
                </td>
                
                @php
                $datesToDisplay = $event->date_selected_id 
                    ? $event->proposedDates->where('id', $event->date_selected_id) 
                    : $event->proposedDates;
                @endphp
    
                <td class="border border-gray-300 p-2 align-middle text-center">
                    @foreach($datesToDisplay as $date)
                    <div>
                        {{ \Carbon\Carbon::parse($date->date_time)->format('h:i A m/d/Y') }}
                    </div>
                    @endforeach
                </td>
    
                <td class="border border-gray-300 p-2 align-middle text-center">
                    @foreach($datesToDisplay as $date)
                    @php
                        $availabilitiesForDate = $date->availabilities;
                        [$available, $notAvailable] = collect($availabilitiesForDate)
                            ->partition(fn($availability) => $availability['is_available'] == 1);
                    @endphp
                    <div class="{{ $date->id == $event->date_selected_id ? 'font-bold' : '' }}">
                        {{ count($available) }}
                    </div>
                    @endforeach
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    

    @else
    <div class="font-semibold mb-5">
        No games scheduled
    </div>
    @endif

</div>
