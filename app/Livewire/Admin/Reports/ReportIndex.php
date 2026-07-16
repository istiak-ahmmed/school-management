<?php

namespace App\Livewire\Admin\Reports;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('admin.layouts.app')]
#[Title('সকল রিপোর্ট (All Reports)')]
class ReportIndex extends Component
{
    public function render()
    {
        return view('livewire.admin.reports.report-index');
    }
}
