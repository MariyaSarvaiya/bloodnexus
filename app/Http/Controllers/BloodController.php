<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use App\Models\BloodRequest;
use App\Support\BloodCompatibility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BloodController extends Controller
{
    public function search(Request $request)
    {
        abort_unless(Auth::check(), 403);
        abort_unless(Auth::user()->role === 'user', 403, 'Only Blood Need users can search for blood.');

        $bloodRequest = null;
        if ($request->filled('request_id')) {
            $bloodRequest = BloodRequest::where('id', $request->integer('request_id'))
                ->where('user_id', Auth::id())
                ->whereNull('parent_request_id')
                ->first();
        }

        if (!$bloodRequest) {
            $bloodRequest = BloodRequest::where('user_id', Auth::id())
                ->whereNull('parent_request_id')
                ->whereIn('status', ['pending', 'matched', 'accepted'])
                ->latest()
                ->first();
        }

        if (!$bloodRequest) {
            return redirect()->route('blood.request')
                ->with('error', 'First create a blood request. Then use Find Blood to see compatible donors.');
        }

        $bloodGroup = strtoupper(trim($bloodRequest->blood_group));
        $city = trim($request->input('city', $bloodRequest->city ?? ''));
        $normalizedCity = strtolower(preg_replace('/\s+/', '', $city));
        $compatibleDonorGroups = BloodCompatibility::donorGroupsForRecipient($bloodGroup);

        $donors = Donor::query()
            ->where('is_available', true)
            ->where(function ($q) {
                $q->whereNull('last_donation_date')
                  ->orWhereDate('last_donation_date', '<=', now()->subMonthsNoOverflow(3));
            })
            ->whereIn('blood_group', $compatibleDonorGroups)
            ->when($city !== '', function ($q) use ($normalizedCity) {
                // Same-city donors are ranked first, but matching blood donors
                // from other cities remain visible as alternatives.
                $q->orderByRaw(
                    "CASE WHEN REPLACE(LOWER(TRIM(city)), ' ', '') = ? THEN 0 ELSE 1 END",
                    [$normalizedCity]
                );
            })
            ->orderBy('city')
            ->orderBy('name')
            ->get();

        return view('blood-search', compact('donors', 'bloodGroup', 'city', 'bloodRequest', 'compatibleDonorGroups'));
    }
}
