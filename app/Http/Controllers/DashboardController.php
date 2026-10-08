<?php

namespace App\Http\Controllers;

use App\Models\BloodRequest;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'user') {
            abort(403, 'This dashboard is only for Blood Need users.');
        }

        $requests = BloodRequest::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $totalRequests = BloodRequest::where('user_id', $user->id)->count();

        $pendingRequests = BloodRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        $acceptedRequests = BloodRequest::where('user_id', $user->id)
            ->where('status', 'accepted')
            ->count();

        $completedRequests = BloodRequest::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        return view('dashboard', compact(
            'user',
            'requests',
            'totalRequests',
            'pendingRequests',
            'acceptedRequests',
            'completedRequests'
        ));
    }
}
