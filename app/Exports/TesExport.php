<?php

namespace App\Exports;

use App\Models\Student;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TesExport implements FromView, ShouldAutoSize, WithStyles
{
    public $students;

    public function __construct($props)
    {
        $this->students = $props['students'];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('B')->getNumberFormat()->setFormatCode('@');
    }

    public function view(): View
    {
        // Retrieve users data from the database
        $tableCols = [];
        $theTableCols = [];
        foreach ($this->students as $student) {
            $fields = json_decode(base64_decode($student->fields), false);
            foreach ($fields as $field) {
                if (!in_array($field->key, $theTableCols) && $field->type != "FILE") {
                    array_push($tableCols, [
                        'key' => $field->key,
                        'label' => $field->label,
                    ]);

                    array_push($theTableCols, $field->key);
                }
            }
        }

        // Return a view with the data
        return view('exports.student', [
            'students' => $this->students,
            'tableCols' => $tableCols,
        ]);
    }
}
