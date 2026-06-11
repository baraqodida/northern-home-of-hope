<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ContributionExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Return the isolated collection pool
     */
    public function collection()
    {
        return $this->data;
    }

    /**
     * Define the spreadsheet header line row
     */
    public function headings(): array
    {
        return [
            'Member Name',
            'Group Cluster',
            'Allocation Purpose',
            'Lifecycle Status',
            'Amount Contributed (Ksh)'
        ];
    }

    /**
     * Map relationships explicitly to prevent raw ID printing
     */
    public function map($contribution): array
    {
        return [
            $contribution->user->name,
            $contribution->user->group ? $contribution->user->group->name : 'Unassigned',
            $contribution->purpose,
            ucfirst($contribution->status),
            $contribution->amount
        ];
    }

    /**
     * Add professional row styling to the worksheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the header row (Row 1): Bold text with a light blue background
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2B6CB0']
                ]
            ],
        ];
    }
}