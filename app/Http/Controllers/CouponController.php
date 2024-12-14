<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function store(Request $request) {
        $saveData = Coupon::create([
            'wave_id' => $request->wave_id,
            'code' => $request->code,
            'type' => $request->type,
            'valid_until' => $request->valid_until,
            'amount' => $request->amount,
            'quantity' => $request->quantity,
            'start_quantity' => $request->quantity,
            'is_active' => true,
        ]);

        return redirect()->route('admin.coupon')->with([
            'messgage' => "Berhasil membuat kupon diskon"
        ]);
    }
    public function toggle($id) {
        $c = Coupon::where('id', $id);
        $coupon = $c->first();

        $c->update([
            'is_active' => $coupon->is_active ? false : true
        ]);

        return redirect()->back();
    }
}
