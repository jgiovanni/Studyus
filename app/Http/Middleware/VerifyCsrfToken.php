<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        '/vendor/froala-editor/js/plugins/wiris/integration/showimage.php',
        '/vendor/froala-editor/js/plugins/wiris/integration/service.php',
    ];
}
