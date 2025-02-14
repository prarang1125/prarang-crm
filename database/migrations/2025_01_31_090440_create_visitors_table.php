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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();  // Auto incrementing ID
            $table->integer('post_id');
            $table->string('post_city');
            $table->string('ip_address');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('language');
            $table->string('screen_width');
            $table->string('screen_height');
            $table->integer('visit_count');
            $table->string('visitor_city');
            $table->text('visitor_address');
            $table->text('user_agent');
            $table->string('current_url', 500);
            $table->string('referrer');
            $table->integer('duration');
            $table->string('scroll');
            $table->string('user_type');
            $table->timestamps();  // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
