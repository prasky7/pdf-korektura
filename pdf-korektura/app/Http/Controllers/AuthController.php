<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use LdapRecord\ConnectionException;
use LdapRecord\Models\ModelNotFoundException;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $provider = config('auth.guards.web.provider');
        $remember = $request->boolean('remember');

        // 1. Try local account by username
        $localByUsername = [
            'username' => $credentials['username'],
            'password' => $credentials['password'],
        ];
        if (Auth::attempt($localByUsername, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        // 2. Try local account by email
        $localByEmail = [
            'email' => $credentials['username'],
            'password' => $credentials['password'],
        ];
        if (Auth::attempt($localByEmail, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        // 3. Try AD/LDAP if configured
        if ($provider === 'ldap') {
            try {
                if (Auth::attempt($credentials, $remember)) {
                    $request->session()->regenerate();
                    return redirect()->intended(route('dashboard'));
                }
            } catch (ConnectionException $e) {
                Log::error('LDAP connection failed', ['error' => $e->getMessage()]);
                return back()->withErrors([
                    'username' => 'LDAP server není dostupný. Zkuste to později.',
                ])->onlyInput('username');
            } catch (ModelNotFoundException $e) {
                Log::warning('LDAP user not found', ['username' => $credentials['username']]);
            }
        }

        return back()->withErrors([
            'username' => 'Přihlašovací údaje nejsou správné.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
