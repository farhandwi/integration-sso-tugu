<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Exception;
use Illuminate\Support\Facades\Log;

class SsoAuthController extends Controller
{
    public function handleRefreshToken($refresh_token, Request $request)
    {
        try {
            $jwt_secret = env('JWT_REFRESH_TCES');
            
            try {
                // Verify JWT signature
                $decoded_refresh_token = JWT::decode($refresh_token, new Key($jwt_secret, 'HS256'));
                
                // Check if token is expired
                $now = time();
                if ($decoded_refresh_token->exp < $now) {
                    return redirect('/not-access')->with('error', 'Refresh token expired');
                }
                
                // Store refresh token in HTTP-only cookie
                $jwt_time = env('REFRESH_TOKEN_TCES_EXPIRY', 300);
                $cookie = cookie('refresh_token', $refresh_token, $jwt_time, null, null, true, true);
                
                // Get access token from SSO
                $access_token = $this->getAccessToken($refresh_token);
                
                if (!$access_token) {
                    return redirect('/not-access')->with('error', 'Failed to obtain access token');
                }
                
                // Store access token in session for middleware use
                session(['access_token' => $access_token]);
                
                // Redirect to dashboard or landing page
                return redirect('/dashboard')->cookie($cookie);
                
            } catch (ExpiredException $e) {
                return redirect('/not-access')->with('error', 'Refresh token expired');
            } catch (Exception $e) {
                return redirect('/not-access')->with('error', 'Invalid refresh token');
            }
        } catch (Exception $e) {
            return redirect('/not-access')->with('error', 'Authentication failed');
        }
    }
    
    private function getAccessToken($refresh_token)
    {
        try {
            $sso_endpoint = env('SSO_ENDPOINT');
            
            $response = Http::post($sso_endpoint . '/access_token', [
                'refresh_token' => $refresh_token,
                'app_id' => 'T0001'
            ]);

            if ($response->successful()) {
                return $response->json('data');
            }
            
            return null;
        } catch (Exception $e) {
            return null;
        }
    }
    
    public static function decodeAccessToken($access_token)
    {
        try {
            $jwt_secret = env('JWT_SECRET_TCES');
            return JWT::decode($access_token, new Key($jwt_secret, 'HS256'));
        } catch (Exception $e) {
            return null;
        }
    }
}