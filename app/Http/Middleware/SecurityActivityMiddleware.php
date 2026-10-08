<?php

namespace App\Http\Middleware;

use App\Models\BloodRequest;
use App\Models\SecurityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityActivityMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $isFixedAdmin = $request->session()->get('fixed_admin') === true;

        if (!$isFixedAdmin && !auth()->check()) {
            return $response;
        }

        if (!$isFixedAdmin && auth()->user()->isSuspended() && !str_contains((string) ($request->route()?->getName() ?? ''), 'logout')) {
            auth()->logout();
            return redirect()->route('login')->withErrors(['email' => 'This account is temporarily suspended due to suspicious activity.']);
        }

        $route = $request->route()?->getName() ?? $request->path();
        $method = strtoupper($request->method());
        $watched = $method !== 'GET' || str_contains($route, 'blood') || str_contains($route, 'donor') || str_contains($route, 'message');

        if (!$watched) {
            return $response;
        }

        $user = $isFixedAdmin ? null : auth()->user();
        $event = $this->eventType($route, $method);
        $recentQuery = SecurityLog::query();
        if ($user) {
            $recentQuery->where('user_id', $user->id);
        } else {
            $recentQuery->whereNull('user_id');
        }
        $recent = $recentQuery
            ->where('ip_address', $request->ip())
            ->where('created_at', '>=', now()->subMinutes(10))
            ->count();

        $risk = min(100, max(0, ($recent * 4)));
        $severity = $risk >= 70 ? 'critical' : ($risk >= 40 ? 'high' : ($risk >= 20 ? 'medium' : 'info'));
        $message = $this->message($event);

        if ($recent >= 4) {
            $message .= ' Repeated activity detected within the last 10 minutes.';
        }

        SecurityLog::record([
            'user_id' => $user?->id,
            'event_type' => $event,
            'severity' => $severity,
            'risk_score' => $risk,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 1000),
            'route' => $route,
            'method' => $method,
            'message' => $message,
            'metadata' => [
                'status_code' => $response->getStatusCode(),
                'path' => $request->path(),
                'recent_activity_count' => $recent,
            ],
        ]);

        // Flag bursty blood-request creation/cancellation as a separate security incident.
        if ($event === 'blood_request_action' && $method === 'POST') {
            $requestBurst = BloodRequest::where('user_id', $user->id)
                ->where('created_at', '>=', now()->subMinutes(10))
                ->count();
            if ($requestBurst >= 4) {
                SecurityLog::record([
                    'user_id' => $user?->id,
                    'event_type' => 'suspicious_request_burst',
                    'severity' => $requestBurst >= 7 ? 'critical' : 'high',
                    'risk_score' => min(100, 55 + ($requestBurst * 5)),
                    'ip_address' => $request->ip(),
                    'user_agent' => substr((string) $request->userAgent(), 0, 1000),
                    'route' => $route,
                    'method' => $method,
                    'message' => "Repeated blood-request creation detected: {$requestBurst} request(s) in 10 minutes.",
                    'metadata' => ['request_burst' => $requestBurst],
                ]);
            }
        }

        return $response;
    }

    private function eventType(string $route, string $method): string
    {
        if (str_contains($route, 'login')) return 'login';
        if (str_contains($route, 'blood.request') || str_contains($route, 'blood-request')) return 'blood_request_action';
        if (str_contains($route, 'donor.request')) return 'donor_request_action';
        if (str_contains($route, 'messages')) return 'message_action';
        if ($method === 'DELETE') return 'delete_action';
        return 'protected_action';
    }

    private function message(string $event): string
    {
        return match ($event) {
            'blood_request_action' => 'Blood request activity recorded.',
            'donor_request_action' => 'Donor request activity recorded.',
            'message_action' => 'Messaging activity recorded.',
            'delete_action' => 'Delete action recorded.',
            default => 'Protected application activity recorded.',
        };
    }
}
