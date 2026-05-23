<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $key = 'contact:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $minutes = ceil(RateLimiter::availableIn($key) / 60);
            return response()->json([
                'success' => false,
                'message' => "Too many messages. Please try again in {$minutes} minute(s).",
            ], 429);
        }

        $data = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:255',
            'subject' => 'required|string|max:100',
            'message' => 'required|string|max:1000',
        ]);

        ContactSubmission::create([
            ...$data,
            'ip_address' => $request->ip(),
        ]);

        RateLimiter::hit($key, 3600); // 3 per hour per IP

        return response()->json(['success' => true]);
    }
}
