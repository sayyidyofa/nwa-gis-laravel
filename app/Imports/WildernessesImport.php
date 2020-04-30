<?php

namespace App\Imports;

use App\Helpers\MiscHelper;
use App\Wilderness;
use DebugBar\DebugBar;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\BeforeImport;

class WildernessesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, WithEvents
{
    use Importable, SkipsFailures, SkipsErrors;

    private $indexes = [];
    private $old_w_data;
    /**
     * @param array $row
     * @return Wilderness
     */
    public function model(array $row)
    {
        $w = new Wilderness([
            'name' => $row['wilderness_name'],
            'boundary_status' => $row['boundary_status'],
            'color' => $row['color'] ?? (new MiscHelper)->random_color()
        ]);
        return $w;
        /*if (DB::getPDO()->lastInsertId() !== null)
            $this->indexes[] = DB::getPDO()->lastInsertId();
        return $w;*/
    }

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            'wilderness_name' => Rule::unique('wildernesses', 'name')
        ];
    }

    /**
     * @inheritDoc
     */
    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function(BeforeImport $event) { // Data dari sebelum import dan setelah import dicapture
                $this->old_w_data = Wilderness::all();             // Lalu kedua capture data tadi dibandingkan dan diambil yg berbeda, diambil id-nya, dioper ke Session
            },
            AfterImport::class => function(AfterImport $event) {
                $diffed_data = Wilderness::all()->diff($this->old_w_data);
                $before_data = Wilderness::all()->diff($diffed_data);
                \Session::put('before_data', $before_data->pluck('name')->toArray());
                $this->indexes = $diffed_data->pluck('id')->toArray();
                \Session::put('w_indexes', $this->indexes);
            }
        ];
    }
}
