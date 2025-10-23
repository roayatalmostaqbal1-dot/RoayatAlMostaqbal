<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactMail;
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
            'message' => 'required|string|max:1000'
        ]);


        $data = $request->only('name', 'email', "phone", 'company', 'service','subject', 'message');

        Mail::to('Roayatalmostaqbal1@gmail.com')->send(new ContactMail($data));


        return back()->with('success', 'تم إرسال رسالتك بنجاح!');

    }
}


