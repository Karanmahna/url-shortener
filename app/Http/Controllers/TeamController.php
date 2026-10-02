<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function index()
    {
        $admin = Auth::user();

        $teamMembers = User::where('company_id', $admin->company_id)
            ->where('id', '!=', $admin->id)
            ->latest()
            ->paginate(10);

        return view('admin.team.index', compact('teamMembers'));
    }
}