<?php

namespace App\Services\Payments;

use App\Models\Payment;
use Illuminate\Support\Facades\Http;

/**
 * ZarinPal REST API v4. Requests and verification are always made from the server.
 */
class ZarinPalGateway implements PaymentGateway
{
    public function __construct(private string $merchantId, private bool $sandbox, private int $multiplier)
    {
    }

    private function base(): string
    {
        return $this->sandbox ? 'https://sandbox.zarinpal.com' : 'https://payment.zarinpal.com';
    }

    public function request(Payment $payment, string $callbackUrl, string $description): ?array
    {
        $response = Http::timeout(15)->acceptJson()->post($this->base().'/pg/v4/payment/request.json', [
            'merchant_id' => $this->merchantId,
            'amount' => (int) $payment->amount * $this->multiplier,
            'callback_url' => $callbackUrl,
            'description' => mb_substr($description, 0, 200),
        ]);

        $authority = data_get($response->json(), 'data.authority');
        if (!$response->successful() || (int) data_get($response->json(), 'data.code') !== 100 || !$authority) {
            return null;
        }

        return ['authority' => (string) $authority, 'redirect_url' => $this->base().'/pg/StartPay/'.$authority];
    }

    public function verify(Payment $payment, string $authority): ?string
    {
        $response = Http::timeout(15)->acceptJson()->post($this->base().'/pg/v4/payment/verify.json', [
            'merchant_id' => $this->merchantId,
            'amount' => (int) $payment->amount * $this->multiplier,
            'authority' => $authority,
        ]);

        $code = (int) data_get($response->json(), 'data.code');
        if (!$response->successful() || !in_array($code, [100, 101], true)) {
            return null;
        }

        return (string) data_get($response->json(), 'data.ref_id', 'verified');
    }
}
