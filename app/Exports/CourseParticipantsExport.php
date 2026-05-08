<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CourseParticipantsExport implements FromCollection, WithHeadings
{
    protected $participants;

    public function __construct($participants)
    {
        $this->participants = $participants;
    }

    public function collection()
    {
        return collect($this->participants)->map(function ($p, $index) {

            return [
                'No' => $index + 1,
                'Name' => $p->name,
                'Email' => $p->email,
                'Phone' => $p->phone,
                'Organization' => $p->organization,
                'Certificate' => $p->certificate_name,
                'Status' => $p->collected ? 'Collected' : 'Pending',
            ];
        });
    }

    public function headings(): array
    {
        return [
            '#',
            'Name',
            'Email',
            'Phone',
            'Organization',
            'Certificate',
            'Status'
        ];
    }
}
