<?php

namespace App\Imports;

use App\Geometry;
use Faker\Generator;
use Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Session;

class GeometriesImport implements ToModel, WithHeadingRow
{
    use Importable;
    private $begin_id, $i;
    public function __construct()
    {
        $this->begin_id = Session::get('begin_w_id');
        $this->i = 0;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $indexes = Session::get('w_indexes');
        if (!empty($indexes)) {
            return new Geometry([
                'geotype' => $row['geotype'],
                'coordinates' => $row['coordinates'],
                'wildernesses_id' => $indexes[$this->i++]
            ]);
        }
    }
}
