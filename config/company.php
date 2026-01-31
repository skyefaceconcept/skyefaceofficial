<?php

return [
    /* Company display name used across the app and emails */
    'name' => env('COMPANY_NAME', env('APP_NAME', 'Skyeface')),

    /* Company contact information */
    'address' => env('COMPANY_ADDRESS', 'I40, Kojomoonu Street, Ijagbo, Kwara State, Nigeria.'),
    'phone' => env('COMPANY_PHONE', '+234 806 212 1410'),
    'email' => env('COMPANY_EMAIL', env('MAIL_FROM_ADDRESS', 'support@example.com')),

    /* Support contact defaults (fall back to values in .env) */
    'support_phone' => env('SUPPORT_PHONE', '+000 000 0000'),
    'support_email' => env('SUPPORT_EMAIL', env('MAIL_FROM_ADDRESS', 'support@example.com')),

    /* Working hours */
    'working_hours' => [
        'weekday_start' => env('WORKING_HOURS_WEEKDAY_START', '9:00 AM'),
        'weekday_end' => env('WORKING_HOURS_WEEKDAY_END', '6:00 PM'),
        'saturday_start' => env('WORKING_HOURS_SATURDAY_START', '10:00 AM'),
        'saturday_end' => env('WORKING_HOURS_SATURDAY_END', '4:00 PM'),
    ],

    /* Logo and branding - use CompanyHelper to fetch from database or env fallback */
];
