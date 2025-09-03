<?php
// app/Services/RoleRedirectService.php

namespace App\Services;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class RoleRedirectService
{
    public static function getRedirectPath(): string
    {
        $user = Auth::user();
        
        if (!$user) {
            return '/login';
        }

        return match($user->role) {
            'admin' => '/admin/dashboard',
            'user' => '/home',
            default => '/home',
        };
    }

    public static function redirectByRole(): RedirectResponse
    {
        return redirect(self::getRedirectPath());
    }
}