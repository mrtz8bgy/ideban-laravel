<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies;

    public function __construct()
    {
        // TRUSTED_PROXIES=* or a comma-separated list of proxy IPs. Empty = trust no proxy.
        $value = trim((string) env('TRUSTED_PROXIES', ''));
        if ($value === '*') {
            $this->proxies = '*';
        } elseif ($value !== '') {
            $this->proxies = array_values(array_filter(array_map('trim', explode(',', $value))));
        }
    }

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;
}
