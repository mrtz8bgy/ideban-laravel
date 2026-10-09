<?php

return [
    /*
     * Online gateway: "zarinpal" (needs a merchant id), "bank" (manual bank transfer only),
     * or "fake" (testing environment only). The site never marks a card payment as paid
     * without a successful server-side verification.
     */
    'gateway' => env('PAYMENT_GATEWAY', 'bank'),

    'zarinpal' => [
        'merchant_id' => env('ZARINPAL_MERCHANT_ID'),
        'sandbox' => env('ZARINPAL_SANDBOX', false),
        // Toman to rial multiplier. Confirm the unit your ZarinPal account expects before going live.
        'amount_multiplier' => (int) env('ZARINPAL_AMOUNT_MULTIPLIER', 10),
    ],

    'bank' => [
        'name' => env('BANK_NAME', ''),
        'owner' => env('BANK_OWNER', ''),
        'card' => env('BANK_CARD', ''),
        'iban' => env('BANK_IBAN', ''),
    ],

    'receipt_max_kb' => 5120,
];
