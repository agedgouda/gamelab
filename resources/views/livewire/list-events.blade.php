<div class="w-full mb-5">
    <div class="font-bold mb-5">
        Upcoming Games
    </div>
    @if($events)
    <div class="grid grid-cols-4">
        <div class="font-semibold">
            Title
        </div>
        <div class="font-semibold">
            Game
        </div>
        <div class="font-semibold">
            Location
        </div>
        <div class="font-semibold">
            Dates
            </div>
    </div>
    @foreach($events as $event)
    <div class="grid grid-cols-4 hover:bg-gray-100 cursor-pointer {{ $loop->odd ? 'bg-gray-200' : '' }}" @click="window.location.href = '/events/{{ $event->id }}'">
        <div class="align-middle">
            {{ $event->title }}
        </div>
        <div class="align-middle">
            {{ $event->game->name }}
        </div>
        <div class="align-middle">
            {{ $event->description }}
        </div>
        <div class="align-middle">
            @foreach($event->proposedDates as $date)
                {{ \Carbon\Carbon::parse($date->date_time)->format('h:i A') }} {{ \Carbon\Carbon::parse($date->date_time)->format('m/d/Y') }}<br>
            @endforeach
        </div>
    </div>
    @endforeach


    @else
    <div class="font-semibold mb-5">
        No games scheduled
    </div>
    @endif

</div>
