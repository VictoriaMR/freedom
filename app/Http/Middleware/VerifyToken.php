<?php

namespace App\Http\Middleware;

class VerifyToken
{
    protected static $except = [
    ];

    protected static $exceptNotToken = [
    ];

    protected static $exceptNotAgreement = [
    ];

    public function handle()
    {
    }

    /**
     * Determine if the request has a URI that should pass through token verification.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    protected static function inExceptArray($route)
    {
    }

    protected static function inExceptByNotTokenArray($request)
    {
    }

    protected static function inExceptByNotAgreementArray($request)
    {
    }

}
