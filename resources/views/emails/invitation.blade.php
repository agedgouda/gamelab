<div>

    <p style="text-align: center;">
        <img src="{{ url('/img/logoemail_70s.png')}}">
    </p>
    
    <p>Dear {{ $invitee->name }},</p>
        
    <p>
   
    </p>
    <p>
        @php
            if ($invitee->event->date_selected_id) {
                // Filter to get only the selected date if it exists
                $datesToDisplay = $invitee->event->proposedDates->where('id', $invitee->event->date_selected_id);
                
            } else {
                // Otherwise, get all dates
                $datesToDisplay = $invitee->event->proposedDates;
            }
        @endphp
        @if(count($datesToDisplay) > 1)
            {{ $invitee->event->user->name }} invited you to play {{ $invitee->event->game->name }} at {{ $invitee->event->location }} one of the dates below:
            @foreach($invitee->event->proposedDates as $date)
                <div style="width: 45%; text-align: center; {{ $loop->odd ? 'background-color: #e5e7eb;' : '' }}">
                        {{ \Carbon\Carbon::parse($date->date_time)->format('m/d/Y') }} at {{ \Carbon\Carbon::parse($date->date_time)->format('h:i A') }}
                </div>
            @endforeach
            <p>
                Click here to RSVP and pick a date:<br>
                <a href="{{ url('/events/'.$invitee->event->id)  }}">{{ url("/events/".$invitee->event->id) }}</a>
            </p>
        @else 
            {{ $invitee->event->user->name }} invited you to play {{ $invitee->event->game->name }} at {{ $invitee->event->location }} 
            on {{ \Carbon\Carbon::parse($invitee->event->proposedDates[0]->date_time)->format('m/d/Y') }} at {{ \Carbon\Carbon::parse($invitee->event->proposedDates[0]->date_time)->format('h:i A') }}.
            <p>
                Click here to RSVP:<br>
                <a href="{{ url('/events/'.$invitee->event->id)  }}">{{ url("/events/".$invitee->event->id) }}</a>
            </p>
        @endif
    </p>
</div>