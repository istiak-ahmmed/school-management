<?php

namespace App\Livewire\Traits;

trait Sortable
{
    public $sortField = 'id';
    public $sortDirection = 'desc';

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
            $this->sortField = $field;
        }

        if (method_exists($this, 'loadData')) {
            $this->loadData();
        }
    }
}
