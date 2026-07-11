<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Ticketing Configuration
    |--------------------------------------------------------------------------
    | Semua nilai dinamis untuk sistem tiket, bisa diubah dari .env
    | tanpa perlu mengubah kode.
    */

    'tax_rate' => env('TICKETING_TAX_RATE', 0.11),

    'service_fee_rate' => env('TICKETING_SERVICE_FEE_RATE', 0.05),

    'timezone_label' => env('TICKETING_TIMEZONE_LABEL', 'WIB'),

    'doors_open_offset_hours' => (int) env('TICKETING_DOORS_OPEN_OFFSET_HOURS', 2),

    'order_prefix' => env('TICKETING_ORDER_PREFIX', 'FSTV-'),

    'ticket_prefix' => env('TICKETING_TICKET_PREFIX', 'TKT-'),

    'midtrans_order_prefix' => env('TICKETING_MIDTRANS_ORDER_PREFIX', 'ORDER-'),

    'ticket_number_prefix' => env('TICKETING_TICKET_NUMBER_PREFIX', 'TKT'),
    'ticket_number_padding' => 6,

    'qr_size' => 300,
    'qr_format' => 'png',
    'qr_error_correction' => 'H',
];
