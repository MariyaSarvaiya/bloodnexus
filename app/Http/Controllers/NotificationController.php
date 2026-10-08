<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | NOTIFICATIONS PAGE
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        abort_unless(Auth::check(), 403);

        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('notifications', compact('notifications'));
    }


    /*
    |--------------------------------------------------------------------------
    | MARK SINGLE NOTIFICATION AS READ
    |--------------------------------------------------------------------------
    */

    public function read(Notification $notification)
    {
        abort_unless(Auth::check(), 403);

        // User can only modify their own notification
        if ((int) $notification->user_id !== (int) Auth::id()) {
            abort(403);
        }

        $notification->update([
            'is_read' => true,
        ]);

        return back()->with(
            'success',
            'Notification marked as read.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | MARK ALL NOTIFICATIONS AS READ
    |--------------------------------------------------------------------------
    */

    public function markAllRead()
    {
        abort_unless(Auth::check(), 403);

        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
            ]);

        return back()->with(
            'success',
            'All notifications marked as read. ✅'
        );
    }
}