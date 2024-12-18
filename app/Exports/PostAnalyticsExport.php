<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PostAnalyticsExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return ['S.No', 'Geography', 'Area', 'Maker', 'Checker', 'Uploader', 'Comments', 'Likes', 'App Visits', 'Sub Title'];
    }
}

