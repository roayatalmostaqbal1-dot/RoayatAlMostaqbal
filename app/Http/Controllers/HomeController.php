<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function newsletter(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        // Here you would typically save to database or send to email service
        // For now, we'll just return a success response

        return response()->json([
            'success' => true,
            'message' => __('messages.newsletter.success', [], app()->getLocale())
        ]);
    }
}


