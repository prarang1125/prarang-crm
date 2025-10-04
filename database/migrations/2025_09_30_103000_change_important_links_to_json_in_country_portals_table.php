<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, convert any existing data to valid JSON format
        $portals = DB::table('country_portals')->whereNotNull('important_links')->get();
        
        foreach ($portals as $portal) {
            if (!empty($portal->important_links)) {
                // Check if it's already valid JSON
                $decoded = json_decode($portal->important_links);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    // If not valid JSON, set to null or try to fix it
                    DB::table('country_portals')
                        ->where('id', $portal->id)
                        ->update(['important_links' => null]);
                }
            }
        }

        // Now change the column type to JSON
        Schema::table('country_portals', function (Blueprint $table) {
            $table->json('important_links')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('country_portals', function (Blueprint $table) {
            $table->longText('important_links')->nullable()->change();
        });
    }
};
