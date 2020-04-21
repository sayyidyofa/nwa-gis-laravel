<?php

namespace App\Exports;

use App\GIS;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GISExport implements FromCollection, WithHeadings
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return GIS::all();
    }

    /**
     * @inheritDoc
     */
    public function headings(): array
    {
        return ["WILDERNESS_ID", "GEOMETRY_ID", "WILDERNESS_NAME", "BOUNDARY_STATUS", "GEOTYPE", "COORDINATES", "COLOR"];
    }
}
