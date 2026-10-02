<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\View\View;

class SuperAdminDashboardController extends Controller
{
    public function index(): View
    {
        $companies = Company::whereHas('users', function ($query) {
            $query->where('role', User::ROLE_ADMIN);
        })
        ->with(['users' => function ($query) {
            $query->where('role', User::ROLE_ADMIN);
        }])
        ->withCount('users', 'shortUrls')
        ->withSum('shortUrls', 'hits')
        ->whereHas('users', function ($query) {
            $query->where('role', 'admin');
        })
        ->latest()
        ->paginate(10);
        $totalClients = Company::count();
        $totalAdmins = User::where('role', User::ROLE_ADMIN)->count();
        $totalUrls = ShortUrl::count();
        $shortUrls = ShortUrl::with('company')
        ->latest()
        ->paginate(10);
        $totalUrlHits = ShortUrl::sum('hits');

        return view('superadmin.dashboard', compact(
            'companies',
            'totalClients',
            'totalAdmins',
            'totalUrls',
            'shortUrls',
            'totalUrlHits'
        ));
    }
}