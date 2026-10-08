<?php

namespace App\Http\Controllers;

use App\Models\BloodRequest;
use App\Models\Donor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AiAssistantController extends Controller
{
    public function index()
    {
        abort_unless(Auth::check() && in_array(Auth::user()->role, ['user', 'donor'], true), 403);

        $role = Auth::user()->role;
        $context = ['role' => $role];

        if ($role === 'donor') {
            $donor = Donor::where('user_id', Auth::id())->first();
            $context['donor'] = $donor;
        } else {
            $context['requestCount'] = BloodRequest::where('user_id', Auth::id())->count();
        }

        return view('ai-assistant', $context);
    }

    public function ask(Request $request)
    {
        abort_unless(Auth::check() && in_array(Auth::user()->role, ['user', 'donor'], true), 403);

        $data = $request->validate([
            'message' => ['nullable', 'string', 'max:1000'],
            'question' => ['nullable', 'string', 'max:1000'],
        ]);

        $question = trim($data['message'] ?? $data['question'] ?? '');
        if ($question === '') {
            return response()->json(['reply' => 'Please enter a question first.'], 422);
        }

        $q = strtolower($question);
        $user = Auth::user();

        if ($user->role === 'donor') {
            $answer = $this->donorAnswer($q, $question);
        } else {
            $answer = $this->userAnswer($q, $question);
        }

        return response()->json([
            'reply' => $answer,
            'assistant' => 'BloodNexus AI',
            'timestamp' => now()->format('H:i'),
        ]);
    }

    private function userAnswer(string $q, string $original): string
    {
        if (preg_match('/\b(a\+|a-|b\+|b-|ab\+|ab-|o\+|o-)\b/i', $original, $m)) {
            $group = strtoupper($m[1]);
            $city = $this->extractCity($original, $group);
            $query = Donor::where('is_available', true)
                ->whereRaw('UPPER(TRIM(blood_group)) = ?', [$group]);

            if ($city) {
                $norm = strtolower(preg_replace('/\s+/', '', $city));
                $query->orderByRaw("CASE WHEN REPLACE(LOWER(TRIM(city)), ' ', '') = ? THEN 0 ELSE 1 END", [$norm]);
            }

            $donors = $query->orderBy('city')->orderBy('name')->limit(6)->get();
            if ($donors->isEmpty()) {
                return "🔎 I couldn't find an available {$group} donor right now. Try another city or check again later.";
            }

            $lines = $donors->map(fn ($d) => '• ' . $d->name . ' — ' . $d->blood_group . ' — ' . ($d->city ?: 'City not set'))->implode("\n");
            return "🤖 BloodNexus AI found {$donors->count()} available {$group} donor(s):\n\n{$lines}\n\n📌 Same-city matches are ranked first when a city is mentioned. Contact and medical confirmation should be handled through the hospital/blood-bank process.";
        }

        if (str_contains($q, 'my request') || str_contains($q, 'my requests')) {
            $requests = BloodRequest::where('user_id', Auth::id())->get();
            $pending = $requests->where('status', 'pending')->count();
            $accepted = $requests->where('status', 'accepted')->count();
            $completed = $requests->where('status', 'completed')->count();
            return "📋 Your BloodNexus request summary:\n\n• Total: {$requests->count()}\n• Pending: {$pending}\n• Accepted: {$accepted}\n• Completed: {$completed}\n\nOpen My Requests for the full status and donor details.";
        }

        if (str_contains($q, 'emergency') || str_contains($q, 'critical')) {
            return "🚨 For a critical blood need, select Critical urgency in Need Blood and submit complete patient, hospital, city and contact details. BloodNexus will notify the selected donor when you choose one. For an actual medical emergency, contact the treating hospital/emergency service immediately.";
        }

        if (str_contains($q, 'how') && str_contains($q, 'request')) {
            return "🩸 To request blood: Need Blood → enter patient details → choose blood group → hospital/city → units → urgency → optionally select a donor from Find Blood → Submit Request. A selected donor receives the request directly.";
        }

        if (str_contains($q, 'ai') || str_contains($q, 'help')) {
            return "🤖 I'm BloodNexus AI. I can search live donor availability, summarize your requests, explain urgency levels, guide you through requesting blood, and explain blood-group concepts.";
        }

        if (str_contains($q, 'blood group') || str_contains($q, 'compatibility') || str_contains($q, 'universal')) {
            return "🩸 I can explain blood-group compatibility at a general level. O-negative is commonly used as a universal red-cell donor type and AB-positive as a universal red-cell recipient type, but actual transfusion decisions must be confirmed by qualified medical staff and compatibility testing.";
        }

        if (str_contains($q, 'donation') || str_contains($q, 'donate')) {
            return "❤️ Donation eligibility depends on age, health, recent donation history and local blood-bank rules. BloodNexus tracks donor availability and a 3-month cooldown in this project, but a qualified blood-bank professional makes the final eligibility decision.";
        }

        return "🤖 I can help with:\n\n🔎 Live donor search\n🩸 Blood requests\n🚨 Critical/emergency requests\n📋 Your request status\n🧬 Blood-group information\n❤️ Donation guidance\n\nTry: “Find AB+ donors”, “Show my requests”, or “How do I request blood?”";
    }

    private function donorAnswer(string $q, string $original): string
    {
        $donor = Donor::where('user_id', Auth::id())->first();
        if (!$donor) return '⚠️ Your donor profile could not be found.';

        $group = strtoupper(trim($donor->blood_group ?? ''));
        $city = strtolower(preg_replace('/\s+/', '', trim($donor->city ?? '')));

        if (str_contains($q, 'critical') || str_contains($q, 'emergency') || str_contains($q, 'urgent')) {
            $critical = BloodRequest::where('status', 'pending')
                ->whereNull('donor_id')
                ->whereRaw('UPPER(TRIM(blood_group)) = ?', [$group])
                ->whereIn('urgency', ['critical', 'urgent'])
                ->count();
            return "🚨 Donor emergency radar:\n\n• {$critical} pending {$group} urgent/critical request(s) match your blood group.\n• City is used as a priority, not a hard restriction.\n• Critical requests appear first.\n\nOpen Matching Requests to review the full details.";
        }

        if (str_contains($q, 'request') || str_contains($q, 'match') || str_contains($q, 'nearby')) {
            $requests = BloodRequest::where('status', 'pending')
                ->whereNull('donor_id')
                ->whereRaw('UPPER(TRIM(blood_group)) = ?', [$group])
                ->when($city !== '', fn ($x) => $x->orderByRaw("CASE WHEN REPLACE(LOWER(TRIM(city)), ' ', '') = ? THEN 0 ELSE 1 END", [$city]))
                ->orderByRaw("CASE WHEN urgency = 'critical' THEN 1 WHEN urgency = 'urgent' THEN 2 ELSE 3 END")
                ->latest()->limit(8)->get();

            if ($requests->isEmpty()) return "🎯 No pending {$group} requests are waiting right now. I'll keep the matching logic ready for the next request.";
            $lines = $requests->map(fn ($r) => '• #' . $r->id . ' — ' . $r->blood_group . ' — ' . ($r->city ?: '-') . ' — ' . ucfirst($r->urgency))->implode("\n");
            return "🤖 I found {$requests->count()} matching request(s):\n\n{$lines}\n\n📍 Same-city requests are ranked first, while other cities remain visible so you don't miss a matching need.";
        }

        if (str_contains($q, 'eligible') || str_contains($q, 'can i donate') || str_contains($q, 'cooldown')) {
            if ($donor->canDonate()) {
                return "✅ Your project profile currently shows you as eligible under the configured 3-month cooldown rule. Final donation eligibility must still be confirmed by the blood-bank professional.";
            }
            $date = $donor->nextDonationDate();
            return "⏳ Your profile is currently inside the configured donation cooldown. Next eligible date: " . ($date?->format('d M Y') ?? 'not available') . ".";
        }

        if (str_contains($q, 'availability') || str_contains($q, 'available')) {
            return $donor->isAvailable()
                ? "🟢 Your donor profile is currently marked AVAILABLE. Matching requests can be shown to you."
                : "⚪ Your donor profile is currently NOT available. You can change availability from the donor dashboard when appropriate.";
        }

        if (str_contains($q, 'donation') || str_contains($q, 'history')) {
            $count = BloodRequest::where('donor_id', $donor->id)->where('status', 'completed')->count();
            return "❤️ Your BloodNexus donation history contains {$count} completed donation record(s). The project also stores recipient, hospital, date, time and units when a request is completed.";
        }

        return "🤖 BloodNexus AI is ready for your donor role. Try:\n\n🚨 “Show critical requests”\n🎯 “Find matching requests”\n📍 “Show requests outside my city too”\n❤️ “Am I eligible to donate?”\n🟢 “Am I available?”\n📊 “Show my donation history”";
    }

    private function extractCity(string $text, string $group): ?string
    {
        $clean = preg_replace('/' . preg_quote($group, '/') . '/i', '', $text);
        if (preg_match('/\b(?:in|at|near|from)\s+([a-zA-Z][a-zA-Z\s]{2,30})/i', $clean, $m)) {
            return trim($m[1]);
        }
        return null;
    }
}
