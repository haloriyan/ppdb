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
        Schema::create('student_fields', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->string('label');
            $table->string('type'); // INPUT, SELECT, FILE
            $table->boolean('required');
            $table->string('options', 455)->nullable();
            $table->integer('priority')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_fields');
    }
};
