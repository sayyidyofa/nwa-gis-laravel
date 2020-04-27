<?php

namespace App\Imports;

use App\Geometry;
use Faker\Generator;
use Illuminate\Validation\Rule;
use Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Session;

class GeometriesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsOnError
{
    use Importable, SkipsErrors, SkipsFailures;
    private /*$begin_id,*/ $i;
    public function __construct()
    {
        //$this->begin_id = Session::get('begin_w_id');
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
        //array_pop($indexes);
        if (!empty($indexes) && $this->i<count($indexes)) {
            //debug($indexes[$this->i]);
            //$name = $row['wilderness_name'];
            return new Geometry([
                'geotype' => $row['geotype'],
                'coordinates' => $row['coordinates'],
                'wildernesses_id' => $indexes[$this->i++]
            ]);
        }
    }

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        $before_data = Session::get('before_data');
        if (!empty($before_data)) {
            return [
                'wilderness_name' => Rule::notIn($before_data)
            ];
        }
        return null;
    }
}
