<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\WorkerExport;
use App\Imports\WorkersImport;
use Maatwebsite\Excel\Facades\Excel;

class FileController extends Controller
{
    public function export() 
    {
        return Excel::download(new WorkerExport, 'workers.xlsx');
    }

    public function import() 
    {
        Excel::import(new WorkersImport, request()->file('excel'));
        
        return response()->json([
            'message'=>'success'
        ]);
    }
}
