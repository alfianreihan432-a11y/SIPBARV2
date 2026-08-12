<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * SipintuService
 *
 * Handles all communication with SiPintu Identity & API Gateway.
 * Supports two integration modes:
 *   1. Server-to-Server (X-Client-ID / X-Client-Secret header auth)
 *   2. OAuth 2.0 / OpenID Connect SSO (user-facing login)
 */
class SipintuService
{
    protected string $baseUrl;
    protected string $clientId;
    protected string $clientSecret;
    protected string $redirectUri;
    protected int    $timeout;

    public function __construct()
    {
        $this->baseUrl      = rtrim(config('sipintu.api_url'), '/');
        $this->clientId     = config('sipintu.client_id', '');
        $this->clientSecret = config('sipintu.client_secret', '');
        $this->redirectUri  = config('sipintu.redirect_uri', '');
        $this->timeout      = (int) config('sipintu.timeout', 10);
    }

    // ═══════════════════════════════════════════════════════════
    // PRIVATE HELPERS
    // ═══════════════════════════════════════════════════════════

    /**
     * Build HTTP client with Server-to-Server header authentication.
     */
    private function gatewayClient(): \Illuminate\Http\Client\PendingRequest
    {
        return Http::timeout($this->timeout)
            ->withHeaders([
                'X-Client-ID'     => $this->clientId,
                'X-Client-Secret' => $this->clientSecret,
                'Accept'          => 'application/json',
            ]);
    }

    /**
     * Build full URL from endpoint key in config.
     */
    private function url(string $endpointKey): string
    {
        return $this->baseUrl . config("sipintu.endpoints.{$endpointKey}", "/{$endpointKey}");
    }

    // ═══════════════════════════════════════════════════════════
    // MONITORING & HEALTH
    // ═══════════════════════════════════════════════════════════

