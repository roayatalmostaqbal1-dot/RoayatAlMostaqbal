<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\ContactMail;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|min:3',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'service' => 'nullable|string|max:255',
            'preferred_contact' => 'nullable|string|in:email,phone,whatsapp',
            'department' => 'nullable|string|in:commercial,residential,maintenance',
            'message' => 'required|string|max:1000',
            // Government compliance: Privacy consent is required
            'privacy_consent' => 'required|accepted',
        ], [
            'privacy_consent.required' => __('messages.contact.form.privacy_consent_required'),
            'privacy_consent.accepted' => __('messages.contact.form.privacy_consent_required'),
        ]);

        $data = $request->only('name', 'email', 'phone', 'company', 'service', 'message', 'preferred_contact', 'department');

        // Add privacy consent timestamp for compliance audit trail
        $data['privacy_consent_at'] = now();
        $data['ip_address'] = $request->ip();

        // Store contact in database
        Contact::create($data);

        // Send email notification
        Mail::to(config('mail.from.address'))->send(new ContactMail($data));

        return redirect()->route('contact', ['locale' => app()->getLocale()])
            ->with('success', __('messages.contact.form.success'));
    }
}


