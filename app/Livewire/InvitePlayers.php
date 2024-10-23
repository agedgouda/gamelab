<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

use Livewire\Component;
use App\Models\Invitee;
use App\Mail\InvitePlayer;


class InvitePlayers extends Component
{
    
    public $eventId;
    public $invitees = [];
    public $name;
    public $email;
    public $isProcessing = false;
    public $emailSent = null;
    
    public function sendInvite($eventId)
    {
        $this->isProcessing = true;
        $this->resetErrorBag();
    
        try {
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
                $this->isProcessing = false;
                return; // Stop further execution
            }
    
            // Create the Invitee record
            $invitee = Invitee::create([
                'event_id' => $eventId,
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
            ]);
    
            // Send the email
            Mail::to($validatedData['email'])->send(new InvitePlayer($invitee));
    
            // Reset form fields
            $this->name = '';
            $this->email = '';
    
            // Mark success
            $invitee->message_status = 'Invitation Sent';
            $invitee->save();
            $this->emailSent = true;
        } catch (\Exception $e) {
            // Mark failure
            $this->emailSent = false;
        }
    
        $this->isProcessing = false;
    }

    public function render()
    {
        $this->invitees = Invitee::with('user')->where('event_id',$this->eventId)->get();
        return view('livewire.invite-players');
    }
}
