<div>

    <p style="text-align: center;">
        <img src="{{ url('/img/logoemail.png')}}">
    </p>
    
    <p>Dear {{ $invitee->name }},</p>
        
    <p>
   
    </p>
    <p>
        @if(count($invitee->event->proposedDates) > 1)
            You have been invited to play {{ $invitee->event->game->name }} at {{ $invitee->event->location }} one of the dates below:
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
            You have been invited to play {{ $invitee->event->game->name }} at {{ $invitee->event->description }} 
            on {{ \Carbon\Carbon::parse($invitee->event->proposedDates[0]->date_time)->format('m/d/Y') }} at {{ \Carbon\Carbon::parse($invitee->event->proposedDates[0]->date_time)->format('h:i A') }}.
            <p>
                Click here to RSVP:<br>
                <a href="{{ url('/events/'.$invitee->event->id)  }}">{{ url("/events/".$invitee->event->id) }}</a>
            </p>
        @endif
    </p>
</div>