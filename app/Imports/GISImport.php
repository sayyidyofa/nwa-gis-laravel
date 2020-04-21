<?php

namespace App\Imports;

use App\Geometry;
use App\GIS;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;

class GISImport implements ToModel
{
    use Importable;

    /**
     * @param array $rows
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $rows)
    {
        return new Geometry([
            ''
        ]);
    }
}
