<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function index()
    {
        $admin = Auth::user();
        // Fetch all members of the admin's company with their short URL counts and hits
        $items = User::where('company_id', $admin->company_id)
            ->where('id', '!=', $admin->id)
            ->withSum('shortUrls', 'hits')
            ->withCount('shortUrls')
            ->get();

        return view('members', [
            'items' => $items,
            'type' => 'team',
            'title' => 'Team Members',
        ]);
    }

    public function show()
    {
        $admin = Auth::user();
        $items = ShortUrl::with(['company', 'user']) // Fetch short URLs with their linked company and user
            ->where('company_id', $admin->company_id)
            ->get();
        $title = 'All Generated URLs';
        return view('members', [
            'items' => $items,
            'type' => 'team_url',
            'title' => $title,
        ]);
    }
}