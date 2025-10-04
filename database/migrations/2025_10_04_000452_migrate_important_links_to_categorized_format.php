<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration converts the important_links column from the old flat key-value format
     * to the new categorized format with tourist_places, community_pages, and resources arrays.
     */
    public function up(): void
    {
        // Get all country portals with important_links
        $portals = DB::table('country_portals')->whereNotNull('important_links')->get();
        
        foreach ($portals as $portal) {
            if (!empty($portal->important_links)) {
                $decoded = json_decode($portal->important_links, true);
                
                // Check if it's already in the new format
                if (is_array($decoded)) {
                    // If it has the new structure, skip
                    if (isset($decoded['tourist_places']) && is_array($decoded['tourist_places'])) {
                        continue;
                    }
                    
                    // Convert old flat format to new categorized format
                    // Old format: {"tourism": "url", "government": "url"}
                    // New format: {"tourist_places": ["url"], "community_pages": ["url"], "resources": ["url"]}
                    
                    $newFormat = [
                        'tourist_places' => [],
                        'community_pages' => [],
                        'resources' => []
                    ];
                    
                    // Categorize existing links based on key names
                    foreach ($decoded as $key => $value) {
                        $keyLower = strtolower($key);
                        
                        // Tourist places keywords
                        if (str_contains($keyLower, 'tourist') || 
                            str_contains($keyLower, 'tourism') || 
                            str_contains($keyLower, 'travel') ||
                            str_contains($keyLower, 'place') ||
                            str_contains($keyLower, 'destination') ||
                            str_contains($keyLower, 'attraction')) {
                            $newFormat['tourist_places'][] = $value;
                        }
                        // Community pages keywords
                        elseif (str_contains($keyLower, 'community') || 
                                str_contains($keyLower, 'facebook') || 
                                str_contains($keyLower, 'social') ||
                                str_contains($keyLower, 'group') ||
                                str_contains($keyLower, 'forum')) {
                            $newFormat['community_pages'][] = $value;
                        }
                        // Resources keywords (government, official, embassy, etc.)
                        elseif (str_contains($keyLower, 'resource') || 
                                str_contains($keyLower, 'government') || 
                                str_contains($keyLower, 'official') ||
                                str_contains($keyLower, 'embassy') ||
                                str_contains($keyLower, 'gov') ||
                                str_contains($keyLower, 'portal') ||
                                str_contains($keyLower, 'website')) {
                            $newFormat['resources'][] = $value;
                        }
                        // Default to resources if no match
                        else {
                            $newFormat['resources'][] = $value;
                        }
                    }
                    
                    // Update the record with the new format
                    DB::table('country_portals')
                        ->where('id', $portal->id)
                        ->update(['important_links' => json_encode($newFormat)]);
                    
                    echo "Migrated country portal ID: {$portal->id}\n";
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     * 
     * This will convert back to the old flat format, but data categorization will be lost.
     */
    public function down(): void
    {
        $portals = DB::table('country_portals')->whereNotNull('important_links')->get();
        
        foreach ($portals as $portal) {
            if (!empty($portal->important_links)) {
                $decoded = json_decode($portal->important_links, true);
                
                // Check if it's in the new categorized format
                if (is_array($decoded) && isset($decoded['tourist_places'])) {
                    $oldFormat = [];
                    
                    // Convert tourist places
                    if (isset($decoded['tourist_places']) && is_array($decoded['tourist_places'])) {
                        foreach ($decoded['tourist_places'] as $index => $url) {
                            $oldFormat["tourist_place_" . ($index + 1)] = $url;
                        }
                    }
                    
                    // Convert community pages
                    if (isset($decoded['community_pages']) && is_array($decoded['community_pages'])) {
                        foreach ($decoded['community_pages'] as $index => $url) {
                            $oldFormat["community_page_" . ($index + 1)] = $url;
                        }
                    }
                    
                    // Convert resources
                    if (isset($decoded['resources']) && is_array($decoded['resources'])) {
                        foreach ($decoded['resources'] as $index => $url) {
                            $oldFormat["resource_" . ($index + 1)] = $url;
                        }
                    }
                    
                    // Update the record with the old format
                    DB::table('country_portals')
                        ->where('id', $portal->id)
                        ->update(['important_links' => json_encode($oldFormat)]);
                }
            }
        }
    }
};
