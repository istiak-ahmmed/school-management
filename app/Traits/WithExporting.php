<?php

namespace App\Traits;

use App\Exports\GenericExport;
use Maatwebsite\Excel\Facades\Excel;

trait WithExporting
{
    /**
     * Define the headers for the export
     * @return array
     */
    abstract public function getExportHeaders(): array;

    /**
     * Define the data for the export
     * @return array
     */
    abstract public function getExportData(): array;

    /**
     * Define the base filename for the export
     */
    public function getExportFilename(): string
    {
        return 'export-' . now()->format('Y-m-d-His');
    }

    public function exportToExcel()
    {
        $export = new GenericExport($this->getExportHeaders(), $this->getExportData());
        return Excel::download($export, $this->getExportFilename() . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function exportToCsv()
    {
        $export = new GenericExport($this->getExportHeaders(), $this->getExportData());
        return Excel::download($export, $this->getExportFilename() . '.csv', \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ])->useBom(); // Enable BOM for Bangla font compatibility in MS Excel
    }

    public function exportToPdf()
    {
        $this->dispatch('trigger-print');
    }
}
