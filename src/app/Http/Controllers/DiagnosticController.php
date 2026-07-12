<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class DiagnosticController extends Controller
{
    public function google(): JsonResponse
    {
        $clientId = config('services.google.client_id');
        $secret   = config('services.google.client_secret');
        $redirect = config('services.google.redirect');
        $appUrl   = config('app.url');

        $checks = [
            'socialite_installed' => class_exists(\Laravel\Socialite\Facades\Socialite::class),
            'google_client_id_set' => !empty($clientId) && !str_starts_with((string) $clientId, 'your-'),
            'google_client_secret_set' => !empty($secret) && !str_starts_with((string) $secret, 'your-'),
            'app_url' => $appUrl,
            'redirect_uri' => $redirect,
            'redirect_uri_matches_app_url' => str_starts_with((string) $redirect, (string) $appUrl),
            'route_exists' => app('router')->has('auth.google.redirect'),
            'google_controller_exists' => class_exists(\App\Http\Controllers\GoogleAuthController::class),
        ];

        $allOk = !in_array(false, $checks, true);

        return response()->json([
            'status' => $allOk ? 'OK' : 'FAIL',
            'checks' => $checks,
            'instructions' => $allOk
                ? 'Semua OK. Google OAuth siap digunakan.'
                : 'Ada masalah. Lihat checks yang false di atas.',
        ]);
    }
}
