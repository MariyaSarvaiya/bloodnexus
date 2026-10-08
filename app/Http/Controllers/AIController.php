<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AIController extends Controller
{
    /**
     * Show AI Assistant page
     */
    public function index()
    {
        return view('ai-assistant');
    }

    /**
     * Handle AI Assistant question
     */
    public function ask(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $message = strtolower(trim($request->message));

        /*
        |--------------------------------------------------------------------------
        | Simple AI Blood Assistant
        |--------------------------------------------------------------------------
        | फिलहाल demo/local AI response.
        | बाद में इसे actual AI API से connect कर सकते हैं.
        |--------------------------------------------------------------------------
        */

        if (
            str_contains($message, 'blood group') ||
            str_contains($message, 'blood type')
        ) {
            $reply = "BloodNexus में आप A+, A-, B+, B-, AB+, AB-, O+ और O- blood groups के donors खोज सकते हैं.";
        }

        elseif (
            str_contains($message, 'donor') ||
            str_contains($message, 'find blood')
        ) {
            $reply = "Blood donor खोजने के लिए Blood Availability page पर जाएं और blood group तथा city select करके search करें.";
        }

        elseif (
            str_contains($message, 'request') ||
            str_contains($message, 'blood चाहिए') ||
            str_contains($message, 'blood chahiye')
        ) {
            $reply = "Blood request बनाने के लिए Dashboard से 'Request Blood' option चुनें और required blood group, city और emergency level की जानकारी भरें.";
        }

        elseif (
            str_contains($message, 'emergency') ||
            str_contains($message, 'critical') ||
            str_contains($message, 'urgent')
        ) {
            $reply = "Emergency blood requirement के लिए Critical level select करें. इससे request को priority के साथ handle किया जा सकता है.";
        }

        elseif (
            str_contains($message, 'donate') ||
            str_contains($message, 'donation')
        ) {
            $reply = "अगर आप blood donate करना चाहते हैं तो Donor Registration करके अपना blood group, city और contact information provide करें.";
        }

        elseif (
            str_contains($message, 'hello') ||
            str_contains($message, 'hi') ||
            str_contains($message, 'hey')
        ) {
            $reply = "Hello! 👋 मैं AI Blood Assistant हूँ. मैं blood search, blood requests, donors और system features के बारे में आपकी मदद कर सकता हूँ.";
        }

        else {
            $reply = "मैं BloodNexus के blood donors, blood requests, emergency requests और donation process से संबंधित general information में आपकी मदद कर सकता हूँ. कृपया अपना सवाल थोड़ा specific पूछें.";
        }

        return response()->json([
            'success' => true,
            'reply' => $reply,
        ]);
    }
}