<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CounterController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\WaveController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'index']);

Route::get('tes', function () {
    $res = Http::post(env('WA_URL') . '/send-message', [
        'client_id' => env('WA_CLIENT_ID'),
        'number' => "6281259828618",
        'message' => "Wop yu"
    ]);

    return $res->body();
});

Route::get('berita/{slug}', [NewsController::class, 'read'])->name('news.read');

// Siswa
Route::get('logout', [StudentController::class, 'logout'])->name('student.logout');
Route::match(['GET', 'POST'], 'auth', [StudentController::class, 'auth'])->name('student.auth');

Route::group(['middleware' => "student"], function () {
    Route::match(['GET', 'POST'], 'otp', [StudentController::class, 'otp'])->name('student.otp');
    Route::get('resend-otp', [StudentController::class, 'resendOtp'])->name('student.resendOtp');
    

    Route::get('dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::post('pendaftaran', [StudentController::class, 'pendaftaran'])->name('student.pendaftaran');
    Route::get('pembayaran', [StudentController::class, 'pembayaran'])->name('student.pembayaran');
    Route::get('pay/{paymentKey}', [StudentController::class, 'pay'])->name('student.pay');
    Route::post('pilih-gelombang', [StudentController::class, 'pilihGelombang'])->name('student.pilihGelombang');
    Route::post('use-coupon', [StudentController::class, 'useCoupon'])->name('student.useCoupon');
    Route::get('discard-coupon', [StudentController::class, 'discardCoupon'])->name('student.discardCoupon');
});

Route::group(['prefix' => "admin"], function () {
    Route::match(['get', 'post'], 'login', [AdminController::class, 'login'])->name('admin.login');
    Route::get('logout', [AdminController::class, 'logout'])->name('admin.logout');

    Route::middleware(['admin'])->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        Route::group(['prefix' => "export"], function () {
            Route::get('student', [ExportController::class, 'student'])->name('admin.export.student');
        });
        
        Route::group(['prefix' => "berita"], function () {
            Route::get('/', [AdminController::class, 'news'])->name('admin.news');
            Route::get('tulis', [NewsController::class, 'create'])->name('admin.news.create');
            Route::post('store', [NewsController::class, 'store'])->name('admin.news.store');
            Route::get('/{id}/edit', [NewsController::class, 'edit'])->name('admin.news.edit');
            Route::post('/{id}/update', [NewsController::class, 'update'])->name('admin.news.update');
            Route::post('delete', [NewsController::class, 'delete'])->name('admin.news.delete');
        });

        Route::group(['prefix' => "settings"], function () {
            Route::get('basic', [AdminController::class, 'basicSettings'])->name('admin.settings.basic');
            Route::post('basic/save', [AdminController::class, 'saveBasicSettings'])->name('admin.settings.basic.save');

            Route::match(['GET', 'POST'], 'midtrans', [AdminController::class, 'midtrans'])->name('admin.settings.midtrans');
            Route::get('midtrans/toggle/{key}', [AdminController::class, 'toggleMidtrans'])->name('admin.settings.midtrans.toggle');
            Route::get('whatsapp', [AdminController::class, 'whatsapp'])->name('admin.settings.whatsapp');
            Route::get('whatsapp/disconnect', [AdminController::class, 'disconnectWhatsapp'])->name('admin.settings.whatsapp.disconnect');

            Route::group(['prefix' => "isian-siswa"], function () {
                Route::post('store', [FieldController::class, 'store'])->name('admin.settings.field.store');
                Route::get('{id}/required', [FieldController::class, 'required'])->name('admin.settings.field.required');
                Route::get('/', [AdminController::class, 'studentField'])->name('admin.settings.field');
            });

            Route::group(['prefix' => "counter"], function () {
                Route::post('store', [CounterController::class, 'store'])->name('admin.settings.counter.store');
                Route::post('update', [CounterController::class, 'update'])->name('admin.settings.counter.update');
                Route::post('delete', [CounterController::class, 'delete'])->name('admin.settings.counter.delete');
                Route::get('/', [AdminController::class, 'counter'])->name('admin.settings.counter');
            });
        });

        Route::group(['prefix' => "gelombang"], function () {
            Route::get('create', [WaveController::class, 'create'])->name('admin.wave.create');
            Route::post('store', [WaveController::class, 'store'])->name('admin.wave.store');
            Route::get('{id}/edit', [WaveController::class, 'edit'])->name('admin.wave.edit');
            Route::post('{id}/update', [WaveController::class, 'update'])->name('admin.wave.update');
            Route::post('delete', [WaveController::class, 'delete'])->name('admin.wave.delete');
            Route::get('/', [AdminController::class, 'wave'])->name('admin.wave');
        });

        Route::group(['prefix' => "kupon"], function () {
            Route::post('store', [CouponController::class, 'store'])->name('admin.coupon.store');
            Route::post('update', [CouponController::class, 'update'])->name('admin.coupon.update');
            Route::post('delete', [CouponController::class, 'delete'])->name('admin.coupon.delete');
            Route::get('{id}/toggle', [CouponController::class, 'toggle'])->name('admin.coupon.toggle');
            Route::get('/', [AdminController::class, 'coupon'])->name('admin.coupon');
        });

        Route::group(['prefix' => "pendaftaran"], function () {
            Route::group(['prefix' => "{id}"], function () {
                Route::get('detail', [BookingController::class, 'detail'])->name('admin.booking.detail');
            });
            Route::get('/', [AdminController::class, 'booking'])->name('admin.booking');
        });

        Route::group(['prefix' => "admin"], function () {
            Route::post('store', [AdminController::class, 'store'])->name('admin.admin.store');
            Route::post('update', [AdminController::class, 'update'])->name('admin.admin.update');
            Route::post('delete', [AdminController::class, 'delete'])->name('admin.admin.delete');
            Route::get('/', [AdminController::class, 'admin'])->name('admin.admin');
        });
    });
});