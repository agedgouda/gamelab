<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class GameDaySelected extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(
        $event,
        $user,
    ) {
        $this->event = $event;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: 'Get Ready to Play '.$this->event->game->name.'!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.game_day_selected',
            with:[
                    'event' => $this->event,
                    'user' => $this->user,
                ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // Start and end time of the event
        $startDate = Carbon::parse($this->event->selectedDate->date_time);
        $endDate = $startDate->copy()->addHours(4);

        // ICS File Content
        $icsContent = "BEGIN:VCALENDAR\r\n" .
                  "VERSION:2.0\r\n" .
                  "PRODID:-//Your App Name//EN\r\n" .
                  "CALSCALE:GREGORIAN\r\n" .
                  "BEGIN:VEVENT\r\n" .
                  "DTSTART:" . $startDate->format('Ymd\THis') . "\r\n" .
                  "DTEND:" . $endDate->format('Ymd\THis') . "\r\n" .
                  "DTSTAMP:" . now()->format('Ymd\THis') . "\r\n" .
                  "UID:" . uniqid() . "\r\n" .
                  "SUMMARY:Game Day - " . $this->event->game->name . "\r\n" .
                  "DESCRIPTION:Join us for game day at " . $this->event->location . "\r\n" .
                  "LOCATION:" . $this->event->location . "\r\n" .
                  "END:VEVENT\r\n" .
                  "END:VCALENDAR\r\n";

        // Attach the ICS file
        return [
            \Illuminate\Mail\Mailables\Attachment::fromData(fn () => $icsContent, 'gamelab-'.$this->event->game->name.'.ics')
                ->withMime('text/calendar')
        ];
    }
}
