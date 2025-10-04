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
        Schema::table('country_portals', function (Blueprint $table) {
            $table->dropColumn('embassy_links');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('country_portals', function (Blueprint $table) {
            $table->longText('embassy_links')->nullable()->after('local_metrics');
        });
    }
};
