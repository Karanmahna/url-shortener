<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $admin = Auth::user();

        $teamMembers = User::where('company_id', $admin->company_id)
            ->where('id', '!=', $admin->id)
            ->latest()
            ->take(5)
            ->get();

        $shortUrls = ShortUrl::where('company_id', $admin->company_id)
            ->with('user')
            ->latest()
            ->paginate(10);
        
        $totalUrlHits = (clone $shortUrls)->sum('hits');

        $totalUrls = ShortUrl::where('company_id', $admin->company_id)->count();

        $totalMembers = User::where('company_id', $admin->company_id)
            ->where('role', User::ROLE_MEMBER)
            ->count();

        return view('admin.dashboard', compact(
            'teamMembers',
            'shortUrls',
            'totalUrls',
            'totalMembers',
            'totalUrlHits'
        ));
    }
}