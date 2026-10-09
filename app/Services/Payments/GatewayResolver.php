<?php

namespace App\Services\Payments;

class GatewayResolver
{
    /** Returns the configured online gateway, or null when online payment is not available. */
    public static function online(): ?PaymentGateway
    {
        $name = config('payments.gateway');

        if ($name === 'zarinpal') {
            $merchant = (string) config('payments.zarinpal.merchant_id');

            return $merchant === ''
                ? null
                : new ZarinPalGateway($merchant, (bool) config('payments.zarinpal.sandbox'), (int) config('payments.zarinpal.amount_multiplier', 10));
        }

        if ($name === 'fake' && app()->environment('testing')) {
            return new FakeGateway();
        }

        return null;
    }
}
