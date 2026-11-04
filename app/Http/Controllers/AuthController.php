<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $throttleKey = Str::transliterate(Str::lower($credentials['username'])).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'login' => "Muitas tentativas. Tente novamente em {$seconds} segundos.",
            ]);
        }

        if (! Auth::attempt($credentials)) {
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'login' => 'Credenciais inválidas.',
            ]);
        }

        $user = Auth::user();
        if ($user->is_active === false) {
            Auth::logout();
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'login' => 'Este acesso está desativado. Procure a secretaria paroquial.',
            ]);
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        if (! $user->password_verify) {
            if (! $user->email_verified_at) {
                $user->sendEmailVerificationCode();
                return redirect()->route('email.verify');
            }
            return redirect()->route('first.access');
        }

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('portal.home');
    }

    public function showEmailVerification()
    {
        return view('auth.verify-email');
    }

    public function verifyEmail(Request $request)
    {
        $request->validate(['code' => ['required', 'digits:6']]);
        $verification = $request->user()->emailVerificationCodes()->latest()->first();
        if (! $verification || $verification->expires_at->isPast() || ! Hash::check($request->code, $verification->code)) {
            return back()->withErrors(['code' => 'Código inválido ou expirado.']);
        }
        $request->user()->forceFill(['email_verified_at' => now()])->save();
        $verification->delete();
        return redirect()->route('first.access');
    }

    public function resendEmailVerification(Request $request)
    {
        $request->user()->sendEmailVerificationCode();
        return back()->with('status', 'Um novo código foi enviado para o seu e-mail.');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);
        $status = Password::sendResetLink($request->only('email'));
        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Se o e-mail estiver cadastrado, enviaremos um link de recuperação.')
            : back()->withErrors(['email' => 'Não foi possível enviar o link agora.']);
    }

    public function showResetPassword(Request $request, string $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->query('email')]);
    }

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'token' => ['required'], 'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(10)->mixedCase()->numbers()->symbols()],
        ]);
        $status = Password::reset($validated, function ($user, $password) {
            $user->forceFill([
                'password' => $password,
                'password_verify' => true,
                'email_verified_at' => $user->email_verified_at ?: now(),
            ])->save();
        });
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Senha redefinida. Faça login com a nova senha.')
            : back()->withErrors(['email' => 'Link inválido ou expirado.']);
    }
}
