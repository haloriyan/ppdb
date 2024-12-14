<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Coupon;
use App\Models\Otp;
use App\Models\Student;
use App\Models\StudentField;
use App\Models\Wave;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class StudentController extends Controller
{
    public function auth(Request $request) {
        if ($request->method() == "POST") {
            $student = Student::where('phone', $request->phone)->first();
            if ($student == null) {
                $student = Student::create([
                    'phone' => $request->phone,
                ]);
            } else {
                $clearOtps = Otp::where('student_id', $student->id)->delete();
            }
            
            $loggingIn = Auth::guard('student')->loginUsingId($student->id);
            $timestamp = Carbon::now()->addMinutes(30)->format('Y-m-d H:i:s');

            $code = rand(1111, 9999);
            $createOtp = Otp::create([
                'student_id' => $student->id,
                'code' => $code,
                'expiry' => $timestamp,
                'has_used' => false,
                'last_used' => $timestamp,
            ]);
            
            $sendOtp = Http::post(env('WA_URL') . '/send-message', [
                'client_id' => env('WA_CLIENT_ID'),
                'number' => "62".$student->phone,
                'message' => "Kode OTP Anda : *" . $code . "*"
            ]);

            return redirect()->route('student.otp');
        } else {
            $me = me('student');
            if ($me != null) {
                $o = Otp::where('student_id', $me->id)->orderBy('created_at', 'DESC');
                $otp = $o->first();
                $diffInMinutes = Carbon::createFromDate($otp->last_used)->diffInMinutes(Carbon::now());
                if ($otp->has_used == false || $diffInMinutes > 15) {
                    // return redirect()->route('student.auth');
                } else {
                    $o->update([
                        'last_used' => Carbon::now()->format('Y-m-d H:i:s')
                    ]);
                    return redirect()->route('student.dashboard');
                }
            }
            return view('student.auth');
        }
    }
    public function logout() {
        $loggingOut = Auth::guard('student')->logout();
        return redirect()->route('student.auth');
    }
    public function otp(Request $request) {
        $me = me('student');
        if ($request->method() == "POST") {
            $o = Otp::where('student_id', $me->id)->orderBy('created_at', 'DESC');
            $otp = $o->first();
            $code = implode("", $request->code);

            if ($otp->code == $code) {
                $o->update([
                    'has_used' => true,
                    'last_used' => Carbon::now()->format('Y-m-d H:i:s'),
                ]);

                return redirect()->route('student.dashboard');
            } else {
                return redirect()->route('student.otp')->withErrors(['Kode OTP Salah!']);
            }
        } else {
            $message = Session::get('message');

            return view('student.otp', [
                'me' => $me,
                'message' => $message,
            ]);
        }
    }
    public function resendOtp() {
        return redirect()->route('student.otp')->with([
            'message' => "Berhasil mengirimkan kembali kode OTP Anda"
        ]);
    }

    public function dashboard() {
        $me = me('student');

        if ($me->fields == null || $me->name == null) {
            $fields = StudentField::orderBy('priority', 'DESC')
            // ->orderBy('updated_at', 'DESC')
            ->get();

            return view('student.pendaftaran', [
                'fields' => $fields,
            ]);
        } else {
            $booking = Booking::where('student_id', $me->id)->with(['wave'])->first();
            if ($booking == null) {
                $waves = Wave::where([
                    ['start_date', '<=', Carbon::today()],
                    ['end_date', '>=', Carbon::today()],
                    ['quantity', '>', 0]
                ])
                ->orderBy('end_date', 'ASC')
                ->get();

                return view('student.pilih_gelombang', [
                    'waves' => $waves,
                    'me' => $me,
                ]);
            } else {
                return view('student.dashboard', [
                    'isUsingHeader' => true,
                    'booking' => $booking,
                    'me' => $me,
                ]);
            }
        }
    }
    public function pendaftaran(Request $request) {
        $fields = StudentField::orderBy('priority', 'DESC')
        ->get();

        $me = me('student');
        $studentFields = [];
        $stu = Student::where('id', $me->id);
        $student = $stu->first();

        foreach ($fields as $field) {
            $value = null;
            if ($field->type == "FILE") {
                if ($request->hasFile($field->key)) {
                    $file = $request->file($field->key);
                    $value = time()."_".$file->getClientOriginalName();
                    $file->move(
                        public_path('storage/pendaftaran_files/' . $student->id),
                        $value,
                    );
                } else {
                    if ($field->required == 1) {
                        return redirect()->back()->withErrors([
                            'Wajib menyertakan ' . $field->label
                        ]);
                    }
                }
            } else {
                $value = $request->{$field->key};
            }
            unset($field->created_at, $field->updated_at);
            $field->value = $value;
            array_push($studentFields, $field);
        }

        $stu->update([
            'name' => $request->name,
            'fields' => base64_encode(json_encode($studentFields)),
        ]);

        return redirect()->route('student.dashboard');
    }
    public function pilihGelombang(Request $request) {
        $waveID = $request->wave_id;
        $wave = Wave::where('id', $request->wave_id)->first();

        if ($wave->quantity > 0) {
            $student = me('student');
            $paymentStatus = $wave->price == 0 ? "SETTLEMENT" : null;
            $booking = Booking::create([
                'student_id' => $student->id,
                'wave_id' => $wave->id,
                'payment_status' => $paymentStatus,
                'total_pay' => $wave->price,
            ]);

            return redirect()->route('student.dashboard');
        }

        return redirect()->route('student.dashboard')->withErrors([
            'Maaf, terjadi kesalahan saat memilih gelombang'
        ]);
    }
    public function pembayaran() {
        $me = me('student');
        $booking = Booking::where('student_id', $me->id)->with(['wave'])->first();
        $message = Session::get('message');

        return view('student.pembayaran', [
            'me' => $me,
            'booking' => $booking,
            'message' => $message,
        ]);
    }
    public function pay($paymentKey) {
        $me = me('student');
        $boo = Booking::where('student_id', $me->id);
        $booking = $boo->with(['wave'])->first();
        $method = null;

        foreach (config('midtrans') as $midtrans) {
            if ($midtrans['key'] == $paymentKey) {
                $method = $midtrans;
            }
        }

        $paymentPayload = [
            'payment_type' => $method['payment_type'],
            'transaction_details' => [
                'gross_amount' => $booking->total_pay,
                'order_id' => "PPDB_" . $booking->id . "_" , rand(1111, 9999),
            ],
        ];

        $paymentPayload = array_merge($paymentPayload, $method['payload']);

        $charge = Http::withBasicAuth(
            env('MIDTRANS_SERVER_KEY'), ''
        )->post('https://api.sandbox.midtrans.com/v2/charge', $paymentPayload);

        $midtransResponse = json_decode($charge->body(), false);

        $boo->update([
            'payment_payload' => json_encode($midtransResponse),
            'payment_status' => $midtransResponse->transaction_status,
            'payment_method' => $method['payment_type'],
            'payment_channel' => $method['key']
        ]);

        return redirect()->route('student.dashboard');
    }
    public function paymentCallback(Request $request) {
        $orderIDs = explode("_", $request->order_id);
        if ($orderIDs[0] == "PPDB") {
            // Pembayaran ppdb
            $boo = Booking::where('id', $orderIDs[1]);
            $booking = $boo->first();

            $boo->update([
                'payment_status' => $request->transaction_status,
            ]);
        }
    }
    public function useCoupon(Request $request) {
        $code = strtoupper($request->code);
        $me = me('student');
        $boo = Booking::where('student_id', $me->id);
        $booking = $boo->with(['wave'])->first();

        $coup = Coupon::where('code', $code);
        $coupon = $coup->first();
        $isAvailable = false;

        if ($coupon == null) {
            return redirect()->back()->withErrors([
                'Kode kupon tidak ditemukan'
            ]);
        } else {
            $isQuantityAvailable = $coupon->quantity > 0;
            $isActive = $coupon->is_active;
            $isStillValid = Carbon::createFromFormat('Y-m-d H:i:s', $coupon->valid_until)->greaterThanOrEqualTo(Carbon::now());

            if ($isQuantityAvailable && $isActive && $isStillValid) {
                $isAvailable = true;
            }
        }

        if (!$isAvailable) {
            return redirect()->back()->withErrors([
                'Kode kupon tidak berlaku'
            ]);
        } else {
            $total = $booking->total_pay;

            $discount = $coupon->type == "percentage" ? $coupon->amount / 100 * $total : $coupon->amount;
            $newTotal = $total - $discount;
            
            $boo->update([
                'total_pay' => $newTotal,
                'coupon_id' => $coupon->id,
            ]);
            $coup->decrement('quantity');

            return redirect()->route('student.pembayaran')->with([
                'message' => "Berhasil menerapkan kupon potongan harga"
            ]);
        }

        return $code;
    }
    public function discardCoupon(Request $request) {
        $me = me('student');
        $boo = Booking::where('student_id', $me->id);
        $booking = $boo->with(['wave', 'coupon'])->first();

        $boo->update([
            'total_pay' => $booking->wave->price,
            'coupon_id' => null,
        ]);

        $coup = Coupon::where('id', $booking->coupon_id);
        $coupon = $coup->first();
        $updateCoupon = $coup->increment('quantity');

        return redirect()->route('student.pembayaran')->with([
            'message' => "Kupon " . $coupon->code . " tidak jadi digunakan"
        ]);
    }
}
