<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('calculatePercentage')) {
    /**
     * Get the current date and time in the user's timezone.
     *
     * @return string
     */
    function calculatePercentage($current, $upperLimit)
    {
        if ($upperLimit == 0) {
            return 0;
        }
        return min(($current / $upperLimit) * 100, 100); // Ensure it never exceeds 100%
    }
}

if (!function_exists('portalLocaLang')) {
    function portalLocaLang($key, $locale = null)
    {
        $locale = $locale ?: app()->getLocale();

        // Normalize the path for cross-platform compatibility
        $path = resource_path("lang" . DIRECTORY_SEPARATOR . "portals" . DIRECTORY_SEPARATOR . "{$locale}.json");

        if (file_exists($path)) {
            $translations = json_decode(file_get_contents($path), true);

            return $translations[$key] ?? $key;
        }

        return $key;
    }
}


if (!function_exists('detectUserType')) {
    function detectUserType($userAgent)
    {
        // Using regex to match different bot user agents
        if (preg_match('/facebookexternalhit/i', $userAgent)) {
            return 'Facebook Bot';
        } elseif (preg_match('/Googlebot/i', $userAgent)) {
            return 'Google Bot';
        } elseif (preg_match('/Bingbot/i', $userAgent)) {
            return 'Bing Bot';
        } elseif (preg_match('/Slurp/i', $userAgent)) {
            return 'Yahoo Bot';
        } else {
            return 'User';
        }
    }
}
