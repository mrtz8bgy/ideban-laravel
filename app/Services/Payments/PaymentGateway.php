<?php

namespace App\Services\Payments;

use App\Models\Payment;

interface PaymentGateway
{
    /** Creates a payment request. Returns ['authority' => string, 'redirect_url' => string] or null. */
    public function request(Payment $payment, string $callbackUrl, string $description): ?array;

    /** Verifies a returned payment on the server. Returns the gateway reference id, or null when not verified. */
    public function verify(Payment $payment, string $authority): ?string;
}
