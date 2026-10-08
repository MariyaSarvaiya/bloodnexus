<?php

namespace App\Http\Controllers;

use App\Helpers\NotificationHelper;
use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\User;
use App\Models\AccountAppeal;
use App\Models\SecurityLog;
use App\Models\Notification;
use App\Models\BloodInventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ADMIN DASHBOARD
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {
        $totalUsers = User::where('role', 'user')->count();

        $totalDonors = Donor::count();

        $activeRequests = BloodRequest::whereIn(
            'status',
            ['pending', 'matched', 'accepted']
        )->count();

        $completedRequests = BloodRequest::where(
            'status',
            'completed'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | CRITICAL REQUESTS
        |--------------------------------------------------------------------------
        */

        $criticalRequests = BloodRequest::with([
            'user',
            'donor'
        ])
        ->where('urgency', 'critical')
        ->whereIn(
            'status',
            ['pending', 'matched', 'accepted']
        )
        ->latest()
        ->take(8)
        ->get();


        /*
        |--------------------------------------------------------------------------
        | BLOOD GROUP STATISTICS
        |--------------------------------------------------------------------------
        */

        $bloodGroupStats = Donor::select(
            'blood_group',
            DB::raw('COUNT(*) as total')
        )
        ->groupBy('blood_group')
        ->pluck('total', 'blood_group')
        ->toArray();


        /*
        |--------------------------------------------------------------------------
        | RECENT REQUESTS
        |--------------------------------------------------------------------------
        */

        $recentRequests = BloodRequest::with([
            'user',
            'donor'
        ])
        ->latest()
        ->take(8)
        ->get();


        /*
        |--------------------------------------------------------------------------
        | RECENT DONORS
        |--------------------------------------------------------------------------
        */

        $recentDonors = Donor::with('user')
            ->latest()
            ->take(8)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | RECENT DONATIONS
        |--------------------------------------------------------------------------
        */

        $recentDonations = $this->donationQuery()
            ->take(8)
            ->get();

        $securityAlerts = SecurityLog::whereIn('severity', ['high', 'critical'])
            ->whereNull('resolved_at')
            ->latest()
            ->take(6)
            ->get();
        $openSecurityAlerts = SecurityLog::whereIn('severity', ['high', 'critical'])
            ->whereNull('resolved_at')
            ->count();

        $cityDemand = BloodRequest::select('city', DB::raw('COUNT(*) as total'))
            ->groupBy('city')->orderByDesc('total')->take(8)->get();
        $bloodDemand = BloodRequest::select('blood_group', DB::raw('COUNT(*) as total'))
            ->groupBy('blood_group')->orderByDesc('total')->get();
        $shortageRisk = $bloodDemand->map(function ($row) {
            $available = Donor::where('blood_group', $row->blood_group)->where('is_available', true)->count();
            $risk = min(100, (int) round(($row->total / max(1, $available)) * 35));
            return (object)['blood_group'=>$row->blood_group,'demand'=>$row->total,'available'=>$available,'risk'=>$risk];
        });
        $suspiciousRequests = BloodRequest::where(function($q){
            $q->where('emergency_mode', true)->orWhere('urgency','critical');
        })->where('created_at','>=',now()->subHours(24))->count();

        return view(
            'admin.dashboard',
            compact(
                'totalUsers',
                'totalDonors',
                'activeRequests',
                'completedRequests',
                'criticalRequests',
                'bloodGroupStats',
                'recentRequests',
                'recentDonors',
                'recentDonations',
                'securityAlerts',
                'openSecurityAlerts',
                'cityDemand',
                'shortageRisk',
                'suspiciousRequests'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | COMPLETED DONATION QUERY
    |--------------------------------------------------------------------------
    | Keep completed donation records in one reusable query.
    |--------------------------------------------------------------------------
    */
    private function donationQuery()
    {
        return BloodRequest::with([
            'donor',
            'user'
        ])
        ->where('status', 'completed')
        ->whereNotNull('donor_id');
    }


    /*
    |--------------------------------------------------------------------------
    | BLOOD REQUEST MANAGEMENT
    |--------------------------------------------------------------------------
    */

    public function requests(Request $request)
    {
        $query = BloodRequest::with([
            'user',
            'donor'
        ])
        ->latest();


        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }


        if ($request->filled('blood_group')) {

            $query->where(
                'blood_group',
                $request->blood_group
            );
        }


        if ($request->filled('urgency')) {

            $query->where(
                'urgency',
                $request->urgency
            );
        }


        if ($request->filled('search')) {

            $term = trim(
                $request->search
            );

            $query->where(function ($q) use ($term) {

                $q->where(
                    'patient_name',
                    'like',
                    "%{$term}%"
                )

                ->orWhere(
                    'city',
                    'like',
                    "%{$term}%"
                )

                ->orWhere(
                    'hospital',
                    'like',
                    "%{$term}%"
                );

            });
        }


        $requests = $query
            ->paginate(15)
            ->withQueryString();


        $donors = Donor::where(
            'is_available',
            true
        )
        ->orderBy('name')
        ->get();


        return view(
            'admin.requests.index',
            compact(
                'requests',
                'donors'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DONOR MANAGEMENT
    |--------------------------------------------------------------------------
    */

    public function donors(Request $request)
    {
        $query = Donor::with('user')
            ->latest();


        if ($request->filled('blood_group')) {

            $query->where(
                'blood_group',
                $request->blood_group
            );
        }


        if ($request->filled('city')) {

            $query->where(
                'city',
                'like',
                '%' . trim($request->city) . '%'
            );
        }


        if ($request->filled('availability')) {

            $query->where(
                'is_available',
                $request->availability === 'available'
            );
        }


        if ($request->filled('search')) {

            $term = trim(
                $request->search
            );

            $query->where(function ($q) use ($term) {

                $q->where(
                    'name',
                    'like',
                    "%{$term}%"
                )

                ->orWhere(
                    'email',
                    'like',
                    "%{$term}%"
                )

                ->orWhere(
                    'phone',
                    'like',
                    "%{$term}%"
                )

                ->orWhere(
                    'city',
                    'like',
                    "%{$term}%"
                );

            });
        }


        $donors = $query
            ->paginate(15)
            ->withQueryString();


        return view(
            'admin.donors.index',
            compact('donors')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | USER MANAGEMENT
    |--------------------------------------------------------------------------
    */

    public function users(Request $request)
    {
        $query = User::where(
            'role',
            'user'
        )
        ->latest();


        if ($request->filled('search')) {

            $term = trim(
                $request->search
            );

            $query->where(function ($q) use ($term) {

                $q->where(
                    'name',
                    'like',
                    "%{$term}%"
                )

                ->orWhere(
                    'email',
                    'like',
                    "%{$term}%"
                )

                ->orWhere(
                    'phone',
                    'like',
                    "%{$term}%"
                )

                ->orWhere(
                    'city',
                    'like',
                    "%{$term}%"
                );

            });
        }


        $users = $query
            ->paginate(15)
            ->withQueryString();


        return view(
            'admin.users.index',
            compact('users')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DONATION HISTORY
    |--------------------------------------------------------------------------
    |
    | completed_at column is NOT required.
    | updated_at is used as completion timestamp.
    |
    */

    public function reports(Request $request)
    {
        // Weekly is the default so the admin immediately sees a one-week picture.
        $days = (int) $request->input('days', 7);
        $days = max(1, min($days, 3650));
        $from = now()->subDays($days - 1)->startOfDay();
        $to = now()->endOfDay();

        $loginLogs = SecurityLog::with('user')
            ->whereIn('event_type', ['successful_login', 'fixed_admin_login'])
            ->whereBetween('created_at', [$from, $to])
            ->latest()
            ->get();

        $donations = $this->donationQuery()->whereBetween('updated_at', [$from, $to])->get();
        if ($request->filled('blood_group')) {
            $donations = $donations->where('blood_group', $request->blood_group)->values();
        }

        $transactions = BloodInventoryTransaction::with(['donor','bloodRequest'])
            ->whereBetween('transaction_at', [$from, $to])
            ->latest('transaction_at')
            ->get();
        if ($request->filled('blood_group')) {
            $transactions = $transactions->where('blood_group', $request->blood_group)->values();
        }

        $bloodReceived = $transactions->where('type', 'received');
        $bloodIssued = $transactions->where('type', 'issued');
        $groups = ['O+','O-','A+','A-','B+','B-','AB+','AB-'];
        $groupStats = collect($groups)->map(function ($group) use ($bloodReceived, $bloodIssued, $donations) {
            $received = $bloodReceived->where('blood_group', $group)->sum('units');
            $issued = $bloodIssued->where('blood_group', $group)->sum('units');
            // Compatibility fallback for old completed records created before the transaction ledger.
            if ($received === 0) {
                $received = $donations->where('blood_group', $group)->sum(fn ($d) => (int)($d->units_fulfilled ?: $d->units ?: 1));
            }
            if ($issued === 0) {
                $issued = $donations->where('blood_group', $group)->sum(fn ($d) => (int)($d->units_fulfilled ?: $d->units ?: 1));
            }
            return ['blood_group'=>$group, 'received'=>$received, 'issued'=>$issued, 'balance'=>max(0, $received-$issued)];
        });

        $loginStats = collect([
            'Donor' => $loginLogs->filter(fn ($l) => $l->user?->role === 'donor')->count(),
            'Blood Need' => $loginLogs->filter(fn ($l) => $l->user?->role === 'user')->count(),
            'Admin' => $loginLogs->filter(fn ($l) => $l->event_type === 'fixed_admin_login' || $l->user?->role === 'admin')->count(),
        ]);
        $uniqueStats = collect([
            'Donor' => $loginLogs->filter(fn ($l) => $l->user?->role === 'donor')->pluck('user_id')->filter()->unique()->count(),
            'Blood Need' => $loginLogs->filter(fn ($l) => $l->user?->role === 'user')->pluck('user_id')->filter()->unique()->count(),
            'Admin' => $loginLogs->filter(fn ($l) => $l->event_type === 'fixed_admin_login' || $l->user?->role === 'admin')->pluck('user_id')->filter()->unique()->count(),
        ]);

        $summary = [
            'logins' => $loginLogs->count(),
            'unique_accounts' => $loginLogs->filter(fn ($l) => $l->user_id)->pluck('user_id')->unique()->count(),
            'donor_logins' => $loginStats['Donor'],
            'need_logins' => $loginStats['Blood Need'],
            'received_units' => $bloodReceived->sum('units') ?: $donations->sum(fn ($d) => (int)($d->units_fulfilled ?: $d->units ?: 1)),
            'issued_units' => $bloodIssued->sum('units') ?: $donations->sum(fn ($d) => (int)($d->units_fulfilled ?: $d->units ?: 1)),
            'donations' => $donations->count(),
            'units' => $donations->sum(fn ($d) => (int)($d->units_fulfilled ?: $d->units ?: 1)),
            'donors' => $donations->pluck('donor_id')->filter()->unique()->count(),
        ];

        return view('admin.reports.index', compact(
            'days','from','to','loginLogs','donations','transactions','bloodReceived','bloodIssued','groupStats','loginStats','uniqueStats','summary'
        ));
    }

    public function viewSystemReport(Request $request): Response
    {
        $days = (int) $request->input('days', 7);
        $days = max(1, min($days, 3650));
        $from = now()->subDays($days - 1)->startOfDay();
        $to = now()->endOfDay();
        $loginLogs = SecurityLog::with('user')->whereIn('event_type', ['successful_login', 'fixed_admin_login'])->whereBetween('created_at', [$from, $to])->latest()->get();
        $donations = $this->donationQuery()->whereBetween('updated_at', [$from, $to])->get();
        if ($request->filled('blood_group')) $donations = $donations->where('blood_group', $request->blood_group)->values();
        $transactions = BloodInventoryTransaction::with(['donor','bloodRequest'])->whereBetween('transaction_at', [$from, $to])->latest('transaction_at')->get();
        if ($request->filled('blood_group')) $transactions = $transactions->where('blood_group', $request->blood_group)->values();
        $pdf = $this->buildSystemReportPdf($loginLogs, $donations, $transactions, $days, $from, $to);
        return response($pdf, 200, ['Content-Type'=>'application/pdf','Content-Disposition'=>'inline; filename="BloodNexus-Weekly-System-Report-'.$days.'-days.pdf"','Content-Length'=>strlen($pdf)]);
    }

    public function downloadSystemReport(Request $request): Response
    {
        $days = (int) $request->input('days', 7);
        $days = max(1, min($days, 3650));
        $from = now()->subDays($days - 1)->startOfDay();
        $to = now()->endOfDay();

        $loginLogs = SecurityLog::with('user')
            ->whereIn('event_type', ['successful_login', 'fixed_admin_login'])
            ->whereBetween('created_at', [$from, $to])
            ->latest()->get();
        $donations = $this->donationQuery()->whereBetween('updated_at', [$from, $to])->get();
        if ($request->filled('blood_group')) $donations = $donations->where('blood_group', $request->blood_group)->values();
        $transactions = BloodInventoryTransaction::with(['donor','bloodRequest'])
            ->whereBetween('transaction_at', [$from, $to])->latest('transaction_at')->get();
        if ($request->filled('blood_group')) $transactions = $transactions->where('blood_group', $request->blood_group)->values();

        $pdf = $this->buildSystemReportPdf($loginLogs, $donations, $transactions, $days, $from, $to);
        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="BloodNexus-Weekly-System-Report-'.$days.'-days.pdf"',
            'Content-Length' => strlen($pdf),
        ]);
    }

    public function donations(Request $request)
    {
        $days = (int) $request->input(
            'days',
            30
        );


        $days = max(
            1,
            min(
                $days,
                365
            )
        );


        $from = now()
            ->subDays($days - 1)
            ->startOfDay();


        $to = now()
            ->endOfDay();


        $query = $this->donationQuery()
            ->whereBetween(
                'updated_at',
                [
                    $from,
                    $to
                ]
            );


        /*
        |--------------------------------------------------------------------------
        | BLOOD GROUP FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('blood_group')) {

            $query->where(
                'blood_group',
                $request->blood_group
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CITY FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('city')) {

            $query->where(
                'city',
                'like',
                '%' . trim($request->city) . '%'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $term = trim(
                $request->search
            );


            $query->where(function ($q) use ($term) {

                $q->where(
                    'patient_name',
                    'like',
                    "%{$term}%"
                )

                ->orWhere(
                    'hospital',
                    'like',
                    "%{$term}%"
                )

                ->orWhereHas(
                    'donor',
                    function ($d) use ($term) {

                        $d->where(
                            'name',
                            'like',
                            "%{$term}%"
                        );

                    }
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $donations = $query
            ->paginate(20)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | SUMMARY
        |--------------------------------------------------------------------------
        */

        $summary = [

            'donations' => (clone $query)
                ->count(),

            'units' => (clone $query)
                ->sum('units'),

            'donors' => (clone $query)
                ->distinct('donor_id')
                ->count('donor_id'),

        ];


        return view(
            'admin.donations.index',
            compact(
                'donations',
                'days',
                'summary'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD DONATION PDF
    |--------------------------------------------------------------------------
    */

    public function downloadDonationReport(
        Request $request
    ): Response
    {
        /*
        |--------------------------------------------------------------------------
        | REPORT DAYS
        |--------------------------------------------------------------------------
        */

        $days = (int) $request->input(
            'days',
            30
        );


        $days = max(
            1,
            min(
                $days,
                365
            )
        );


        /*
        |--------------------------------------------------------------------------
        | DATE RANGE
        |--------------------------------------------------------------------------
        */

        $from = now()
            ->subDays($days - 1)
            ->startOfDay();


        $to = now()
            ->endOfDay();


        /*
        |--------------------------------------------------------------------------
        | GET COMPLETED DONATIONS
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | We use updated_at.
        | completed_at is NOT required in database.
        |
        */

        $donations = $this->donationQuery()
            ->whereBetween(
                'updated_at',
                [
                    $from,
                    $to
                ]
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | BUILD PDF
        |--------------------------------------------------------------------------
        */

        $pdf = $this->buildDonationPdf(
            $donations,
            $days,
            $from,
            $to
        );


        /*
        |--------------------------------------------------------------------------
        | DOWNLOAD RESPONSE
        |--------------------------------------------------------------------------
        */

        return response(
            $pdf,
            200,
            [
                'Content-Type' =>
                    'application/pdf',

                'Content-Disposition' =>
                    'attachment; filename="ai-blood-bank-donation-report-' .
                    $days .
                    '-days.pdf"',

                'Content-Length' =>
                    strlen($pdf),
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN ANALYTICS
    |--------------------------------------------------------------------------
    */

    public function medicalHistory(Request $request)
    {
        $query = Donor::with('user')->latest();

        if ($request->filled('search')) {
            $term = trim($request->search);
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%")
                    ->orWhere('phone', 'like', "%{$term}%")
                    ->orWhere('city', 'like', "%{$term}%")
                    ->orWhere('area', 'like', "%{$term}%");
            });
        }

        $donors = $query->paginate(15)->withQueryString();

        return view('admin.medical-history.index', compact('donors'));
    }

    public function donorIntelligence(Request $request)
    {
        $query=Donor::with('user')->latest();
        if($request->filled('blood_group')) $query->where('blood_group',$request->blood_group);
        if($request->filled('city')) $query->where('city','like','%'.trim($request->city).'%');
        $donors=$query->paginate(18)->withQueryString();
        $donors->getCollection()->transform(function($d){
            $requests=BloodRequest::where('donor_id',$d->id);
            $d->accepted_total=(clone $requests)->where('status','accepted')->count();
            $d->completed_total=(clone $requests)->where('status','completed')->count();
            $d->cooldown_days=$d->donationCooldownDays();
            $d->reliability=$d->accepted_total ? (int)round(($d->completed_total/$d->accepted_total)*100) : 100;
            return $d;
        });
        return view('admin.donors.intelligence',compact('donors'));
    }

    public function messagingControl()
    {
        $connections=BloodRequest::with(['user','donor'])
            ->whereNotNull('donor_id')->whereIn('status',['matched','accepted','completed','rejected','cancelled'])
            ->latest()->paginate(20);
        return view('admin.messaging.index',compact('connections'));
    }

    public function appeals()
    {
        $appeals=AccountAppeal::with('user')->latest()->paginate(20);
        return view('admin.security.appeals',compact('appeals'));
    }

    public function reviewAppeal(Request $request, AccountAppeal $appeal)
    {
        $data=$request->validate(['status'=>'required|in:approved,rejected','admin_note'=>'nullable|string|max:1000']);
        $appeal->update(['status'=>$data['status'],'admin_note'=>$data['admin_note']??null,'reviewed_at'=>now()]);
        if($data['status']==='approved' && $appeal->user){
            $appeal->user->forceFill(['locked_until'=>null,'failed_login_attempts'=>0,'is_suspended'=>false,'suspended_until'=>null,'security_blocked'=>false,'security_block_reason'=>null,'security_blocked_at'=>null])->saveQuietly();
            SecurityLog::record(['user_id'=>$appeal->user_id,'event_type'=>'appeal_approved','severity'=>'info','risk_score'=>0,'ip_address'=>$request->ip(),'user_agent'=>substr((string)$request->userAgent(),0,1000),'route'=>$request->route()?->getName(),'method'=>'POST','message'=>'Administrator approved account review appeal and restored access.','metadata'=>['appeal_id'=>$appeal->id]]);
        }
        if($data['status']==='rejected' && $appeal->user){
            // Rejection keeps the account blocked. The same email cannot submit a second appeal.
            $appeal->user->forceFill([
                'security_blocked'=>true,
                'security_block_reason'=>$appeal->user->security_block_reason ?: 'Account appeal rejected by administrator.',
                'security_blocked_at'=>$appeal->user->security_blocked_at ?: now(),
                'locked_until'=>null,
                'failed_login_attempts'=>0,
            ])->saveQuietly();
            SecurityLog::record(['user_id'=>$appeal->user_id,'event_type'=>'appeal_rejected_account_remains_blocked','severity'=>'high','risk_score'=>90,'ip_address'=>$request->ip(),'user_agent'=>substr((string)$request->userAgent(),0,1000),'route'=>$request->route()?->getName(),'method'=>'POST','message'=>'Administrator rejected the one-time account appeal; account remains blocked.','metadata'=>['appeal_id'=>$appeal->id]]);
        }
        return back()->with('success','Appeal '.$appeal->status.'.');
    }

    public function adminAi()
    {
        $stats=[
            'users'=>User::where('role','user')->count(),
            'donors'=>Donor::count(),
            'available_donors'=>Donor::where('is_available',true)->count(),
            'critical'=>BloodRequest::where('urgency','critical')->whereIn('status',['pending','matched','accepted'])->count(),
            'pending'=>BloodRequest::where('status','pending')->count(),
            'completed'=>BloodRequest::where('status','completed')->count(),
            'security_today'=>SecurityLog::whereDate('created_at',today())->count(),
        ];
        return view('admin.ai.index',compact('stats'));
    }

    public function adminAiAsk(Request $request)
    {
        $q=strtolower(trim($request->input('question','')));
        $answer='Ask about blood demand, donors, critical requests, completed donations, cities or security activity.';
        if(str_contains($q,'a+') && str_contains($q,'donor')) $answer='A+ available donors: '.Donor::where('blood_group','A+')->where('is_available',true)->count().'.';
        elseif(str_contains($q,'critical')) $answer='Active critical requests: '.BloodRequest::where('urgency','critical')->whereIn('status',['pending','matched','accepted'])->count().'.';
        elseif(str_contains($q,'completed')) $answer='Completed donations/requests: '.BloodRequest::where('status','completed')->count().'.';
        elseif(str_contains($q,'pending')) $answer='Pending blood requests: '.BloodRequest::where('status','pending')->count().'.';
        elseif(str_contains($q,'security') || str_contains($q,'suspicious')) $answer='Security events today: '.SecurityLog::whereDate('created_at',today())->count().'. Critical open events: '.SecurityLog::where('severity','critical')->whereNull('resolved_at')->count().'.';
        elseif(str_contains($q,'city') || str_contains($q,'area')) { $top=BloodRequest::select('city',DB::raw('COUNT(*) as total'))->groupBy('city')->orderByDesc('total')->first(); $answer=$top ? 'Highest blood-request area: '.$top->city.' with '.$top->total.' requests.' : 'No city demand data available yet.'; }
        elseif(str_contains($q,'shortage') || str_contains($q,'risk')) { $rows=BloodRequest::select('blood_group',DB::raw('COUNT(*) as total'))->groupBy('blood_group')->orderByDesc('total')->get(); $answer=$rows->isEmpty()?'No demand data available.':$rows->map(function($r){$a=Donor::where('blood_group',$r->blood_group)->where('is_available',true)->count();return $r->blood_group.': risk '.min(100,(int)round(($r->total/max(1,$a))*35)).'/100';})->implode(' | '); }
        return back()->with('ai_answer',$answer);
    }

    public function security(Request $request)
    {
        $query = SecurityLog::with('user')->latest();

        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }
        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }
        if ($request->filled('search')) {
            $term = trim($request->search);
            $query->where(function ($q) use ($term) {
                $q->where('incident_id', 'like', "%{$term}%")
                  ->orWhere('ip_address', 'like', "%{$term}%")
                  ->orWhere('message', 'like', "%{$term}%")
                  ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$term}%")->orWhere('email', 'like', "%{$term}%"));
            });
        }

        $logs = $query->paginate(25)->withQueryString();

        $frequentUsers = User::whereIn('role', ['user', 'donor'])
            ->withCount(['securityLogs as successful_logins_today' => function ($q) {
                $q->where('event_type', 'successful_login')->whereDate('created_at', today());
            }])
            ->having('successful_logins_today', '>=', 5)
            ->orderByDesc('successful_logins_today')
            ->limit(25)
            ->get();

        // Brute-force signals are grouped by account + IP over the latest 10 minutes.
        $bruteForceAlerts = SecurityLog::with('user')
            ->whereIn('event_type', ['failed_login', 'account_permanently_blocked'])
            ->where('created_at', '>=', now()->subMinutes(10))
            ->latest()
            ->get()
            ->groupBy(fn ($log) => ($log->user_id ?? 'guest') . '|' . ($log->ip_address ?? 'unknown'))
            ->map(function ($group) {
                $latest = $group->first();
                return (object) [
                    'user' => $latest->user,
                    'user_id' => $latest->user_id,
                    'ip_address' => $latest->ip_address,
                    'attempts' => $group->count(),
                    'first_at' => $group->last()->created_at,
                    'last_at' => $latest->created_at,
                    'high_risk' => $group->count() >= 3 || $group->contains(fn ($x) => $x->severity === 'critical'),
                ];
            })
            ->filter(fn ($alert) => $alert->high_risk)
            ->sortByDesc('attempts')
            ->values();

        $blockedUsers = User::where('security_blocked', true)
            ->whereIn('role', ['user', 'donor'])
            ->latest('security_blocked_at')
            ->get();

        $stats = [
            'total' => SecurityLog::count(),
            'locked' => User::whereNotNull('locked_until')->where('locked_until','>',now())->count(),
            'blocked' => $blockedUsers->count(),
            'bruteforce' => $bruteForceAlerts->count(),
            'appeals' => AccountAppeal::where('status','pending')->count(),
            'critical' => SecurityLog::where('severity', 'critical')->whereNull('resolved_at')->count(),
            'high' => SecurityLog::where('severity', 'high')->whereNull('resolved_at')->count(),
            'medium' => SecurityLog::where('severity', 'medium')->whereNull('resolved_at')->count(),
            'today' => SecurityLog::whereDate('created_at', today())->count(),
        ];

        return view('admin.security.index', compact('logs', 'stats', 'frequentUsers', 'bruteForceAlerts', 'blockedUsers'));
    }

    public function unlockUser(Request $request, User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Administrator accounts cannot be unlocked from this screen.');
        }

        $user->forceFill([
            'locked_until' => null,
            'failed_login_attempts' => 0,
            'security_blocked' => false,
            'security_block_reason' => null,
            'security_blocked_at' => null,
        ])->saveQuietly();

        SecurityLog::record([
            'user_id' => $user->id,
            'event_type' => 'admin_unlock',
            'severity' => 'info',
            'risk_score' => 0,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 1000),
            'route' => $request->route()?->getName(),
            'method' => 'POST',
            'message' => 'Administrator manually unlocked the temporarily locked account.',
            'metadata' => ['unlocked_user_id' => $user->id],
        ]);

        return back()->with('success', 'Account unlocked successfully.');
    }

    public function sendSecurityWarning(Request $request, User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Administrator accounts are excluded from user security warnings.');
        }

        $data = $request->validate([
            'message' => ['required', 'string', 'min:10', 'max:1200'],
        ]);

        NotificationHelper::send(
            $user->id,
            'admin_security_warning',
            '⚠️ Security Warning from BloodNexus Admin',
            $data['message'],
            [
                'admin_message' => true,
                'security_warning' => true,
                'sent_at' => now()->toIso8601String(),
            ]
        );

        SecurityLog::record([
            'user_id' => $user->id,
            'event_type' => 'admin_security_warning_sent',
            'severity' => 'high',
            'risk_score' => 75,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 1000),
            'route' => $request->route()?->getName(),
            'method' => 'POST',
            'message' => 'Administrator sent a security warning to the user.',
            'metadata' => ['target_user_id' => $user->id],
        ]);

        return back()->with('success', 'Security warning sent to ' . $user->name . '.');
    }

    public function blockUserForSecurity(Request $request, User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Administrator accounts cannot be blocked here.');
        }

        $data = $request->validate([
            'reason' => ['required', 'string', 'min:10', 'max:255'],
        ]);

        $user->forceFill([
            'security_blocked' => true,
            'security_block_reason' => $data['reason'],
            'security_blocked_at' => now(),
            'locked_until' => null,
            'failed_login_attempts' => 0,
        ])->saveQuietly();

        NotificationHelper::send(
            $user->id,
            'admin_security_block',
            '🔴 BloodNexus Security Action',
            'Your account has been blocked by an administrator. Reason: ' . $data['reason'] . ' Please use the account appeal process if you believe this was a mistake.',
            ['admin_message' => true, 'security_block' => true]
        );

        SecurityLog::record([
            'user_id' => $user->id,
            'event_type' => 'admin_security_block',
            'severity' => 'critical',
            'risk_score' => 100,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 1000),
            'route' => $request->route()?->getName(),
            'method' => 'POST',
            'message' => 'Administrator permanently blocked a user account.',
            'metadata' => ['reason' => $data['reason']],
        ]);

        return back()->with('success', $user->name . ' has been permanently blocked.');
    }

    public function resolveSecurityLog(SecurityLog $securityLog)
    {
        $securityLog->update(['resolved_at' => now()]);
        return back()->with('success', "Security incident {$securityLog->incident_id} marked as resolved.");
    }

    public function suspendUser(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Admin accounts cannot be suspended here.');
        }

        $user->update([
            'is_suspended' => true,
            'suspended_until' => now()->addHours(24),
        ]);

        SecurityLog::record([
            'user_id' => $user->id,
            'event_type' => 'admin_user_suspension',
            'severity' => 'high',
            'risk_score' => 80,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'route' => request()->route()?->getName(),
            'method' => request()->method(),
            'message' => 'Administrator temporarily suspended user for suspicious activity.',
            'metadata' => ['target_user_id' => $user->id, 'duration_hours' => 24],
        ]);

        return back()->with('success', $user->name . ' has been suspended for 24 hours.');
    }

    public function unsuspendUser(User $user)
    {
        $user->update(['is_suspended' => false, 'suspended_until' => null]);
        return back()->with('success', $user->name . ' suspension removed.');
    }

    public function analytics()
    {
        $totalUsers = User::where(
            'role',
            'user'
        )->count();


        $totalDonors = Donor::count();


        $totalRequests = BloodRequest::count();


        $completedRequests = BloodRequest::where(
            'status',
            'completed'
        )->count();


        $cancelledRequests = BloodRequest::where(
            'status',
            'cancelled'
        )->count();


        $acceptedRequests = BloodRequest::where(
            'status',
            'accepted'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | BLOOD GROUP STATS
        |--------------------------------------------------------------------------
        */

        $bloodGroupStats = Donor::select(
            'blood_group',
            DB::raw('COUNT(*) as total')
        )
        ->groupBy('blood_group')
        ->pluck(
            'total',
            'blood_group'
        )
        ->toArray();


        /*
        |--------------------------------------------------------------------------
        | MONTHLY DONATIONS
        |--------------------------------------------------------------------------
        |
        | completed_at removed.
        | updated_at is used.
        |
        */

        $monthlyDonations = BloodRequest::where(
            'status',
            'completed'
        )
        ->where(
            'updated_at',
            '>=',
            now()
                ->subMonths(6)
                ->startOfMonth()
        )
        ->get([
            'updated_at'
        ])
        ->groupBy(
            function ($request) {

                return Carbon::parse(
                    $request->updated_at
                )->format('Y-m');

            }
        )
        ->map(
            function ($items) {

                return $items->count();

            }
        )
        ->sortKeys()
        ->toArray();


        return view(
            'admin.analytics.index',
            compact(
                'totalUsers',
                'totalDonors',
                'totalRequests',
                'completedRequests',
                'cancelledRequests',
                'acceptedRequests',
                'bloodGroupStats',
                'monthlyDonations'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | BLOOD DEMAND INTELLIGENCE REPORT
    |--------------------------------------------------------------------------
    */

    public function demandReport(Request $request)
    {
        $days = max(1, min((int) $request->input('days', 1), 365));
        $from = now()->subDays($days - 1)->startOfDay();
        $to = now()->endOfDay();

        $base = BloodRequest::query()->whereBetween('created_at', [$from, $to]);
        $bloodGroup = $request->input('blood_group');
        if ($bloodGroup) { $base->where('blood_group', $bloodGroup); }

        $totalRequests = (clone $base)->count();
        $totalUnits = (clone $base)->sum('units');
        $emergencyRequests = (clone $base)->where(function ($q) {
            $q->where('urgency', 'critical')->orWhere('emergency_mode', true);
        })->count();

        $byBloodGroup = (clone $base)
            ->select('blood_group', DB::raw('COUNT(*) as requests'), DB::raw('COALESCE(SUM(units),0) as units'))
            ->groupBy('blood_group')->orderByDesc('requests')->get();

        $byCity = (clone $base)
            ->select('city', DB::raw('COUNT(*) as requests'), DB::raw('COALESCE(SUM(units),0) as units'))
            ->groupBy('city')->orderByDesc('requests')->get();

        $byArea = (clone $base)
            ->select('city', 'area', DB::raw('COUNT(*) as requests'), DB::raw('COALESCE(SUM(units),0) as units'))
            ->groupBy('city', 'area')->orderByDesc('requests')->get();

        $byHospital = (clone $base)
            ->select('hospital', 'city', DB::raw('COUNT(*) as requests'), DB::raw('COALESCE(SUM(units),0) as units'))
            ->groupBy('hospital', 'city')->orderByDesc('requests')->get();

        $byReason = (clone $base)
            ->select('reason', DB::raw('COUNT(*) as requests'), DB::raw('COALESCE(SUM(units),0) as units'))
            ->groupBy('reason')->orderByDesc('requests')->get();

        $topBlood = $byBloodGroup->first();
        $topCity = $byCity->first();
        $topArea = $byArea->first();
        $topHospital = $byHospital->first();
        $needersForGroup = $bloodGroup ? (clone $base)->distinct('user_id')->count('user_id') : null;
        $donorsForGroup = $bloodGroup ? Donor::where('blood_group', $bloodGroup)->count() : null;
        $availableDonorsForGroup = $bloodGroup ? Donor::where('blood_group', $bloodGroup)->where('is_available', true)->count() : null;

        return view('admin.analytics.demand-report', compact(
            'days', 'from', 'to', 'totalRequests', 'totalUnits', 'emergencyRequests',
            'byBloodGroup', 'byCity', 'byArea', 'byHospital', 'byReason',
            'topBlood', 'topCity', 'topArea', 'topHospital', 'bloodGroup',
            'needersForGroup', 'donorsForGroup', 'availableDonorsForGroup'
        ));
    }

    public function viewDemandReport(Request $request): Response
    {
        $days = max(1, min((int) $request->input('days', 1), 365));
        $from = now()->subDays($days - 1)->startOfDay();
        $to = now()->endOfDay();
        $base = BloodRequest::query()->whereBetween('created_at', [$from, $to]);
        $bloodGroup = $request->input('blood_group');
        if ($bloodGroup) $base->where('blood_group', $bloodGroup);
        $blood = (clone $base)->select('blood_group', DB::raw('COUNT(*) as requests'), DB::raw('COALESCE(SUM(units),0) as units'))->groupBy('blood_group')->orderByDesc('requests')->get();
        $cities = (clone $base)->select('city', DB::raw('COUNT(*) as requests'), DB::raw('COALESCE(SUM(units),0) as units'))->groupBy('city')->orderByDesc('requests')->get();
        $areas = (clone $base)->select('city','area', DB::raw('COUNT(*) as requests'), DB::raw('COALESCE(SUM(units),0) as units'))->groupBy('city','area')->orderByDesc('requests')->get();
        $hospitals = (clone $base)->select('hospital','city', DB::raw('COUNT(*) as requests'), DB::raw('COALESCE(SUM(units),0) as units'))->groupBy('hospital','city')->orderByDesc('requests')->get();
        $reasons = (clone $base)->select('reason', DB::raw('COUNT(*) as requests'), DB::raw('COALESCE(SUM(units),0) as units'))->groupBy('reason')->orderByDesc('requests')->get();
        $totalRequests=(clone $base)->count(); $totalUnits=(clone $base)->sum('units');
        $emergencyRequests=(clone $base)->where(function($q){$q->where('urgency','critical')->orWhere('emergency_mode',true);})->count();
        $needersForGroup = $bloodGroup ? (clone $base)->distinct('user_id')->count('user_id') : null;
        $donorsForGroup = $bloodGroup ? Donor::where('blood_group',$bloodGroup)->count() : null;
        $availableDonorsForGroup = $bloodGroup ? Donor::where('blood_group',$bloodGroup)->where('is_available',true)->count() : null;
        $topBlood=$blood->first(); $topCity=$cities->first(); $topArea=$areas->first(); $topHospital=$hospitals->first();
        $pdf=$this->buildDemandPdf($blood,$cities,$areas,$hospitals,$reasons,$days,$from,$to,$bloodGroup,$needersForGroup,$donorsForGroup,$availableDonorsForGroup);
        return response($pdf,200,['Content-Type'=>'application/pdf','Content-Disposition'=>'inline; filename="bloodnexus-demand-report-'.$days.'-days.pdf"','Content-Length'=>strlen($pdf)]);
    }

    public function downloadDemandReport(Request $request): Response
    {
        $days = max(1, min((int) $request->input('days', 1), 365));
        $from = now()->subDays($days - 1)->startOfDay(); $to = now()->endOfDay();
        $base = BloodRequest::query()->whereBetween('created_at', [$from, $to]);
        $bloodGroup = $request->input('blood_group'); if ($bloodGroup) $base->where('blood_group',$bloodGroup);
        $blood=(clone $base)->select('blood_group',DB::raw('COUNT(*) as requests'),DB::raw('COALESCE(SUM(units),0) as units'))->groupBy('blood_group')->orderByDesc('requests')->get();
        $cities=(clone $base)->select('city',DB::raw('COUNT(*) as requests'),DB::raw('COALESCE(SUM(units),0) as units'))->groupBy('city')->orderByDesc('requests')->get();
        $areas=(clone $base)->select('city','area',DB::raw('COUNT(*) as requests'),DB::raw('COALESCE(SUM(units),0) as units'))->groupBy('city','area')->orderByDesc('requests')->get();
        $hospitals=(clone $base)->select('hospital','city',DB::raw('COUNT(*) as requests'),DB::raw('COALESCE(SUM(units),0) as units'))->groupBy('hospital','city')->orderByDesc('requests')->get();
        $reasons=(clone $base)->select('reason',DB::raw('COUNT(*) as requests'),DB::raw('COALESCE(SUM(units),0) as units'))->groupBy('reason')->orderByDesc('requests')->get();
        $needersForGroup=$bloodGroup?(clone $base)->distinct('user_id')->count('user_id'):null; $donorsForGroup=$bloodGroup?Donor::where('blood_group',$bloodGroup)->count():null; $availableDonorsForGroup=$bloodGroup?Donor::where('blood_group',$bloodGroup)->where('is_available',true)->count():null;
        $pdf=$this->buildDemandPdf($blood,$cities,$areas,$hospitals,$reasons,$days,$from,$to,$bloodGroup,$needersForGroup,$donorsForGroup,$availableDonorsForGroup);
        return response($pdf,200,['Content-Type'=>'application/pdf','Content-Disposition'=>'attachment; filename="bloodnexus-demand-report-'.$days.'-days.pdf"','Content-Length'=>strlen($pdf)]);
    }

    private function buildDemandPdf($blood,$cities,$areas,$hospitals,$reasons,$days,$from,$to,$bloodGroup,$needers,$donors,$availableDonors): string
    {
        $lines=['BLOODNEXUS - BLOOD DEMAND INTELLIGENCE REPORT','Period: '.$from->format('d M Y').' to '.$to->format('d M Y').' ('.$days.' day(s))','Generated: '.now()->format('d M Y H:i:s'),'Filter: '.($bloodGroup ?: 'All blood groups'),''];
        if($bloodGroup){$lines[]='SELECTED BLOOD GROUP SUMMARY';$lines[]='Blood group: '.$bloodGroup;$lines[]='People currently needing this exact blood group: '.(int)$needers;$lines[]='Registered donors with this exact blood group: '.(int)$donors;$lines[]='Currently available donors with this exact blood group: '.(int)$availableDonors;$lines[]='';}
        $lines[]='BLOOD GROUP DEMAND';$lines[]=str_repeat('-',70);foreach($blood as $r)$lines[]=sprintf('%-8s Requests: %-5s Units: %s',$r->blood_group?:'-',$r->requests,$r->units);
        $lines[]='';$lines[]='CITY DEMAND';$lines[]=str_repeat('-',70);foreach($cities as $r)$lines[]=sprintf('%-25s Requests: %-5s Units: %s',mb_substr($r->city?:'-',0,25),$r->requests,$r->units);
        $lines[]='';$lines[]='AREA DEMAND';$lines[]=str_repeat('-',70);foreach($areas as $r)$lines[]=sprintf('%-18s %-20s Requests: %-4s Units: %s',mb_substr($r->city?:'-',0,18),mb_substr($r->area?:'-',0,20),$r->requests,$r->units);
        $lines[]='';$lines[]='HOSPITAL DEMAND';$lines[]=str_repeat('-',70);foreach($hospitals as $r)$lines[]=sprintf('%-28s %-15s Requests: %-4s Units: %s',mb_substr($r->hospital?:'-',0,28),mb_substr($r->city?:'-',0,15),$r->requests,$r->units);
        $lines[]='';$lines[]='REASON / MEDICAL NEED';$lines[]=str_repeat('-',70);foreach($reasons as $r)$lines[]=sprintf('%-25s Requests: %-5s Units: %s',mb_substr($r->reason?:'Not specified',0,25),$r->requests,$r->units);
        return $this->buildSimpleTextPdf($lines);
    }


    /*
    |--------------------------------------------------------------------------
    | PREMIUM PDF / DONATION CERTIFICATE
    |--------------------------------------------------------------------------
    | The project intentionally uses a small built-in PDF writer so PDF
    | generation does not depend on an extra Composer package or browser.
    |--------------------------------------------------------------------------
    */

    public function donorCertificateView(BloodRequest $bloodRequest): Response
    {
        abort_unless(
            auth()->user()->role === 'donor' &&
            (int) $bloodRequest->donor_id === (int) auth()->user()->donor?->id,
            403
        );

        abort_unless($bloodRequest->status === 'completed', 404);

        $bloodRequest->load(['donor', 'user']);

        return response()
            ->view('donor-certificate', compact('bloodRequest'));
    }

    public function donorCertificate(BloodRequest $bloodRequest): Response
    {
        abort_unless(
            auth()->user()->role === 'donor' &&
            (int) $bloodRequest->donor_id === (int) auth()->user()->donor?->id,
            403
        );

        abort_unless($bloodRequest->status === 'completed', 404);

        $bloodRequest->load(['donor', 'user']);

        $donor = $bloodRequest->donor;
        $units = max(1, (int) ($bloodRequest->units_fulfilled ?: $bloodRequest->units ?: 1));
        $date = optional($bloodRequest->donation_date)->format('d F Y')
            ?? optional($bloodRequest->completed_at)->format('d F Y')
            ?? optional($bloodRequest->updated_at)->format('d F Y')
            ?? 'N/A';
        $time = $bloodRequest->donation_time
            ? date('h:i A', strtotime((string) $bloodRequest->donation_time))
            : (optional($bloodRequest->completed_at)->format('h:i A') ?? 'N/A');
        $location = trim(($bloodRequest->area ? $bloodRequest->area . ', ' : '') . ($bloodRequest->city ?: ''));
        $certificateId = 'BNX-DON-' . now()->format('Y') . '-' . str_pad((string) $bloodRequest->id, 6, '0', STR_PAD_LEFT);

        $pdf = $this->buildCertificatePdf([
            'certificate_id' => $certificateId,
            'donor' => $donor?->name ?: 'BloodNexus Donor',
            'blood_group' => $donor?->blood_group ?: ($bloodRequest->blood_group ?: 'N/A'),
            'units' => $units,
            'date' => $date,
            'time' => $time,
            'request_id' => $bloodRequest->id,
            'hospital' => $bloodRequest->hospital ?: 'BloodNexus Partner Hospital',
            'location' => $location ?: 'N/A',
        ]);

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $certificateId . '.pdf"',
            'Content-Length' => strlen($pdf),
        ]);
    }

    private function buildDonationPdf($donations, int $days, $from, $to): string
    {
        $rows = [];
        foreach ($donations as $d) {
            $rows[] = [
                '#' . $d->id,
                $d->donor?->name ?: 'Unknown',
                $d->blood_group ?: '—',
                $d->patient_name ?: '—',
                trim(($d->hospital ?: '—') . ($d->city ? ' / ' . $d->city : '')),
                (string) max(1, (int) ($d->units_fulfilled ?: $d->units ?: 1)),
                $d->donation_date?->format('d M Y') ?: ($d->completed_at?->format('d M Y') ?: $d->updated_at?->format('d M Y') ?: '—'),
            ];
        }

        return $this->buildTablePdf(
            'BloodNexus • Donation History',
            'Completed donor contributions recorded by the system',
            'Period: ' . $from->format('d M Y') . ' to ' . $to->format('d M Y') . '  •  ' . $days . ' day(s)',
            [
                'Donation', 'Donor', 'Blood', 'Need / Patient',
                'Hospital / City', 'Units', 'Date'
            ],
            $rows,
            [0.80, 0.06, 0.12]
        );
    }

    private function buildSystemReportPdf($loginLogs, $donations, $transactions, int $days, $from, $to): string
    {
        $received = 0;
        foreach ($donations as $d) {
            $received += max(1, (int) ($d->units_fulfilled ?: $d->units ?: 1));
        }

        $issued = 0;
        foreach ($transactions as $t) {
            if (($t->type ?? $t->transaction_type ?? '') === 'issued' || str_contains(strtolower((string) ($t->type ?? '')), 'issue')) {
                $issued += max(0, (int) ($t->units ?? 0));
            }
        }

        if ($issued === 0) {
            $issued = (int) $donations->sum(function ($d) {
                return (int) ($d->units_fulfilled ?: 0);
            });
        }

        $groups = [];
        foreach ($donations as $d) {
            $g = $d->blood_group ?: 'Unknown';
            $groups[$g] = ($groups[$g] ?? 0) + max(1, (int) ($d->units_fulfilled ?: $d->units ?: 1));
        }
        ksort($groups);

        $pages = [];
        $content = $this->pdfHeader(
            'BLOODNEXUS',
            'WEEKLY SYSTEM REPORT',
            'Live operational summary • ' . $from->format('d M Y') . ' — ' . $to->format('d M Y')
        );

        $content .= $this->pdfCard(40, 660, 160, 78, 'DONOR LOGINS', (string) $loginLogs->filter(fn($l) => $l->user?->role === 'donor')->count(), [0.80,0.06,0.12]);
        $content .= $this->pdfCard(217, 660, 160, 78, 'BLOOD NEED LOGINS', (string) $loginLogs->filter(fn($l) => $l->user?->role === 'user')->count(), [0.09,0.39,0.76]);
        $content .= $this->pdfCard(394, 660, 160, 78, 'BLOOD RECEIVED', $received . ' units', [0.08,0.48,0.29]);
        $content .= $this->pdfCard(40, 562, 160, 78, 'BLOOD ISSUED', $issued . ' units', [0.46,0.25,0.75]);
        $content .= $this->pdfCard(217, 562, 160, 78, 'LOGIN EVENTS', (string) $loginLogs->count(), [0.09,0.39,0.76]);
        $content .= $this->pdfCard(394, 562, 160, 78, 'DONATIONS', (string) $donations->count(), [0.08,0.48,0.29]);

        $content .= $this->pdfSectionTitle('BLOOD GROUP RECEIVED', 515);
        $y = 488;
        $x = 45;
        foreach ($groups as $g => $units) {
            $content .= $this->pdfPill($x, $y, 245, 31, $g, $units . ' unit(s)', [0.80,0.06,0.12]);
            $x += 255;
            if ($x > 330) {
                $x = 45;
                $y -= 42;
            }
            if ($y < 90) {
                $pages[] = $content . $this->pdfFooter(count($pages) + 1);
                $content = $this->pdfHeader('BLOODNEXUS', 'WEEKLY SYSTEM REPORT', 'Blood group continuation');
                $y = 720;
                $x = 45;
            }
        }

        $content .= $this->pdfSectionTitle('RECENT COMPLETED DONATIONS', $y - 12);
        $tableY = $y - 40;
        $headers = ['Donor', 'Blood', 'Units', 'Hospital', 'Date'];
        $widths = [145, 60, 55, 190, 105];
        $content .= $this->pdfTableHeader($headers, $widths, 40, $tableY);
        $tableY -= 26;

        $count = 0;
        foreach ($donations as $d) {
            if ($tableY < 70) {
                $pages[] = $content . $this->pdfFooter(count($pages) + 1);
                $content = $this->pdfHeader('BLOODNEXUS', 'WEEKLY SYSTEM REPORT', 'Completed donation records • continuation');
                $tableY = 745;
                $content .= $this->pdfTableHeader($headers, $widths, 40, $tableY);
                $tableY -= 26;
            }
            $content .= $this->pdfTableRow([
                $d->donor?->name ?: 'Unknown',
                $d->blood_group ?: '—',
                (string) max(1, (int) ($d->units_fulfilled ?: $d->units ?: 1)),
                $d->hospital ?: '—',
                $d->donation_date?->format('d M Y') ?: ($d->updated_at?->format('d M Y') ?: '—'),
            ], $widths, 40, $tableY, $count % 2 === 0);
            $tableY -= 25;
            $count++;
        }

        $pages[] = $content . $this->pdfFooter(count($pages) + 1);

        return $this->compilePdf($pages);
    }

    private function buildCertificatePdf(array $d): string
    {
        $c = $this->pdfHeader('BLOODNEXUS', 'DONATION CERTIFICATE', 'Verified contribution record');
        $c .= $this->pdfRect(28, 36, 539, 760, [1,1,1], [0.80,0.06,0.12], 2);
        $c .= $this->pdfRect(42, 50, 511, 732, [0.995,0.998,1], [0.86,0.70,0.72], 0.8);

        $c .= $this->pdfText(297, 710, 'CERTIFICATE OF APPRECIATION', 23, [0.08,0.17,0.29], true, 'center');
        $c .= $this->pdfText(297, 680, 'This certificate is proudly awarded to', 11, [0.40,0.45,0.52], false, 'center');
        $c .= $this->pdfText(297, 638, $d['donor'], 28, [0.80,0.06,0.12], true, 'center');
        $c .= $this->pdfLine(155, 625, 440, 625, [0.80,0.06,0.12], 1.1);

        $c .= $this->pdfText(297, 592, 'for completing a recorded blood donation through BloodNexus.', 12, [0.20,0.27,0.36], false, 'center');

        $c .= $this->pdfCard(65, 475, 215, 82, 'BLOOD GROUP', $d['blood_group'], [0.80,0.06,0.12]);
        $c .= $this->pdfCard(315, 475, 215, 82, 'UNITS DONATED', (string) $d['units'], [0.08,0.48,0.29]);

        $c .= $this->pdfText(65, 438, 'DONATION DETAILS', 11, [0.80,0.06,0.12], true);
        $c .= $this->pdfKeyValue(65, 410, 'Donation date', $d['date']);
        $c .= $this->pdfKeyValue(65, 385, 'Donation time', $d['time']);
        $c .= $this->pdfKeyValue(65, 360, 'Request ID', '#' . $d['request_id']);

        $c .= $this->pdfText(315, 438, 'LOCATION', 11, [0.80,0.06,0.12], true);
        $c .= $this->pdfKeyValue(315, 410, 'Hospital', $d['hospital']);
        $c .= $this->pdfKeyValue(315, 385, 'Area / City', $d['location']);
        $c .= $this->pdfKeyValue(315, 360, 'Status', 'COMPLETED');

        $c .= $this->pdfRect(65, 285, 465, 48, [0.95,0.985,0.965], [0.55,0.78,0.64], 0.8);
        $c .= $this->pdfText(82, 315, '✓  VERIFIED DONATION RECORD', 11, [0.08,0.45,0.27], true);
        $c .= $this->pdfText(82, 296, 'Recorded as completed in the BloodNexus system.', 9, [0.30,0.40,0.34]);

        $c .= $this->pdfText(297, 215, 'CERTIFICATE ID', 9, [0.45,0.50,0.57], true, 'center');
        $c .= $this->pdfText(297, 190, $d['certificate_id'], 15, [0.08,0.17,0.29], true, 'center');

        $c .= $this->pdfLine(90, 130, 245, 130, [0.45,0.50,0.57], 0.8);
        $c .= $this->pdfLine(350, 130, 505, 130, [0.45,0.50,0.57], 0.8);
        $c .= $this->pdfText(167, 112, 'BloodNexus Administration', 9, [0.40,0.45,0.52], false, 'center');
        $c .= $this->pdfText(427, 112, 'System Generated Certificate', 9, [0.40,0.45,0.52], false, 'center');

        $c .= $this->pdfText(297, 72, "Every donation can become someone's second chance. Thank you for donating blood.", 9, [0.45,0.50,0.57], false, 'center');
        $c .= $this->pdfText(297, 52, $d['certificate_id'], 7, [0.65,0.68,0.72], false, 'center');

        return $this->compilePdf([$c]);
    }

    private function buildTablePdf(string $title, string $subtitle, string $period, array $headers, array $rows, array $accent): string
    {
        $pages = [];
        $content = $this->pdfHeader('BLOODNEXUS', $title, $period);
        $content .= $this->pdfText(40, 690, $subtitle, 10, [0.40,0.45,0.52]);
        $content .= $this->pdfRect(40, 640, 515, 34, [0.97,0.98,1], [0.88,0.90,0.94], 0.7);
        $content .= $this->pdfText(54, 661, 'Generated ' . now()->format('d M Y • h:i A'), 8, [0.42,0.47,0.54]);
        $content .= $this->pdfText(520, 661, count($rows) . ' record(s)', 8, $accent, true, 'right');

        $widths = $this->pdfColumnWidths(count($headers));
        $y = 605;
        $content .= $this->pdfTableHeader($headers, $widths, 40, $y);
        $y -= 27;

        foreach ($rows as $i => $row) {
            if ($y < 70) {
                $pages[] = $content . $this->pdfFooter(count($pages) + 1);
                $content = $this->pdfHeader('BLOODNEXUS', $title, 'Continuation');
                $y = 760;
                $content .= $this->pdfTableHeader($headers, $widths, 40, $y);
                $y -= 27;
            }
            $content .= $this->pdfTableRow($row, $widths, 40, $y, $i % 2 === 0);
            $y -= 25;
        }

        if (count($rows) === 0) {
            $content .= $this->pdfText(297, $y, 'No records found for this period.', 11, [0.45,0.50,0.57], false, 'center');
        }

        $pages[] = $content . $this->pdfFooter(count($pages) + 1);
        return $this->compilePdf($pages);
    }

    private function pdfHeader(string $brand, string $title, string $subtitle): string
    {
        $c = $this->pdfRect(0, 0, 595, 842, [1,1,1], null, 0);
        $c .= $this->pdfRect(0, 790, 595, 52, [0.08,0.17,0.29], null, 0);
        $c .= $this->pdfText(40, 815, $brand, 19, [1,1,1], true);
        $c .= $this->pdfText(555, 817, 'BLOOD • CONNECT • CARE', 7, [0.82,0.87,0.93], true, 'right');
        $c .= $this->pdfText(40, 750, $title, 23, [0.08,0.17,0.29], true);
        $c .= $this->pdfText(40, 729, $subtitle, 9, [0.42,0.47,0.54]);
        $c .= $this->pdfLine(40, 712, 555, 712, [0.80,0.06,0.12], 1.2);
        return $c;
    }

    private function pdfFooter(int $page): string
    {
        $c = $this->pdfLine(40, 38, 555, 38, [0.88,0.90,0.94], 0.6);
        $c .= $this->pdfText(40, 22, 'BloodNexus • System Generated PDF', 7, [0.50,0.55,0.62]);
        $c .= $this->pdfText(555, 22, 'Page ' . $page, 7, [0.50,0.55,0.62], false, 'right');
        return $c;
    }

    private function pdfCard(float $x, float $y, float $w, float $h, string $label, string $value, array $accent): string
    {
        $c = $this->pdfRect($x, $y, $w, $h, [0.985,0.99,1], [0.88,0.90,0.94], 0.8);
        $c .= $this->pdfRect($x, $y + $h - 5, $w, 5, $accent, null, 0);
        $c .= $this->pdfText($x + 14, $y + $h - 28, $label, 7, [0.45,0.50,0.57], true);
        $c .= $this->pdfText($x + 14, $y + 25, $value, 19, [0.08,0.17,0.29], true);
        return $c;
    }

    private function pdfPill(float $x, float $y, float $w, float $h, string $label, string $value, array $accent): string
    {
        $c = $this->pdfRect($x, $y, $w, $h, [0.99,0.985,0.99], [0.90,0.90,0.94], 0.6);
        $c .= $this->pdfText($x + 10, $y + 20, $label, 10, $accent, true);
        $c .= $this->pdfText($x + $w - 10, $y + 20, $value, 9, [0.35,0.40,0.48], true, 'right');
        return $c;
    }

    private function pdfSectionTitle(string $title, float $y): string
    {
        $c = $this->pdfText(40, $y, $title, 11, [0.08,0.17,0.29], true);
        $c .= $this->pdfLine(40, $y - 8, 555, $y - 8, [0.90,0.91,0.94], 0.6);
        return $c;
    }

    private function pdfKeyValue(float $x, float $y, string $key, string $value): string
    {
        $c = $this->pdfText($x, $y, $key, 8, [0.45,0.50,0.57], false);
        $c .= $this->pdfText($x + 100, $y, $this->pdfClip($value, 32), 9, [0.08,0.17,0.29], true);
        return $c;
    }

    private function pdfColumnWidths(int $count): array
    {
        if ($count === 7) return [62, 92, 48, 90, 110, 48, 65];
        if ($count === 6) return [95, 55, 55, 170, 70, 70];
        if ($count === 5) return [150, 60, 60, 155, 90];
        return array_fill(0, max(1, $count), (int) floor(515 / max(1, $count)));
    }

    private function pdfTableHeader(array $headers, array $widths, float $x, float $y): string
    {
        $c = $this->pdfRect($x, $y - 16, array_sum($widths), 23, [0.08,0.17,0.29], null, 0);
        $cx = $x;
        foreach ($headers as $i => $header) {
            $c .= $this->pdfText($cx + 7, $y - 2, $this->pdfClip($header, 20), 7, [1,1,1], true);
            $cx += $widths[$i];
        }
        return $c;
    }

    private function pdfTableRow(array $values, array $widths, float $x, float $y, bool $shade): string
    {
        $c = $this->pdfRect($x, $y - 17, array_sum($widths), 24, $shade ? [0.985,0.988,0.992] : [1,1,1], [0.92,0.93,0.95], 0.35);
        $cx = $x;
        foreach ($values as $i => $value) {
            $c .= $this->pdfText($cx + 7, $y - 3, $this->pdfClip((string) $value, max(8, (int) floor($widths[$i] / 4.2))), 7.5, [0.17,0.22,0.30]);
            $cx += $widths[$i];
        }
        return $c;
    }

    private function pdfClip(string $value, int $max): string
    {
        $value = preg_replace('/\s+/', ' ', trim($value));
        if (function_exists('mb_substr')) {
            return mb_strlen($value) > $max ? mb_substr($value, 0, max(1, $max - 1)) . '…' : $value;
        }
        return strlen($value) > $max ? substr($value, 0, max(1, $max - 1)) . '...' : $value;
    }

    private function pdfEscape(string $text): string
    {
        $text = preg_replace('/[^\x20-\x7E]/', '', $text);
        return str_replace(['\\', '(', ')'], ['\\\\', '\(', '\)'], $text);
    }

    private function pdfText(float $x, float $y, string $text, float $size = 10, array $rgb = [0,0,0], bool $bold = false, string $align = 'left'): string
    {
        $font = $bold ? 'F2' : 'F1';
        $safe = $this->pdfEscape($text);
        $width = strlen($safe) * $size * 0.50;
        if ($align === 'center') $x -= $width / 2;
        if ($align === 'right') $x -= $width;
        return sprintf(
            "BT /%s %.2F Tf %.3F %.3F %.3F rg %.2F %.2F Td (%s) Tj ET\n",
            $font, $size, $rgb[0], $rgb[1], $rgb[2], $x, $y, $safe
        );
    }

    private function pdfLine(float $x1, float $y1, float $x2, float $y2, array $rgb = [0,0,0], float $width = 1): string
    {
        return sprintf("%.3F %.3F %.3F RG %.2F w %.2F %.2F m %.2F %.2F l S\n", $rgb[0], $rgb[1], $rgb[2], $width, $x1, $y1, $x2, $y2);
    }

    private function pdfRect(float $x, float $y, float $w, float $h, array $fill = [1,1,1], ?array $stroke = null, float $lineWidth = 0): string
    {
        $c = '';
        if ($fill) $c .= sprintf("%.3F %.3F %.3F rg\n", $fill[0], $fill[1], $fill[2]);
        if ($stroke) {
            $c .= sprintf("%.3F %.3F %.3F RG %.2F w\n", $stroke[0], $stroke[1], $stroke[2], $lineWidth ?: 0.8);
            $c .= sprintf("%.2F %.2F %.2F %.2F re B\n", $x, $y, $w, $h);
        } else {
            $c .= sprintf("%.2F %.2F %.2F %.2F re f\n", $x, $y, $w, $h);
        }
        return $c;
    }

    private function compilePdf(array $pages): string
    {
        $objects = [];
        $objects[1] = '<< /Type /Catalog /Pages 2 0 R >>';
        $objects[2] = '<< /Type /Pages /Kids [] /Count 0 >>';

        $fontRegular = 3;
        $fontBold = 4;
        $objects[$fontRegular] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>';
        $objects[$fontBold] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>';

        $kids = [];
        $next = 5;

        foreach ($pages as $stream) {
            $pageObject = $next++;
            $contentObject = $next++;
            $kids[] = $pageObject . ' 0 R';

            $objects[$pageObject] =
                '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] ' .
                '/Resources << /Font << /F1 ' . $fontRegular . ' 0 R /F2 ' . $fontBold . ' 0 R >> >> ' .
                '/Contents ' . $contentObject . ' 0 R >>';

            $objects[$contentObject] =
                '<< /Length ' . strlen($stream) . " >>\nstream\n" . $stream . "\nendstream";
        }

        $objects[2] = '<< /Type /Pages /Kids [' . implode(' ', $kids) . '] /Count ' . count($kids) . ' >>';

        ksort($objects, SORT_NUMERIC);
        $pdf = "%PDF-1.4\n%\xE2\xE3\xCF\xD3\n";
        $offsets = [0];

        foreach ($objects as $number => $object) {
            $offsets[$number] = strlen($pdf);
            $pdf .= $number . " 0 obj\n" . $object . "\nendobj\n";
        }

        $xref = strlen($pdf);
        $max = max(array_keys($objects));
        $pdf .= "xref\n0 " . ($max + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";
        for ($i = 1; $i <= $max; $i++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$i] ?? 0);
        }
        $pdf .= "trailer\n<< /Size " . ($max + 1) . " /Root 1 0 R >>\n";
        $pdf .= "startxref\n" . $xref . "\n%%EOF";
        return $pdf;
    }

    private function buildSimpleTextPdf(array $lines): string
    {
        $title = (string) ($lines[0] ?? 'BloodNexus Report');
        $subtitle = (string) ($lines[1] ?? 'System generated report');

        $pages = [];
        $content = $this->pdfHeader('BLOODNEXUS', $title, $subtitle);

        $y = 675;
        foreach (array_slice($lines, 2) as $line) {
            $line = trim((string) $line);

            if ($line === '') {
                $y -= 12;
                continue;
            }

            if ($y < 65) {
                $pages[] = $content . $this->pdfFooter(count($pages) + 1);
                $content = $this->pdfHeader('BLOODNEXUS', $title, 'Report continuation');
                $y = 675;
            }

            $isHeading =
                str_contains($line, 'SUMMARY') ||
                str_contains($line, 'DEMAND') ||
                str_contains($line, 'HOSPITAL') ||
                str_contains($line, 'AREA') ||
                str_contains($line, 'CITY') ||
                str_contains($line, 'REASON') ||
                str_starts_with($line, 'SELECTED ');

            if ($isHeading) {
                $content .= $this->pdfSectionTitle($line, $y);
                $y -= 25;
                continue;
            }

            if (str_starts_with($line, '---')) {
                $content .= $this->pdfLine(40, $y + 3, 555, $y + 3, [0.90,0.91,0.94], 0.5);
                $y -= 10;
                continue;
            }

            $content .= $this->pdfText(50, $y, $this->pdfClip($line, 100), 8.5, [0.20,0.26,0.34]);
            $y -= 17;
        }

        $pages[] = $content . $this->pdfFooter(count($pages) + 1);
        return $this->compilePdf($pages);
    }

}