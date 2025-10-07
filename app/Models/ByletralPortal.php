<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ByletralPortal extends Model
{
    use HasFactory;

    protected $table = 'byletral_portals';

    protected $fillable = [
        'primary_country_id',
        'secondary_country_id',
        'title',
        'slogan',
        'slug',
        'content_country_code',
        'connections',
        'header_scripts',
        'footer_scripts',
        'header_image',
        'footer_image'
    ];

    protected $casts = [];

    /**
     * Get the primary country for this bilateral portal
     */
    public function primaryCountry()
    {
        return $this->belongsTo(CountryPortal::class, 'primary_country_id');
    }

    /**
     * Get the secondary country for this bilateral portal
     */
    public function secondaryCountry()
    {
        return $this->belongsTo(CountryPortal::class, 'secondary_country_id');
    }

    /**
     * Get both countries involved in this bilateral portal
     */
    public function countries()
    {
        return [
            'primary' => $this->primaryCountry,
            'secondary' => $this->secondaryCountry
        ];
    }

    /**
     * Scope to filter by slug
     */
    public function scopeBySlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    /**
     * Scope to filter by content country code
     */
    public function scopeByContentCountryCode($query, $countryCode)
    {
        return $query->where('content_country_code', $countryCode);
    }

    /**
     * Scope to find portals involving a specific country (either primary or secondary)
     */
    public function scopeInvolvingCountry($query, $countryId)
    {
        return $query->where('primary_country_id', $countryId)
            ->orWhere('secondary_country_id', $countryId);
    }

    /**
     * Get the route key name for model binding
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get formatted title with country names
     */
    public function getFormattedTitleAttribute()
    {
        $primary = $this->primaryCountry?->country_name ?? 'Unknown';
        $secondary = $this->secondaryCountry?->country_name ?? 'Unknown';

        return $this->title ?: "{$primary} - {$secondary} Portal";
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // Ensure slug is unique and properly formatted
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title);
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('title') && empty($model->slug)) {
                $model->slug = Str::slug($model->title);
            }
        });
    }
}
