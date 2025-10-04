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
        Schema::create('byletral_portals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('primary_country_id');
            $table->unsignedBigInteger('secondary_country_id');
            $table->string('title');
            $table->string('slogan')->nullable();
            $table->string('slug')->unique();
            $table->string('content_country_code')->nullable();
            $table->longText('connections')->nullable();
            $table->longText('header_scripts')->nullable();
            $table->longText('footer_scripts')->nullable();
            $table->string('header_image')->nullable();
            $table->string('footer_image')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('primary_country_id')->references('id')->on('country_portals')->onDelete('cascade');
            $table->foreign('secondary_country_id')->references('id')->on('country_portals')->onDelete('cascade');

            // Add indexes for commonly queried fields
            $table->index('slug');
            $table->index('content_country_code');
            $table->index(['primary_country_id', 'secondary_country_id']);
            
            // Ensure a country pair doesn't have duplicate portals
            $table->unique(['primary_country_id', 'secondary_country_id'], 'unique_country_pair');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('byletral_portals');
    }
};
