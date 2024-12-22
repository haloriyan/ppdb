<?php

namespace App\Http\Controllers;

use App\Exports\StudentExport;
use App\Exports\TesExport;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function student() {
        $students = Student::whereNotNull('fields')->orderBy('created_at', 'DESC')->get();
        $timestamp = Carbon::now()->format('Y-m-d_H:i:s');
        $filename = "Data-Siswa_Exported-at-" . $timestamp . ".xlsx";

        return Excel::download(
            new TesExport([
                'students' => $students,
            ]),
            $filename
        );
    }
}
