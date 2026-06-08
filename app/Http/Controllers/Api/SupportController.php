<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Faq;
use App\Models\Newsletter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function faqs(Request $request): JsonResponse
    {
        $query = Faq::where('is_active', true);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('question', 'like', '%' . $request->search . '%')
                  ->orWhere('answer', 'like', '%' . $request->search . '%');
            });
        }

        $faqs = $query->orderBy('order')->get();

        return ApiResponse::success($faqs);
    }

    public function contact(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|in:support,partnership,press,other',
            'message' => 'required|string|max:5000',
        ]);

        $contact = Contact::create($data);

        return ApiResponse::success($contact, 'Message envoyé', 201);
    }

    public function subscribeNewsletter(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => 'required|email|unique:newsletters,email',
        ]);

        $newsletter = Newsletter::create([
            'email' => $data['email'],
            'subscribed_at' => now(),
        ]);

        return ApiResponse::success($newsletter, 'Inscription confirmée', 201);
    }
}
