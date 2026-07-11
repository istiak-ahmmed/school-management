<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
