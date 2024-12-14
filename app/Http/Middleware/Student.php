<?php

namespace App\Http\Middleware;

use App\Models\Otp;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class Student
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $myData = Auth::guard('student')->user();
        if ($myData == "") {
            return redirect()->route('student.auth');
        } else {
            $o = Otp::where('student_id', $myData->id)->orderBy('created_at', 'DESC');
            $otp = $o->first();
            $diffInMinutes = Carbon::createFromDate($otp->last_used)->diffInMinutes(Carbon::now());
            
            if ($otp->has_used == false) {
                if (Route::currentRouteName() != 'student.otp') {
                    return redirect()->route('student.auth');
                }
            } else {
                if ($otp->has_used == false || $diffInMinutes > 15) {
                    return redirect()->route('student.auth');
                } else {
                    $o->update([
                        'last_used' => Carbon::now()->format('Y-m-d H:i:s')
                    ]);
                }
            }
        }
        return $next($request);
    }
}
