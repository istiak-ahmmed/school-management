<?php

namespace App\Livewire\Website;

use Livewire\Component;
use Livewire\Attributes\Layout;

class About extends Component
{
    #[Layout('layouts.public')]
    public function render()
    {
        return view('livewire.website.about');
    }
}
