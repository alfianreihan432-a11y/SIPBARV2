<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SiPintu API Gateway Base URL
    |--------------------------------------------------------------------------
    | URL dasar server SiPintu Identity & API Gateway.
    | Contoh: http://localhost:8000 (development)
    */
    'api_url' => env('SIPINTU_API_URL', 'http://localhost:8000'),

    /*
    |--------------------------------------------------------------------------
    | Client Credentials (Server-to-Server & OAuth 2.0)
    |--------------------------------------------------------------------------
    */
    'client_id'     => env('SIPINTU_CLIENT_ID'),
    'client_secret' => env('SIPINTU_CLIENT_SECRET'),
    'redirect_uri'  => env('SIPINTU_REDIRECT_URI', 'http://localhost:8001/oauth/callback'),

    /*
    |--------------------------------------------------------------------------
    | HTTP Request Timeout (detik)
    |--------------------------------------------------------------------------
    */
    'timeout' => env('SIPINTU_TIMEOUT', 10),

    /*
    |--------------------------------------------------------------------------
    | OAuth 2.0 Endpoints
    |--------------------------------------------------------------------------
    */
    'oauth' => [
        'authorize' => '/oauth/authorize',
        'token'     => '/oauth/token',
    ],

    /*
    |--------------------------------------------------------------------------
    | REST API Endpoints
    |--------------------------------------------------------------------------
    */
    'endpoints' => [
        'ping'            => '/api/v1/ping',
        'validate_client' => '/api/v1/validate-client',
        'user'            => '/api/v1/user',
        'students'        => '/api/v1/sijuna/students',
        'teachers'        => '/api/v1/sijuna/teachers',
    ],

];
