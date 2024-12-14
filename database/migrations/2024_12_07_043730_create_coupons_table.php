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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('wave_id')->unsigned()->index()->nullable();
            $table->foreign('wave_id')->references('id')->on('waves')->onDelete('set null');
            $table->string('code');
            $table->string('type'); // AMOUNT, PERCENTAGE
            $table->bigInteger('amount');
            $table->dateTime('valid_until');
            $table->integer('quantity');
            $table->integer('start_quantity');
            $table->boolean('is_active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
