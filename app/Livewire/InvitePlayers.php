<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
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
    public $uninvitedFriends = [];

    
    public function getUninvitedFriends()
    {
        // Get the authenticated user's friends who are not invitees of the specified event
        if(Auth::user() && Auth::user()->friends()) {
        $this->uninvitedFriends = Auth::user()->friends()
            ->whereDoesntHave('invitees', function ($query) {
                $query->where('event_id', $this->eventId); // Check if the friend has been invited to the event
            })
            ->get();
        }
    }

    public function sendInvite($eventId,$name = null, $email = null)
    {
        $this->isProcessing = true;

        $this->name = $name ?? $this->name; 
        $this->email = $email ?? $this->email; 


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
            $invitee = Invitee::where('event_id', $eventId)
                ->where('email', $this->email)
                ->first();
    
            if ($invitee) {
                //if it has been successfully sent stop the process
                if ($invitee->message_status !='not sent') {
                    $this->addError('invite', 'That person has already been invited.');
                    $this->isProcessing = false;
                    return; // Stop further execution
                }
            } else {  
                // Create the Invitee record if it doesn't exist
                $invitee = Invitee::create([
                    'event_id' => $eventId,
                    'name' => $validatedData['name'],
                    'email' => $validatedData['email'],
                ]);
            }
    
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
            // Reset form fields
            $this->name = '';
            $this->email = '';
            $this->emailSent = false;
            \Log::error($e);
        }
    
        $this->isProcessing = false;
    }

    public function render()
    {
        $this->invitees = Invitee::with('user')->where('event_id',$this->eventId)->get();
        $this->getUninvitedFriends();
        return view('livewire.invite-players');
    }
}
