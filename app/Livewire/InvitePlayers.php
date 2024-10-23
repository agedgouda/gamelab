<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use App\Models\Invitee;

class InvitePlayers extends Component
{
    
    public $eventId;
    public $invitees = [];
    public $name;
    public $email;
    
    public function sendInvite($eventId)
    {
        // Validate the inputs
        $validatedData = Validator::make([
            'name' => $this->name,
            'email' => $this->email,
        ], [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ])->validate();

        // Check if the email has already been invited for the given eventId
        $existingInvite = Invitee::where('event_id', $eventId)
            ->where('email', $this->email)
            ->first();

        if ($existingInvite) {
            $this->addError('invite', 'That person has already been invited.');
            return; // Stop further execution
        }

        // Create the Invitee record
        Invitee::create([
            'event_id' => $eventId,
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
        ]);

        $this->name = '';
        $this->email = '';

        $this->resetErrorBag();

        // Optionally: add a success message or handle post-invite logic here
        //session()->flash('success', 'Invite sent successfully!');
    }
    public function render()
    {
        $this->invitees = Invitee::with('user')->where('event_id',$this->eventId)->get();
        return view('livewire.invite-players');
    }
}
