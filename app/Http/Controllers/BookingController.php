<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Student;
use App\Notifications\Accept;
use App\Notifications\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

    public function accept($studentID) {
        $stu = Student::where('id', $studentID);
        $student = $stu->first();

        $updateStatus = Booking::where('student_id', $studentID)->update([
            'is_accepted' => true,
        ]);

        if (is_numeric($student->phone)) {
            $sendOtp = Http::post(env('WA_URL') . '/send-message', [
                'client_id' => env('WA_CLIENT_ID'),
                'number' => "62".$student->phone,
                'message' => "Selamat, " . $student->name . "! Anda dinyatakan diterima di " . env('APP_NAME')
            ]);
        } else {
            $student->notify(new Accept([
                'student' => $student
            ]));
        }

        return redirect()->route('admin.booking');
    }
}
