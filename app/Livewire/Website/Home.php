<?php

namespace App\Livewire\Website;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Notice;

class Home extends Component
{
    #[Layout('layouts.public')]
    public function render()
    {
        $notices = Notice::where('is_published', true)
                                     ->orderBy('publish_from', 'desc')
                                     ->take(3)
                                     ->get();

        return view('livewire.website.home', [
            'notices' => $notices
        ]);
    }
}
