<?php

namespace App\Http\Controllers;

use App\Models\AccountAppeal;
use App\Models\SecurityLog;
use App\Models\User;
use Illuminate\Http\Request;

class AccountAppealController extends Controller
{
    public function create(Request $request)
    {
        $email = trim((string) $request->query('email', ''));
        $existing = $email !== '' ? AccountAppeal::whereRaw('LOWER(email) = ?', [strtolower($email)])->latest()->first() : null;

        if ($existing) {
            return redirect()->route('account.review.track', ['email' => $email]);
        }

        return view('account-appeal', [
            'email' => $email,
            'name' => $request->query('name', ''),
            'existingAppeal' => null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'reason' => ['required', 'string', 'max:120'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        $email = strtolower(trim($data['email']));
        $existing = AccountAppeal::whereRaw('LOWER(email) = ?', [$email])->first();

        if ($existing) {
            return redirect()->route('account.review.track', ['email' => $email])
                ->with('status', 'An appeal has already been submitted for this email. You can only track its status now.');
        }

        $user = User::where('email', $email)->first();
        $appeal = AccountAppeal::create([
            'user_id' => $user?->id,
            'name' => $data['name'],
            'email' => $email,
            'reason' => $data['reason'],
            'message' => $data['message'] ?? null,
            'status' => 'pending',
        ]);

        SecurityLog::record([
            'user_id' => $user?->id,
            'event_type' => 'account_appeal_submitted',
            'severity' => 'medium',
            'risk_score' => 45,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 1000),
            'route' => $request->route()?->getName(),
            'method' => $request->method(),
            'message' => 'A one-time account appeal was submitted.',
            'metadata' => ['appeal_id' => $appeal->id, 'email' => $email],
        ]);

        return redirect()->route('account.review.track', ['email' => $email])
            ->with('status', 'Your appeal was submitted successfully. This email can no longer submit another appeal; use this page to track the decision.');
    }

    public function track(Request $request)
    {
        $email = strtolower(trim((string) $request->query('email', '')));
        $appeal = $email !== ''
            ? AccountAppeal::whereRaw('LOWER(email) = ?', [$email])->latest()->first()
            : null;

        return view('account-appeal-track', compact('email', 'appeal'));
    }
}
