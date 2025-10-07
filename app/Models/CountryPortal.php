<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryPortal extends Model
{
    use HasFactory;

    protected $table = 'country_portals';

    protected $fillable = [
        'country_name',
        'country_code',
        'anlytics_code',
        'analytics_slug',
        'country_name_locale',
        'slogan',
        'locale_lang',
        'maps',
        'embassy_link',
        'weather',
        'news',
        'local_metrics',
        'timezone',
        'important_links'
    ];

    protected $casts = [
        // Remove 'weather' from casts since we're storing widget code, not JSON
        'news' => 'array',
        'local_metrics' => 'array',
        'important_links' => 'array'
    ];

    /**
     * Get the bilateral portals where this country is the primary country
     */
    public function primaryBilateralPortals()
    {
        return $this->hasMany(ByletralPortal::class, 'primary_country_id');
    }

    /**
     * Get the bilateral portals where this country is the secondary country
     */
    public function secondaryBilateralPortals()
    {
        return $this->hasMany(ByletralPortal::class, 'secondary_country_id');
    }

    /**
     * Get all bilateral portals (both primary and secondary) for this country
     */
    public function allBilateralPortals()
    {
        return ByletralPortal::where('primary_country_id', $this->id)
            ->orWhere('secondary_country_id', $this->id);
    }

    /**
     * Scope to filter by country code
     */
    public function scopeByCountryCode($query, $countryCode)
    {
        return $query->where('country_code', $countryCode);
    }

    /**
     * Scope to filter by locale language
     */
    public function scopeByLocaleLanguage($query, $locale)
    {
        return $query->where('locale_lang', $locale);
    }

    /**
     * Get formatted country name with locale
     */
    public function getFormattedCountryNameAttribute()
    {
        return $this->country_name_locale ?: $this->country_name;
    }
}
