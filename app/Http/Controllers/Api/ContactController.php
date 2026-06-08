<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Store contact form submission
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'message' => 'required|string|min:10|max:2000',
        ]);

        try {
            // Store the message in database if you have a Contact model
            // Contact::create($validated);

            // Optionally send an email notification
            // Mail::to(config('mail.from.address'))->send(new ContactFormSubmitted($validated));

            return response()->json([
                'success' => true,
                'message' => 'Your message has been sent successfully. We will reply within 24 hours.',
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Contact form error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to send message. Please try again later.',
            ], 500);
        }
    }
}
