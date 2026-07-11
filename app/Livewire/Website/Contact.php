<?php

namespace App\Livewire\Website;

use App\Models\ContactMessage;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

class Contact extends Component
{
    #[Rule('required|string|max:255')]
    public $name = '';

    #[Rule('nullable|email|max:255')]
    public $email = '';

    #[Rule('nullable|string|max:20')]
    public $phone = '';

    #[Rule('required|string|max:255')]
    public $subject = '';

    #[Rule('required|string')]
    public $message = '';

    #[Layout('layouts.public')]
    public function render()
    {
        return view('livewire.website.contact');
    }

    public function submit()
    {
        $this->validate();

        ContactMessage::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'subject' => $this->subject,
            'message' => $this->message,
        ]);

        $this->reset(['name', 'email', 'phone', 'subject', 'message']);
        
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'আপনার বার্তা সফলভাবে পাঠানো হয়েছে। আমরা শীঘ্রই যোগাযোগ করব।'
        ]);
    }
}
