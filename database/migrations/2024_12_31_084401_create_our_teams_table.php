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
        Schema::create('our_teams', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->unsignedBigInteger('userId')->nullable(); // Nullable userId
            $table->string('profile_image'); // Path or URL to the profile image
            $table->string('display_name'); // Name to display
            $table->string('role'); // Role of the team member
            $table->string('linkedin_link')->nullable(); // Nullable LinkedIn link
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('our_teams');
    }
};
