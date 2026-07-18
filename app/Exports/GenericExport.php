<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GenericExport implements FromCollection, WithHeadings
{
    protected $data;
    protected $headings;

    public function __construct(array $headings, $data)
    {
        $this->headings = $headings;
        // Data can be an array or collection
        $this->data = collect($data);
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->headings;
    }
}
