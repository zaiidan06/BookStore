<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function create()
    {
        return view('contact');
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        Contact::create([
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        return redirect()->route('contact')->with('success', 'Message sent to admin.');
    }
}
