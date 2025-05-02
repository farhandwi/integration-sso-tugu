<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\SsoAuthController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Exception;
use Illuminate\Support\Facades\Log;

class ValidateAccessToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        Log::info('ValidateAccessToken middleware called');
        $access_token = session('access_token');
        
        if (!$access_token) {
            return redirect('/not-access');
        }
        
        try {
            // Validate the access token using JWT Secret
            $jwt_secret = env('JWT_SECRET_TCES');
            $decoded = JWT::decode($access_token, new Key($jwt_secret, 'HS256'));
            
            // Check if token is expired
            $now = time();
            if ($decoded->exp < $now) {
                // Token is expired - try to refresh it
                $refresh_token = $request->cookie('refresh_token');
                
                if (!$refresh_token) {
                    return redirect('/not-access');
                }
                
                // Get new access token using refresh token
                $new_access_token = $this->refreshAccessToken($refresh_token);
                
                if (!$new_access_token) {
                    return redirect('/not-access');
                }
                
                // Update session with new access token
                session(['access_token' => $new_access_token]);
                
                // Decode the new token to get user information
                $decoded = SsoAuthController::decodeAccessToken($new_access_token);
                
                if (!$decoded) {
                    return redirect('/not-access');
                }
            }
            
            // Make user data available to all views
            app('App\Providers\SsoContextServiceProvider')->setUserData($decoded);
            
            return $next($request);
            
        } catch (Exception $e) {
            return redirect('/not-access');
        }
    }
    
    /**
     * Refresh the access token using the refresh token.
     *
     * @param  string  $refresh_token
     * @return string|null
     */
    private function refreshAccessToken($refresh_token)
    {
        try {
            $sso_endpoint = env('SSO_ENDPOINT');
            
            $response = Http::post($sso_endpoint . '/access_token', [
                'refresh_token' => $refresh_token,
                'app_id' => 'T0001'
            ]);
            
            if ($response->successful()) {
                return $response->json('access_token');
            }
            
            return null;
        } catch (Exception $e) {
            return null;
        }
    }
}