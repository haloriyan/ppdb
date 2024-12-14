<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function detail($studentID) {
        $student = Student::where('id', $studentID)->with(['booking'])->first();
        $tableCols = [];
        $fields = json_decode(base64_decode($student->fields), false);
        foreach ($fields as $field) {
            if (!in_array($field->label, $tableCols)) {
                array_push($tableCols, [
                    'key' => $field->key,
                    'label' => $field->label,
                ]);
            }
        }

        return view('admin.booking.detail', [
            'student' => $student,
            'fields' => $fields,
            'tableCols' => $tableCols,
        ]);
    }
}
