<div>

    <p style="text-align: center;">
        <x-application-logo class="h-6 text-gray-800" />
        Better Living through Gaming
    </p>
    
    <p>Dear {{ $invitee->name }},</p>
        
    <p>
   You have been invited to play {{ $invitee->event->game->name }} at {{ $invitee->event->description }}. 
    </p>

    <p>
        To RSVP and pick a date, click here:<br>
        <a href="{{ url('/events/'.$invitee->event->id)  }}">{{ url("/events/".$invitee->event->id) }}</a>
    </p>

</div>