<?php

namespace App\Http\Controllers\Web;

use App\Helpers\PlatformHelper;
use App\Mail\PlatformSupportMail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SupportController extends Controller
{
    public function contact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10|max:5000',
        ]);

        try {
            // Send to platform support email
            $supportEmail = PlatformHelper::supportEmail();
            Mail::to($supportEmail)->send(
                new PlatformSupportMail(
                    subject: "Contact: {$validated['subject']} (de {$validated['name']})",
                    body: "Email: {$validated['email']}\n\n{$validated['message']}",
                    recipientEmail: $supportEmail,
                )
            );

            return back()->with('success', 'Message envoyé avec succès. Notre équipe vous contactera bientôt.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'envoi du message.')->withInput();
        }
    }

    public function subscribeNewsletter(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // TODO: Subscribe to newsletter table
        return back()->with('success', 'Inscrit à la newsletter !');
    }

    public function faqs()
    {
        return view('support.faqs');
    }
}
