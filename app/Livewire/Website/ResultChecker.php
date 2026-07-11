<?php

namespace App\Livewire\Website;

use App\Models\AdmissionApplication;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

class ResultChecker extends Component
{
    #[Rule('required|string|max:30')]
    public $application_no = '';

    public $result = null;
    public $hasSearched = false;

    #[Layout('layouts.public')]
    public function render()
    {
        return view('livewire.website.result-checker');
    }

    public function checkResult()
    {
        $this->validate();
        $this->hasSearched = true;
        
        $this->result = AdmissionApplication::where('application_no', $this->application_no)->first();
    }
}
