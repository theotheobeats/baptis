<?php

namespace App\Imports;

use App\Models\Due;
use App\Models\DueDayOff;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class DueImport implements ToModel, WithStartRow
{
    public function model(array $row)
    {   
        if($row[1] !== null) {
            $due = Due::where("name", $row[1])->first();
            if($due == null) {
                $due = new Due();
                $due->name = $row[1];
                $due->save();
            }
        }

        // Start the process of inputting or updating due data
        $due_day_off = DueDayOff::withTrashed()->find($row[0]);
        if ($due_day_off !== null) {
            $due_day_off->due_id = $due->id;
            $due_day_off->day_off_date = $row[2];
            $due_day_off->save();
        } else {
            $due_day_off = new DueDayOff();
            $due_day_off->due_id = $due->id;
            $due_day_off->day_off_date = $row[2];
            $due_day_off->save();
        }

        return $due_day_off;
    }

    public function startRow(): int
    {
        return 2;
    }
}
