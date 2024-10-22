<?php
use Illuminate\Support\Facades\Auth;

if (!function_exists('calculatePercentage')) {
    /**
     * Get the current date and time in the user's timezone.
     *
     * @return string
     */
    function calculatePercentage($current, $upperLimit) {
        if ($upperLimit == 0) {
            return 0;
        }
        return min(($current / $upperLimit) * 100, 100); // Ensure it never exceeds 100%
    }
}
