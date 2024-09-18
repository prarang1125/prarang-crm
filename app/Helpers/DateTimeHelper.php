<?php
use Illuminate\Support\Facades\Auth;

if (!function_exists('getUserCurrentTime')) {
    /**
     * Get the current date and time in the user's timezone.
     *
     * @return string
     */
    function getUserCurrentTime()
    {
        $user = Auth::guard('admin')->user();
        $userTimezone = $user ? $user->timezone : 'Asia/Kolkata'; // Default timezone

        $validTimezones = \DateTimeZone::listIdentifiers(\DateTimeZone::ALL);

        // Validate the timezone
        if (!in_array($userTimezone, $validTimezones)) {
            $userTimezone = 'Asia/Kolkata';
        }

        $currentDateTime = new \DateTime('now', new \DateTimeZone($userTimezone));
        return $currentDateTime->format('Y-m-d H:i:s');
    }
}
