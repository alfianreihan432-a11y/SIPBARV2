<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticateUser
{
    /**
     * Login berdasarkan role:
     *   admin  → email + password
     *   siswa  → email + password
     *   guru   → email + password
     */
    public function __invoke(Request $request): ?User
    {
        $user = User::where('email', $request->input('email', ''))->first();

        if (! $user || ! Hash::check($request->input('password', ''), $user->password)) {
            return null;
        }

            $role = $request->input('role');
            $hasRole = ! $role || ($role === 'admin'
                ? $user->hasAnyRole(['admin', 'super-admin'])
                : $user->hasRole($role));

            if (! $role) {
                $hasRole = true;
            }

        return $hasRole ? $user : null;
    }
}