    /**
     * Ping SiPintu Gateway — verifikasi koneksi aktif.
     * GET /api/v1/ping?client_id=...
     *
     * @return array{success: bool, online: bool, data: array|null, error: string|null}
     */
    public function ping(): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders(['Accept' => 'application/json'])
                ->get($this->url('ping'), ['client_id' => $this->clientId]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'online'  => ($data['status'] ?? '') === 'online',
                    'data'    => $data,
                    'error'   => null,
                ];
            }

            return [
                'success' => false,
                'online'  => false,
                'data'    => null,
                'error'   => "HTTP {$response->status()}: " . ($response->json('message') ?? 'Unknown error'),
            ];
        } catch (ConnectionException $e) {
            return ['success' => false, 'online' => false, 'data' => null, 'error' => 'Tidak dapat terhubung ke SiPintu — server mungkin offline.'];
        } catch (\Exception $e) {
            Log::warning('SiPintu ping error: ' . $e->getMessage());
            return ['success' => false, 'online' => false, 'data' => null, 'error' => $e->getMessage()];
        }
    }

    /**
     * Validate client credentials via SiPintu.
     * POST /api/v1/validate-client
     *
     * @return array{success: bool, data: array|null, error: string|null}
     */
    public function validateClient(): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders(['Accept' => 'application/json'])
                ->post($this->url('validate_client'), [
                    'client_id'     => $this->clientId,
                    'client_secret' => $this->clientSecret,
                ]);

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json(), 'error' => null];
            }

            return [
                'success' => false,
                'data'    => null,
                'error'   => "HTTP {$response->status()}: " . ($response->json('message') ?? 'Validasi kredensial gagal'),
            ];
        } catch (ConnectionException $e) {
            return ['success' => false, 'data' => null, 'error' => 'Tidak dapat terhubung ke SiPintu.'];
        } catch (\Exception $e) {
            Log::warning('SiPintu validateClient error: ' . $e->getMessage());
            return ['success' => false, 'data' => null, 'error' => $e->getMessage()];
        }
    }

    // ═══════════════════════════════════════════════════════════
    // METODE 1: SERVER-TO-SERVER DATA API
    // ═══════════════════════════════════════════════════════════

    /**
     * Ambil data siswa dari SIJUNA via SiPintu Gateway.
     * GET /api/v1/sijuna/students
     *
     * @param  string|null  $nis     Filter berdasarkan NIS siswa
     * @param  string|null  $search  Pencarian berdasarkan nama
     * @return array{success: bool, data: array, total: int, error: string|null}
     */
    public function getStudents(?string $nis = null, ?string $search = null): array
    {
        try {
            $params = array_filter([
                'nis'    => $nis,
                'search' => $search,
            ]);

            $response = $this->gatewayClient()->get($this->url('students'), $params);

            if ($response->successful()) {
                $body = $response->json();
                $data = $body['data'] ?? $body;
                return [
                    'success' => true,
                    'data'    => is_array($data) ? $data : [],
                    'total'   => $body['total'] ?? count($data),
                    'error'   => null,
                ];
            }

            return [
                'success' => false,
                'data'    => [],
                'total'   => 0,
                'error'   => "HTTP {$response->status()}: " . ($response->json('message') ?? 'Gagal mengambil data siswa'),
            ];
        } catch (ConnectionException $e) {
            return ['success' => false, 'data' => [], 'total' => 0, 'error' => 'Tidak dapat terhubung ke SiPintu — server mungkin offline.'];
        } catch (\Exception $e) {
            Log::error('SiPintu getStudents error: ' . $e->getMessage());
            return ['success' => false, 'data' => [], 'total' => 0, 'error' => $e->getMessage()];
        }
    }

    /**
     * Ambil data guru dari SIJUNA via SiPintu Gateway.
     * GET /api/v1/sijuna/teachers
     *
     * @param  string|null  $nip     Filter berdasarkan NIP guru
     * @param  string|null  $search  Pencarian berdasarkan nama guru
     * @return array{success: bool, data: array, total: int, error: string|null}
     */
    public function getTeachers(?string $nip = null, ?string $search = null): array
    {
        try {
            $params = array_filter([
                'nip'    => $nip,
                'search' => $search,
            ]);

            $response = $this->gatewayClient()->get($this->url('teachers'), $params);

            if ($response->successful()) {
                $body = $response->json();
                $data = $body['data'] ?? $body;
                return [
                    'success' => true,
                    'data'    => is_array($data) ? $data : [],
                    'total'   => $body['total'] ?? count($data),
                    'error'   => null,
                ];
            }

            return [
                'success' => false,
                'data'    => [],
                'total'   => 0,
                'error'   => "HTTP {$response->status()}: " . ($response->json('message') ?? 'Gagal mengambil data guru'),
            ];
        } catch (ConnectionException $e) {
            return ['success' => false, 'data' => [], 'total' => 0, 'error' => 'Tidak dapat terhubung ke SiPintu — server mungkin offline.'];
        } catch (\Exception $e) {
            Log::error('SiPintu getTeachers error: ' . $e->getMessage());
            return ['success' => false, 'data' => [], 'total' => 0, 'error' => $e->getMessage()];
        }
    }

    // ═══════════════════════════════════════════════════════════
    // METODE 2: OAUTH 2.0 / OPENID CONNECT SSO
    // ═══════════════════════════════════════════════════════════

    /**
     * Build URL redirect Authorization Endpoint OAuth 2.0.
     *
     * @param  string  $state  Random state untuk CSRF protection
     * @return string
     */
    public function getAuthorizationUrl(string $state): string
    {
        $baseOauthUrl = $this->baseUrl . config('sipintu.oauth.authorize', '/oauth/authorize');

        return $baseOauthUrl . '?' . http_build_query([
            'client_id'     => $this->clientId,
            'redirect_uri'  => $this->redirectUri,
            'response_type' => 'code',
            'state'         => $state,
        ]);
    }

    /**
     * Tukar Authorization Code dengan Access Token & ID Token.
     * POST /oauth/token
     *
     * @param  string  $code  Authorization code dari callback URL
     * @return array{success: bool, access_token: string|null, refresh_token: string|null, id_token: string|null, expires_in: int|null, error: string|null}
     */
    public function exchangeCodeForToken(string $code): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->asForm()
                ->post($this->baseUrl . config('sipintu.oauth.token', '/oauth/token'), [
                    'grant_type'    => 'authorization_code',
                    'client_id'     => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'code'          => $code,
                    'redirect_uri'  => $this->redirectUri,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success'       => true,
                    'access_token'  => $data['access_token']  ?? null,
                    'refresh_token' => $data['refresh_token'] ?? null,
                    'id_token'      => $data['id_token']      ?? null,
                    'expires_in'    => $data['expires_in']    ?? 86400,
                    'error'         => null,
                ];
            }

            $errorData = $response->json();
            return [
                'success'       => false,
                'access_token'  => null,
                'refresh_token' => null,
                'id_token'      => null,
                'expires_in'    => null,
                'error'         => $errorData['error_description'] ?? ($errorData['message'] ?? "HTTP {$response->status()}: Token exchange gagal"),
            ];
        } catch (ConnectionException $e) {
            return ['success' => false, 'access_token' => null, 'refresh_token' => null, 'id_token' => null, 'expires_in' => null, 'error' => 'Tidak dapat terhubung ke SiPintu untuk exchange token.'];
        } catch (\Exception $e) {
            Log::error('SiPintu token exchange error: ' . $e->getMessage());
            return ['success' => false, 'access_token' => null, 'refresh_token' => null, 'id_token' => null, 'expires_in' => null, 'error' => $e->getMessage()];
        }
    }

    /**
     * Ambil data profil user yang sedang login via Bearer Token.
     * GET /api/v1/user
     *
     * @param  string  $accessToken  Bearer access token
     * @return array{success: bool, data: array|null, error: string|null}
     */
    public function getUserProfile(string $accessToken): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withToken($accessToken)
                ->withHeaders(['Accept' => 'application/json'])
                ->get($this->url('user'));

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json(), 'error' => null];
            }

            return [
                'success' => false,
                'data'    => null,
                'error'   => "HTTP {$response->status()}: " . ($response->json('message') ?? 'Gagal mengambil profil user'),
            ];
        } catch (ConnectionException $e) {
            return ['success' => false, 'data' => null, 'error' => 'Tidak dapat terhubung ke SiPintu.'];
        } catch (\Exception $e) {
            Log::error('SiPintu getUserProfile error: ' . $e->getMessage());
            return ['success' => false, 'data' => null, 'error' => $e->getMessage()];
        }
    }
}
