<?php

namespace App\Livewire\Website;

use App\Models\Gallery as GalleryModel;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Gallery extends Component
{
    #[Layout('layouts.public')]
    public function render()
    {
        $galleries = GalleryModel::where('is_published', true)
                                ->orderBy('order_column', 'asc')
                                ->orderBy('created_at', 'desc')
                                ->get();

        return view('livewire.website.gallery', [
            'galleries' => $galleries
        ]);
    }
}
