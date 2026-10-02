<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShortUrlController extends Controller
{
    public function create()
    {
        return view('create-urls');
    }

    public function store(Request $request)
    {
        $request->validate([
            'original_url' => 'required|url',
        ]);

        do {
            $shortCode = Str::random(8);
        } while (ShortUrl::where('short_code', $shortCode)->exists());

        $shortUrl = ShortUrl::create([
            'company_id' => auth()->user()->company_id,
            'user_id' => auth()->id(),
            'original_url' => $request->original_url,
            'short_code' => $shortCode,
            'hits' => 0,
        ]);

        $shortLink = route('urls.redirect', $shortUrl->short_code);
        return redirect()
        ->route(auth()->user()->dashboardRoute())
        ->with('success', 'URL generated successfully!')
        ->with('short_link', $shortLink);
    }

    public function redirect($shortCode)
    {
        $shortUrl = ShortUrl::where('short_code', $shortCode)->firstOrFail();
        $shortUrl->increment('hits');
        return redirect()->away($shortUrl->original_url);
    }
}