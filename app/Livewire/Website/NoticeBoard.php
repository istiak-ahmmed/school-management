<?php

namespace App\Livewire\Website;

use App\Models\Notice;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

class NoticeBoard extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';

    #[Layout('layouts.public')]
    public function render()
    {
        $query = Notice::where('is_published', true);

        if (!empty($this->search)) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        if (!empty($this->category)) {
            $query->where('category', $this->category);
        }

        $notices = $query->orderBy('publish_from', 'desc')->paginate(10);

        return view('livewire.website.notice-board', [
            'notices' => $notices
        ]);
    }
}
