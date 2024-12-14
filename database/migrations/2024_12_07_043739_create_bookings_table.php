<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_id')->unsigned()->index();
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->bigInteger('wave_id')->unsigned()->index();
            $table->foreign('wave_id')->references('id')->on('waves')->onDelete('cascade');
            $table->bigInteger('coupon_id')->unsigned()->index()->nullable();
            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('set null');

            $table->bigInteger('total_pay')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_channel')->nullable();
            $table->longText('payment_payload')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
