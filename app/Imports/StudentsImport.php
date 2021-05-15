<?php

namespace App\Imports;

use App\Student;
use Maatwebsite\Excel\Concerns\ToModel;

class StudentsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
      
        return new Student([
            'name'  => $row['0'],
            'gender'   => $row['1'],
            'address'   => $row['2'],
            'department'    => $row['3'],
            'branch'  => $row['4'],
        ]);
    }
}
