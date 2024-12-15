<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Booking;
use App\Models\Counter;
use App\Models\Coupon;
use App\Models\News;
use App\Models\Student;
use App\Models\StudentField;
use App\Models\Wave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public static function me() {
        return Auth::guard('admin')->user();
    }
    public function login(Request $request) {
        if ($request->method() == "POST") {
            $loggingIn = Auth::guard('admin')->attempt([
                'username' => $request->username,
                'password' => $request->password,
            ]);

            if ($loggingIn) {
                $redirectTo = $request->r == "" ? route('admin.dashboard') : $request->r;
                return redirect($redirectTo);
            } else {
                return redirect()->route('admin.login')->withErrors(['Kombinasi username dan password tidak tepat']);
            }
        } else {
            $message = Session::get('message');

            return view('admin.login', [
                'request' => $request,
                'message' => $message,
            ]);
        }
    }
    public function logout() {
        $loggingOut = Auth::guard('admin')->logout();

        return redirect()->route('admin.login')->with([
            'message' => "Berhasil logout"
        ]);
    }
    
    public function dashboard(Request $request) {
        $myData = Auth::guard('admin')->user();
        $students = Student::all(['id']);
        $bookings = Booking::all(['id']);
        $paidBooking = Booking::where('payment_status', 'settlement')->with(['wave'])->get();
        $revenue = 0;

        foreach ($paidBooking as $book) {
            $revenue += $book->total_pay;
        }

        // Chart
        $colors = ['rgba(33, 150, 243, 1)', 'rgba(231, 76, 60, 1)', 'rgba(46, 204, 113, 1)', 'rgba(252, 216, 64, 1)', 'rgba(52, 73, 94, 1)'];
        $gelombangChart = [
            'labels' => [],
            'datasets' => [
                [
                    'label' => "Jumlah Siswa",
                    'data' => [],
                    'backgroundColor' => [],
                    'borderWidth' => 0
                ]
            ]
        ];

        $waves = Wave::all();
        foreach ($waves as $w => $wave) {
            $waveCount = Booking::where('wave_id', $wave->id)->get(['id'])->count();
            array_push($gelombangChart['labels'], $wave->name);
            array_push($gelombangChart['datasets'][0]['data'], $waveCount);
            array_push($gelombangChart['datasets'][0]['backgroundColor'], $colors[$w]);
        }

        $latestStudents = Student::whereNotNull('name')->orderBy('created_at', 'DESC')->take(5)->get();

        return view('admin.dashboard', [
            'myData' => $myData,
            'students' => $students,
            'latestStudents' => $latestStudents,
            'bookings' => $bookings,
            'revenue' => $revenue,
            'gelombangChart' => $gelombangChart,
        ]);
    }
    public function news(Request $request) {
        $myData = Auth::guard('admin')->user();
        $posts = News::orderBy('created_at', 'DESC')->paginate(20);
        $message = Session::get('message');

        return view('admin.news.index', [
            'myData' => $myData,
            'posts' => $posts,
            'message' => $message,
        ]);
    }
    public function wave() {
        $message = Session::get('message');
        $waves = Wave::orderBy('start_date', 'ASC')->paginate(25);

        return view('admin.wave.index', [
            'message' => $message,
            'waves' => $waves,
        ]);
    }

    public function basicSettings() {
        $message = Session::get('message');
        return view('admin.settings.basic', [
            'message' => $message,
        ]);
    }
    public function saveBasicSettings(Request $request) {
        $toSave = ['APP_NAME', 'ABOUT', 'JUMBO_TITLE'];

        foreach ($toSave as $item) {
            // echo $request->{$item};
            changeEnv($item, $request->{$item});
        }

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoFileName = $logo->getClientOriginalName();
            changeEnv('APP_LOGO', $logoFileName);
            $logo->move(
                public_path('images'),
                $logoFileName,
            );
        }

        return redirect()->route('admin.settings.basic')->with([
            'message' => "Berhasil menyimpan perubahan"
        ]);
    }
    public function midtrans(Request $request) {
        $message = Session::get('message');
        
        if ($request->method() == "POST") {
            if ($request->tab == "channel") {
                return redirect()->route('admin.settings.midtrans', ['tab' => "channel"])->with([
                    'message' => "Berhasil menyimpan konfigurasi midtrans"
                ]);
            } else {
                changeEnv('MIDTRANS_MODE', $request->mode);
                changeEnv('MIDTRANS_MERCHANT_ID', $request->merchant_id);
                changeEnv('MIDTRANS_SERVER_KEY', $request->server_key);
                changeEnv('MIDTRANS_CLIENT_KEY', $request->client_key);
                sleep(1);

                return redirect()->route('admin.settings.midtrans')->with([
                    'message' => "Berhasil menyimpan konfigurasi midtrans"
                ]);
            }
        }

        return view('admin.settings.midtrans', [
            'message' => $message,
            'request' => $request,
        ]);
    }
    public function toggleMidtrans($key) {
        $filePath = config_path('midtrans.php');
        $config = include $filePath;

        try {
            foreach ($config as &$item) {
                if (isset($item['key']) && $item['key'] === $key) {
                    $item['enable'] = !$item['enable'];

                    // Write the updated config back to the file
                    $configExport = "<?php\n\nreturn [\n";
                    foreach ($config as $configItem) {
                        $configExport .= "    [\n";
                        foreach ($configItem as $key => $value) {
                            if (is_array($value)) {
                                $value = var_export($value, true);
                                $value = str_replace(["array (", ")"], ["[", "]"], $value);
                                $value = str_replace("\n", "\n        ", $value);
                            } else {
                                $value = var_export($value, true);
                            }
                            $configExport .= "        '{$key}' => {$value},\n";
                        }
                        $configExport .= "    ],\n";
                    }
                    $configExport .= "];\n";

                    file_put_contents($filePath, $configExport);
                    sleep(3);

                    return redirect()->route('admin.settings.midtrans', ['tab' => "channel"])->with([
                        'message' => "Channel pembayaran diperbarui.",
                    ]);
                }
            }

            throw new \Exception("Key '{$key}' not found in the configuration.");
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }
    public function whatsapp() {
        $clientID = env('WA_CLIENT_ID');
        $qrSource = null;

        if ($clientID == null || env('WA_CLIENT_NUMBER') == null) {
            $clientID = rand(111111, 999999);
            $init = Http::post(env('WA_URL') . "/initialize", [
                'client_id' => $clientID,
                'callback_url' => env('BASE_URL') . "/api/callback/whatsapp"
            ]);

            $init = json_decode($init, false);
            changeEnv('WA_CLIENT_ID', $clientID);
            sleep(1);

            return view('admin.settings.whatsapp.connect', [
                'client_id' => $clientID,
                'init' => $init,
            ]);
        } else {
            $init = ['qr' => null];
            $init = json_decode(json_encode($init), false);

            return view('admin.settings.whatsapp.ready', [
                'client_id' => $clientID,
                'init' => $init,
            ]);
        }
    }
    public function disconnectWhatsapp() {
        $res = Http::post(env('WA_URL') . "/disconnect", [
            'client_id' => env('WA_CLIENT_ID'),
        ]);

        Log::info($res->body());
        changeEnv('WA_CLIENT_ID', NULL);
        changeEnv('WA_CLIENT_NAME', NULL);
        changeEnv('WA_CLIENT_NUMBER', NULL);

        return redirect()->route('admin.settings.whatsapp');
    }
    public function whatsappCallback(Request $request) {
        changeEnv('WA_CLIENT_NAME', $request->name);
        changeEnv('WA_CLIENT_NUMBER', $request->number);
    }
    public function studentField() {
        $message = Session::get('message');
        $fields = StudentField::orderBy('priority', 'DESC')
        // ->orderBy('updated_at', 'DESC')
        ->get();

        return view('admin.settings.fields.index', [
            'message' => $message,
            'fields' => $fields,
        ]);
    }
    public function coupon(Request $request) {
        $message = Session::get('message');
        $waves = Wave::where('price', '>', 0)->orderBy('start_date', 'ASC')->get();
        $coup = Coupon::orderBy('valid_until', 'ASC');
        $bookingsWithCoupon = Booking::whereNotNull('coupon_id')->with(['wave'])->get();
        $usageAmount = 0;

        foreach ($bookingsWithCoupon as $book) {
            $realPrice = $book->wave->price;
            $toPay = $book->total_pay;
            $usageAmount += $realPrice - $toPay;
        }
        // return $bookingsWithCoupon;

        if ($request->q != "") {
            $coup = $coup->where('code', 'LIKE', '%'.$request->q.'%')->orWhereHas('wave', function ($query) use ($request) {
                $query->where('name', 'LIKE', '%'.$request->q.'%');
            });
        }
        
        $coupons = $coup->paginate(25);

        return view('admin.coupon', [
            'message' => $message,
            'request' => $request,
            'waves' => $waves,
            'coupons' => $coupons,
            'usage_amount' => $usageAmount,
            'bookings_with_coupon' => $bookingsWithCoupon,
        ]);
    }

    public function booking(Request $request) {
        $filter = [];
        if ($request->q != "") {
            array_push($filter, ['name', 'LIKE', '%'.$request->q.'%']);
        }

        $students = Student::where($filter)->whereNotNull('fields')->orderBy('created_at')->paginate(25);
        $tableCols = [];
        $theTableCols = [];
        foreach ($students as $student) {
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

        return view('admin.booking.index', [
            'students' => $students,
            'tableCols' => $tableCols,
            'request' => $request,
        ]);
    }
    public function counter() {
        $message = Session::get('message');
        $counters = Counter::orderBy('updated_at', 'DESC')->get();

        return view('admin.settings.counter', [
            'counters' => $counters,
            'message' => $message,
        ]);
    }

    public function admin(Request $request) {
        $filter = [];
        $message = Session::get('message');
        if  ($request->q != "") {
            array_push($filter, ['name', 'LIKE', '%'.$request->q.'%']);
        }
        $admins = Admin::where($filter)->paginate(15);

        return view('admin.admin', [
            'admins' => $admins,
            'message' => $message,
            'request' => $request,
        ]);
    }
    public function store(Request $request) {
        $saveData = Admin::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'role' => "administrator"
        ]);

        return redirect()->route('admin.admin')->with([
            'message' => "Berhasil menambahkan admin baru"
        ]);
    }
    public function update(Request $request) {
        $me = me();
        $toUpdate = [
            'name' => $request->name,
            'username' => $request->username,
        ];

        if ($request->password != "") {
            $toUpdate['password'] = bcrypt($request->password);
        }

        $updateData = Admin::where('id', $request->id)->update($toUpdate);

        if ($me->id == $request->id && $toUpdate['password']) {
            $loggingOut = Auth::guard('admin')->logout();

            return redirect()->route('admin.login')->with([
                'message' => "Mohon login kembali menggunakan password baru"
            ]);
        }
        
        return redirect()->route('admin.admin')->with([
            'message' => "Berhasil mengubah data admin"
        ]);
    }
    public function delete(Request $request) {
        $deleteData = Admin::where('id', $request->id)->delete();

        return redirect()->route('admin.admin')->with([
            'message' => "Berhasil menghapus admin"
        ]);
    }
}
