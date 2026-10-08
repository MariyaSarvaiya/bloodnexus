<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\Donor;
use App\Models\BloodRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | GET CURRENT AUTHENTICATED USER
    |--------------------------------------------------------------------------
    */

    private function currentUser(): User
    {
        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403, 'Authentication required.');
        }

        return $user;
    }


    /*
    |--------------------------------------------------------------------------
    | FIND SHARED BLOOD REQUEST
    |--------------------------------------------------------------------------
    |
    | User <-> Donor communication is allowed only if:
    |
    | User created a blood request
    | AND
    | Donor accepted that request.
    |
    */

    private function sharedBloodRequest(
        int $currentUserId,
        int $otherUserId
    ): ?BloodRequest {

        $currentUser = $this->currentUser();

        $otherUser = User::find($otherUserId);

        if (!$otherUser) {
            return null;
        }


        /*
        |--------------------------------------------------------------------------
        | USER -> DONOR
        |--------------------------------------------------------------------------
        */

        if (
            $currentUser->role === 'user' &&
            $otherUser->role === 'donor'
        ) {

            $donor = Donor::where(
                'user_id',
                $otherUser->id
            )->first();

            if (!$donor) {
                return null;
            }

            return BloodRequest::where(
                'user_id',
                $currentUserId
            )
            ->where(
                'donor_id',
                $donor->id
            )
            ->where('status', 'accepted')
            ->latest()
            ->first();
        }


        /*
        |--------------------------------------------------------------------------
        | DONOR -> USER
        |--------------------------------------------------------------------------
        */

        if (
            $currentUser->role === 'donor' &&
            $otherUser->role === 'user'
        ) {

            $donor = Donor::where(
                'user_id',
                $currentUserId
            )->first();

            if (!$donor) {
                return null;
            }

            return BloodRequest::where(
                'user_id',
                $otherUser->id
            )
            ->where(
                'donor_id',
                $donor->id
            )
            ->where('status', 'accepted')
            ->latest()
            ->first();
        }


        return null;
    }


    /*
    |--------------------------------------------------------------------------
    | MESSAGE LIST
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $user = $this->currentUser();

        $userId = $user->id;


        // Show only messages belonging to an ACTIVE accepted donation connection.
        // Completed donations are intentionally archived and no longer appear in chat.
        $messages = Message::with([
            'sender',
            'receiver',
            'bloodRequest',
        ])
        ->where(function ($query) use ($userId) {
            $query->where('sender_id', $userId)
                ->orWhere('receiver_id', $userId);
        })
        ->whereHas('bloodRequest', function ($query) {
            $query->where('status', 'accepted');
        })
        ->latest()
        ->get();


        /*
        |--------------------------------------------------------------------------
        | UNREAD COUNT
        |--------------------------------------------------------------------------
        */

        $unreadCount = Message::where(
            'receiver_id',
            $userId
        )
        ->where(
            'is_read',
            false
        )
        ->count();


        return view(
            'messages',
            compact(
                'messages',
                'unreadCount'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | OPEN CONVERSATION
    |--------------------------------------------------------------------------
    */

    public function conversation(User $user)
    {
        $currentUser = $this->currentUser();


        /*
        |--------------------------------------------------------------------------
        | SELF MESSAGE PROTECTION
        |--------------------------------------------------------------------------
        */

        if (
            (int) $currentUser->id ===
            (int) $user->id
        ) {

            return redirect()
                ->route('messages.index')
                ->with(
                    'error',
                    'You cannot message yourself.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | ONLY USER <-> DONOR
        |--------------------------------------------------------------------------
        */

        if (
            !in_array($currentUser->role, ['user', 'donor'], true)
            ||
            !in_array($user->role, ['user', 'donor'], true)
            ||
            $currentUser->role === $user->role
        ) {

            abort(
                403,
                'Messages are available only between blood users and donors.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FIND CONNECTED BLOOD REQUEST
        |--------------------------------------------------------------------------
        */

        $bloodRequest = $this->sharedBloodRequest(
            $currentUser->id,
            $user->id
        );


        /*
        |--------------------------------------------------------------------------
        | CHAT IS ACTIVE ONLY WHILE DONATION IS ACCEPTED
        |--------------------------------------------------------------------------
        */

        if (!$bloodRequest) {
            abort(403, 'This donation connection is closed because the request is no longer active.');
        }


        /*
        |--------------------------------------------------------------------------
        | LOAD CONVERSATION
        |--------------------------------------------------------------------------
        */

        $messages = Message::with([
            'sender',
            'receiver',
            'bloodRequest',
        ])
        ->where(
            function ($query) use ($currentUser, $user) {

                $query
                    ->where('sender_id', $currentUser->id)
                    ->where('receiver_id', $user->id);

            }
        )
        ->orWhere(
            function ($query) use ($currentUser, $user) {

                $query
                    ->where('sender_id', $user->id)
                    ->where('receiver_id', $currentUser->id);

            }
        )
        ->oldest()
        ->get();


        /*
        |--------------------------------------------------------------------------
        | MARK MESSAGES AS READ
        |--------------------------------------------------------------------------
        */

        Message::where(
            'sender_id',
            $user->id
        )
        ->where(
            'receiver_id',
            $currentUser->id
        )
        ->where(
            'is_read',
            false
        )
        ->update([
            'is_read' => true,
        ]);


        return view(
            'conversation',
            [
                'messages' => $messages,
                'otherUser' => $user,
                'bloodRequest' => $bloodRequest,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SEND MESSAGE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $currentUser = $this->currentUser();


        /*
        |--------------------------------------------------------------------------
        | VALIDATE
        |--------------------------------------------------------------------------
        */

        $data = $request->validate([

            'receiver_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],

            'message' => [
                'required',
                'string',
                'max:2000',
            ],

            'blood_request_id' => [
                'nullable',
                'integer',
                'exists:blood_requests,id',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | SELF MESSAGE PROTECTION
        |--------------------------------------------------------------------------
        */

        if (
            (int) $data['receiver_id'] ===
            (int) $currentUser->id
        ) {

            return back()
                ->withErrors([
                    'message' =>
                        'You cannot send a message to yourself.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | FIND RECEIVER
        |--------------------------------------------------------------------------
        */

        $receiver = User::findOrFail(
            $data['receiver_id']
        );


        /*
        |--------------------------------------------------------------------------
        | ONLY USER <-> DONOR
        |--------------------------------------------------------------------------
        */

        if (
            !in_array($currentUser->role, ['user', 'donor'], true)
            ||
            !in_array($receiver->role, ['user', 'donor'], true)
            ||
            $currentUser->role === $receiver->role
        ) {

            abort(
                403,
                'Messages are available only between users and donors.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CHECK BLOOD CONNECTION
        |--------------------------------------------------------------------------
        */

        $bloodRequest = $this->sharedBloodRequest(
            $currentUser->id,
            $receiver->id
        );


        if (!$bloodRequest) {

            abort(
                403,
                'Messaging is available after a donor accepts the blood request.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | BLOOD REQUEST ID
        |--------------------------------------------------------------------------
        */

        $bloodRequestId =
            $data['blood_request_id']
            ?? $bloodRequest->id;


        /*
        |--------------------------------------------------------------------------
        | SECURITY CHECK
        |--------------------------------------------------------------------------
        */

        $validRequest = false;


        /*
        |--------------------------------------------------------------------------
        | USER SENDING TO DONOR
        |--------------------------------------------------------------------------
        */

        if ($currentUser->role === 'user') {

            $donor = Donor::where(
                'user_id',
                $receiver->id
            )->first();

            if ($donor) {

                $validRequest = BloodRequest::where(
                    'id',
                    $bloodRequestId
                )
                ->where(
                    'user_id',
                    $currentUser->id
                )
                ->where(
                    'donor_id',
                    $donor->id
                )
                ->where('status', 'accepted')
                ->exists();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | DONOR SENDING TO USER
        |--------------------------------------------------------------------------
        */

        if ($currentUser->role === 'donor') {

            $donor = Donor::where(
                'user_id',
                $currentUser->id
            )->first();

            if ($donor) {

                $validRequest = BloodRequest::where(
                    'id',
                    $bloodRequestId
                )
                ->where(
                    'user_id',
                    $receiver->id
                )
                ->where(
                    'donor_id',
                    $donor->id
                )
                ->where('status', 'accepted')
                ->exists();
            }
        }


        if (!$validRequest) {

            abort(
                403,
                'Invalid blood request communication.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | SAVE MESSAGE
        |--------------------------------------------------------------------------
        */

        Message::create([

            'sender_id' =>
                $currentUser->id,

            'receiver_id' =>
                $receiver->id,

            'blood_request_id' =>
                $bloodRequestId,

            'message' =>
                trim($data['message']),

            'is_read' =>
                false,

        ]);


        /*
        |--------------------------------------------------------------------------
        | RETURN TO CHAT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'messages.conversation',
                $receiver->id
            )
            ->with(
                'success',
                'Message sent ❤️'
            );
    }
}