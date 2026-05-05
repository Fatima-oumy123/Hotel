<?php

return [
    'name'                => env('HOTEL_NAME', 'Mon Hôtel'),
    'tax_rate'            => env('HOTEL_TAX_RATE', 18),
    'stay_tax_per_night'  => env('HOTEL_STAY_TAX', 1000),
    'currency'            => env('HOTEL_CURRENCY', 'FCFA'),
    'currency_symbol'     => env('HOTEL_CURRENCY_SYMBOL', 'F'),
    'timezone'            => env('HOTEL_TIMEZONE', 'Africa/Dakar'),
    'check_in_time'       => env('HOTEL_CHECK_IN_TIME', '14:00'),
    'check_out_time'      => env('HOTEL_CHECK_OUT_TIME', '12:00'),
    'max_advance_booking' => (int) env('HOTEL_MAX_ADVANCE_DAYS', 365),
    'cancellation_hours'  => (int) env('HOTEL_CANCELLATION_HOURS', 48),
    'lang_default'        => env('HOTEL_LANG', 'fr'),
    'address'             => env('HOTEL_ADDRESS', 'Dakar, Sénégal'),
    'phone'               => env('HOTEL_PHONE', '+221 33 000 00 00'),
    'email'               => env('HOTEL_EMAIL', 'contact@hotel.com'),
    'logo'                => env('HOTEL_LOGO', null),
];
