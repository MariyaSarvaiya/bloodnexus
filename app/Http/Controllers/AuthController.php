<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Donor;
use App\Models\SecurityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LOGIN / REGISTER PAGES
    |--------------------------------------------------------------------------
    */

    public function showLogin()
    {
        return view('login');
    }

    public function showRegister()
    {
        return view('register');
    }

    /*
    |--------------------------------------------------------------------------
    | USER REGISTRATION - PHASE 1
    |--------------------------------------------------------------------------
    */

    public function showUserRegister()
    {
        return view('register-user');
    }

    public function registerUser(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc,dns', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'blood_group' => [
                'required',
                'in:A+,A-,B+,B-,AB+,AB-,O+,O-'
            ],
            'city' => ['required', 'string', 'max:100'],
            'area' => ['required', 'string', 'max:120'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);


        $user = new User();

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'];
        $user->blood_group = $data['blood_group'];
        $user->city = $data['city'];
        $user->area = $data['area'];
        $user->password = Hash::make($data['password']);

        // Email verification is not required. New accounts are active immediately.
        $user->role = 'user';
        $user->email_verified_at = now();
        $user->save();

        return redirect()->route('login')->with('status', 'Blood Need account created successfully. You can login now.');
    }

    /*
    |--------------------------------------------------------------------------
    | DONOR REGISTRATION
    |--------------------------------------------------------------------------
    */

    public function showDonorRegister()
    {
        return view('register-donor');
    }

    public function registerDonor(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc,dns', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'blood_group' => [
                'required',
                'in:A+,A-,B+,B-,AB+,AB-,O+,O-'
            ],
            'city' => ['required', 'string', 'max:100'],
            'area' => ['required', 'string', 'max:120'],
            'medical_history' => ['nullable', 'array'],
            'medical_history.*' => ['nullable', 'in:yes,no'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);


        $user = DB::transaction(function () use ($data) {

            /*
            |--------------------------------------------------------------------------
            | CREATE USER ACCOUNT
            |--------------------------------------------------------------------------
            */

            $user = new User();

            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->phone = $data['phone'];
            $user->blood_group = $data['blood_group'];
            $user->city = $data['city'];
            $user->area = $data['area'];
            $user->password = Hash::make($data['password']);

            // DONOR ROLE
            $user->role = 'donor';

            $user->save();


            /*
            |--------------------------------------------------------------------------
            | CREATE DONOR PROFILE
            |--------------------------------------------------------------------------
            */

            $donor = new Donor();

            $donor->user_id = $user->id;
            $donor->name = $data['name'];
            $donor->email = $data['email'];
            $donor->phone = $data['phone'];
            $donor->blood_group = $data['blood_group'];
            $donor->city = $data['city'];
            $donor->area = $data['area'];
            $donor->medical_history = !empty($data['medical_history']) ? json_encode($data['medical_history']) : null;
            $donor->is_available = true;

            $donor->save();

            return $user;
        });


        // Email verification is not required. Donor accounts are active immediately.
        $user->forceFill(['email_verified_at' => now()])->saveQuietly();

        return redirect()->route('login')->with('status', 'Donor account created successfully. You can login now.');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function login(Request $request)
    {
        // BloodNexus login-abuse policy. These limits are intentionally explicit
        // so they can be tuned later without changing the rest of authentication.
        $loginWarningThreshold = 5;   // show a warning before another login
        $loginBlockThreshold   = 8;   // block the next login after 8 successful logins today

        $fixedAdminEmail = 'admin@bloodnexus.local';
        $fixedAdminPassword = 'BloodNexus@2026';
        $email = Str::lower(trim((string) $request->input('email')));
        $password = (string) $request->input('password');

        // Fixed administrator is deliberately outside normal user monitoring.
        if ($email === $fixedAdminEmail && hash_equals($fixedAdminPassword, $password)) {
            $request->session()->regenerate();
            $request->session()->put([
                'fixed_admin' => true,
                'fixed_admin_email' => $fixedAdminEmail,
                'fixed_admin_name' => 'BloodNexus Administrator',
            ]);

            SecurityLog::record([
                'event_type' => 'fixed_admin_login',
                'severity' => 'info',
                'risk_score' => 0,
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 1000),
                'route' => $request->route()?->getName(),
                'method' => $request->method(),
                'message' => 'Fixed BloodNexus administrator logged in.',
                'metadata' => ['email' => $fixedAdminEmail],
            ]);

            return redirect()->route('admin.dashboard');
        }

        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $email)->first();

        // Permanent security block: wrong-password abuse or excessive login activity.
        if ($user && $user->isSecurityBlocked()) {
            SecurityLog::record([
                'user_id' => $user->id,
                'event_type' => 'blocked_login_attempt',
                'severity' => 'critical',
                'risk_score' => 100,
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 1000),
                'route' => $request->route()?->getName(),
                'method' => $request->method(),
                'message' => 'Blocked account attempted to log in.',
                'metadata' => [
                    'reason' => $user->security_block_reason,
                ],
            ]);

            return back()
                ->withErrors(['email' => 'Your BloodNexus account has been permanently blocked by the security system.'])
                ->with('account_blocked', true)
                ->withInput(['email' => $email]);
        }

        // Existing temporary lock protection remains supported for old accounts.
        if ($user && $user->accountIsLocked()) {
            $minutes = max(1, now()->diffInMinutes($user->locked_until));
            SecurityLog::record([
                'user_id' => $user->id,
                'event_type' => 'locked_login_attempt',
                'severity' => 'high',
                'risk_score' => 85,
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 1000),
                'route' => $request->route()?->getName(),
                'method' => $request->method(),
                'message' => 'Login attempt blocked because account is temporarily locked.',
                'metadata' => ['locked_until' => $user->locked_until?->toIso8601String()],
            ]);

            return back()
                ->withErrors(['email' => "Account temporarily locked. Please wait about {$minutes} minute(s), or submit an account review appeal."])
                ->with('account_locked', true)
                ->withInput(['email' => $email]);
        }

        // Count successful logins recorded today. SecurityLog is used as the audit source.
        $loginsToday = $user
            ? SecurityLog::where('user_id', $user->id)
                ->where('event_type', 'successful_login')
                ->whereDate('created_at', today())
                ->count()
            : 0;

        // The next login after the daily limit is blocked permanently.
        if ($user && !$user->isAdmin() && $loginsToday >= $loginBlockThreshold) {
            $reason = "Excessive login activity: {$loginsToday} successful logins recorded today.";
            $user->forceFill([
                'security_blocked' => true,
                'security_block_reason' => $reason,
                'security_blocked_at' => now(),
                'locked_until' => null,
                'failed_login_attempts' => 0,
            ])->saveQuietly();

            SecurityLog::record([
                'user_id' => $user->id,
                'event_type' => 'excessive_login_block',
                'severity' => 'critical',
                'risk_score' => 100,
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 1000),
                'route' => $request->route()?->getName(),
                'method' => $request->method(),
                'message' => 'Account permanently blocked after excessive successful logins in one day.',
                'metadata' => [
                    'successful_logins_today' => $loginsToday,
                    'threshold' => $loginBlockThreshold,
                ],
            ]);

            return back()
                ->withErrors(['email' => 'Your account has been blocked because of unusually frequent login activity today. Please contact the administrator.'])
                ->with('account_blocked', true)
                ->withInput(['email' => $email]);
        }

        // Before another login is accepted, show an explicit security warning once
        // the user reaches the monitoring threshold. The user must acknowledge it.
        $warningAcknowledged = $request->boolean('security_warning_ack');
        if ($user && !$user->isAdmin() && $loginsToday >= $loginWarningThreshold && !$warningAcknowledged) {
            $user->forceFill(['last_login_warning_at' => now()])->saveQuietly();

            SecurityLog::record([
                'user_id' => $user->id,
                'event_type' => 'frequent_login_warning',
                'severity' => 'high',
                'risk_score' => 70,
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 1000),
                'route' => $request->route()?->getName(),
                'method' => $request->method(),
                'message' => 'Frequent login warning displayed before another login.',
                'metadata' => [
                    'successful_logins_today' => $loginsToday,
                    'warning_threshold' => $loginWarningThreshold,
                    'block_threshold' => $loginBlockThreshold,
                ],
            ]);

            return back()
                ->with('login_warning', true)
                ->with('login_count_today', $loginsToday)
                ->with('login_warning_threshold', $loginWarningThreshold)
                ->with('login_block_threshold', $loginBlockThreshold)
                ->withInput(['email' => $email]);
        }

        if (!Auth::attempt(['email' => $email, 'password' => $data['password']], $request->boolean('remember'))) {
            $attempts = $user ? ((int) $user->failed_login_attempts + 1) : 1;

            if ($user) {
                $user->forceFill(['failed_login_attempts' => $attempts])->saveQuietly();
            }

            $blocked = false;
            if ($user && $attempts >= 3 && !$user->isAdmin()) {
                $blocked = true;
                $user->forceFill([
                    'security_blocked' => true,
                    'security_block_reason' => 'Three incorrect password attempts.',
                    'security_blocked_at' => now(),
                    'locked_until' => null,
                    'failed_login_attempts' => 0,
                ])->saveQuietly();
            }

            SecurityLog::record([
                'user_id' => $user?->id,
                'event_type' => $blocked ? 'account_permanently_blocked' : 'failed_login',
                'severity' => $blocked ? 'critical' : ($attempts >= 2 ? 'medium' : 'info'),
                'risk_score' => $blocked ? 100 : min(90, 20 + $attempts * 20),
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 1000),
                'route' => $request->route()?->getName(),
                'method' => $request->method(),
                'message' => $blocked
                    ? 'Account permanently blocked after 3 incorrect password attempts.'
                    : 'Failed login attempt for ' . $email . '. Attempt #' . $attempts . '.',
                'metadata' => [
                    'email' => $email,
                    'attempts' => $attempts,
                ],
            ]);

            if ($blocked) {
                return back()
                    ->withErrors(['email' => 'Your account has been permanently blocked after 3 incorrect passwords.'])
                    ->with('account_blocked', true)
                    ->withInput(['email' => $email]);
            }

            return back()
                ->withErrors(['email' => 'The email or password is incorrect.'])
                ->withInput(['email' => $email]);
        }

        $request->session()->regenerate();
        $user = Auth::user();

        // User and donor accounts can login directly.

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isSecurityBlocked() || $user->isSuspended() || $user->accountIsLocked()) {
            Auth::logout();
            return back()->withErrors(['email' => 'This account is restricted by BloodNexus security.']);
        }

        $user->forceFill([
            'failed_login_attempts' => 0,
            'locked_until' => null,
        ])->saveQuietly();

        SecurityLog::record([
            'user_id' => $user->id,
            'event_type' => 'successful_login',
            'severity' => 'info',
            'risk_score' => 0,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 1000),
            'route' => $request->route()?->getName(),
            'method' => $request->method(),
            'message' => 'Successful login recorded.',
            'metadata' => [
                'daily_login_number' => $loginsToday + 1,
            ],
        ]);

        return $user->role === 'donor'
            ? redirect()->route('donor.dashboard')
            : redirect()->route('dashboard');
    }

    /*
    |--------------------------------------------------------------------------
    | FORGOT / RESET PASSWORD
    |--------------------------------------------------------------------------
    */

    public function showForgotPassword()
    {
        return view('forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email:rfc,dns'],
        ]);

        $email = Str::lower(trim($data['email']));

        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No BloodNexus account was found for this email address.'])->withInput();
        }


        $status = Password::sendResetLink(['email' => $email]);

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        return back()->withErrors([
            'email' => __($status),
        ]);
    }

    public function showResetPassword(Request $request, string $token)
    {
        return view('reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $data = $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $status = Password::reset(
            $data,
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                RateLimiter::clear('login:' . Str::lower($user->email) . '|' . request()->ip());

                SecurityLog::record([
                    'user_id' => $user->id,
                    'event_type' => 'password_reset',
                    'severity' => 'info',
                    'risk_score' => 0,
                    'ip_address' => request()->ip(),
                    'user_agent' => substr((string) request()->userAgent(), 0, 1000),
                    'route' => request()->route()?->getName(),
                    'method' => request()->method(),
                    'message' => 'Password was successfully reset.',
                ]);
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', __($status));
        }

        return back()->withErrors([
            'email' => __($status),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        if ($request->session()->get('fixed_admin') === true) {
            SecurityLog::record([
                'user_id' => null,
                'event_type' => 'fixed_admin_logout',
                'severity' => 'info',
                'risk_score' => 0,
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 1000),
                'route' => $request->route()?->getName(),
                'method' => $request->method(),
                'message' => 'Fixed BloodNexus administrator logged out.',
            ]);

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('home')->with('success', 'You have been logged out safely.');
        }

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('home')
            ->with(
                'success',
                'You have been logged out safely.'
            );
    }
}