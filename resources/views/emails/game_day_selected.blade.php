<div>

    <p style="text-align: center;">
        <img src="{{ url('/img/logoemail.png')}}">
    </p>
    
    <p>Dear {{ $user->name }},</p>
        
    <p>
   
    </p>
    <p>
            {{ $event->user->name }} has selected {{ \Carbon\Carbon::parse($event->selectedDate->date_time)->format('m/d/Y') }} at {{ \Carbon\Carbon::parse($event->selectedDate->date_time)->format('h:i A') }} to play {{ $event->game->name }} at {{ $event->location }}.
    </p>
</div>