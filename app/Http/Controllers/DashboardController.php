<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;

class DashboardController extends Controller
{
    public function member()
    {
        $shortUrls = ShortUrl::where('company_id', auth()->user()->company_id)
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('member.dashboard', compact('shortUrls'));
    }
}