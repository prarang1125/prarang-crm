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
        Schema::create('country_portals', function (Blueprint $table) {
            $table->id();
            $table->string('country_name');
            $table->string('country_code');
            $table->string('anlytics_code')->nullable();
            $table->string('analytics_slug')->nullable();
            $table->string('country_name_locale')->nullable();
            $table->string('slogan')->nullable();
            $table->string('locale_lang')->nullable();
            $table->string('maps')->nullable();
            $table->longText('weather')->nullable();
            $table->longText('news')->nullable();
            $table->longText('local_metrics')->nullable();
            $table->longText('embassy_links')->nullable();
            $table->string('timezone')->nullable();
            $table->longText('important_links')->nullable();
            $table->timestamps();

            // Add indexes for commonly queried fields
            $table->index('country_code');
            $table->index('locale_lang');
            $table->index('analytics_slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('country_portals');
    }
};
