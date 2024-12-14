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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('admin_id')->unsigned()->index()->nullable();
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null');
            $table->string('title', 455);
            $table->string('slug', 555);
            $table->string('featured_image', 555);
            $table->longText('content');
            $table->bigInteger('hit');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
