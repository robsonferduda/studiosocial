<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class OcorrenciasExport implements FromCollection
{
    public function collection()
    {
        return $this->data;
    }
}
