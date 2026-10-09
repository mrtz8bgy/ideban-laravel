<?php

namespace App\Services\Payments;

use App\Models\Payment;

/** Used only by automated tests. Never bound outside the testing environment. */
class FakeGateway implements PaymentGateway
{
    public function request(Payment $payment, string $callbackUrl, string $description): ?array
    {
        $authority = 'FAKE-'.$payment->id.'-'.bin2hex(random_bytes(4));

        return ['authority' => $authority, 'redirect_url' => $callbackUrl.'?Authority='.$authority.'&Status=OK'];
    }

    public function verify(Payment $payment, string $authority): ?string
    {
        return str_starts_with($authority, 'FAKE-') ? 'FAKE-REF-'.$payment->id : null;
    }
}
