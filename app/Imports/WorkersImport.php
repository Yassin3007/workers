<?php

namespace App\Imports;

use App\Models\Worker;
use Maatwebsite\Excel\Concerns\ToModel;

class WorkersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Worker([
            'id'=>$row[0],
            'name'=>$row[1],
            'email'=>$row[2],
            'password'=>$row[3],
            'phone'=>$row[3],
            'location'=>$row[5],
        ]);
    }
}
