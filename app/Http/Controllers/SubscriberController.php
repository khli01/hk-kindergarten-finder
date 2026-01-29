<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            "email" => "required|email|unique:subscribers,email",
        ]);

        $subscriber = Subscriber::create([
            "email" => $request->email,
            "preferred_language" => app()->getLocale(),
        ]);

        return redirect()->back()->with("success", __("Thank you for subscribing! We'll keep you updated."));
    }
}
