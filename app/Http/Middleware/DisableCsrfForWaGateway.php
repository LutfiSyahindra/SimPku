<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class DisableCsrfForWaGateway extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     */
    protected $except = [
        'simrs/waGateway/wa/qr-store',
        'simrs/waGateway/wa/qr-clear',
    ];
}
