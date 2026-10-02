<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    public function createClient()
    {
        return view('superadmin.invite-client');
    }

    public function sendInvitation(Request $request)
    {
        $inviter = auth()->user();
        abort_unless( // Ensure only super admins and admins can send invitations
            in_array($inviter->role, [
                User::ROLE_SUPER_ADMIN,
                User::ROLE_ADMIN,
            ]),
            403
        );
        $isSuperAdmin = $inviter->role === User::ROLE_SUPER_ADMIN;
        // Validations based on who is sending the invitation
        $validated = $request->validate([
            'company_name' => $isSuperAdmin
                ? 'required|string|max:255'
                : 'nullable|string|max:255',

            'email' => 'required|email|max:255',

            'role' => $isSuperAdmin
                ? 'nullable'
                : 'required|in:admin,member',
        ]);

        // Check if existing user
        if (User::where('email', $validated['email'])->exists()) {
            return back()
                ->withErrors([
                    'email' => 'This email already has an account.',
                ])
                ->withInput();
        }

        // Prevent duplicate rec
        $pendingInvitation = Invitation::where('email', $validated['email'])
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->exists();

        if ($pendingInvitation) {
            return back()
                ->withErrors([
                    'email' => 'An active invitation already exists for this email.',
                ])
                ->withInput();
        }

        $invitation = DB::transaction(function () use (
            $validated,
            $inviter,
            $isSuperAdmin
        ) {
            $companyId = $isSuperAdmin
                ? Company::create([
                    'name' => $validated['company_name'],
                ])->id
                : $inviter->company_id;

            // Check invited role
            $role = $isSuperAdmin
                ? User::ROLE_ADMIN
                : $validated['role'];

            return Invitation::create([ // Store the invitation in the database
                'company_id' => $companyId,
                'invited_by' => $inviter->id,
                'email' => $validated['email'],
                'role' => $role,
                'token' => Str::random(64),
                'expires_at' => now()->addDays(7),
            ]);
        });

        $invitationUrl = route('invitations.accept', [
            'token' => $invitation->token,
        ]);

        // Redirect according to inviter role
        $dashboardRoute = $isSuperAdmin
            ? 'superadmin.dashboard'
            : 'admin.dashboard';

        $successMessage = $isSuperAdmin
            ? 'Client invitation created successfully.'
            : 'Team invitation created successfully.';

        return redirect()
            ->route($dashboardRoute)
            ->with('success', $successMessage)
            ->with('invitation_url', $invitationUrl);
    }

    public function showAccept(string $token)
    {
        $invitation = Invitation::where('token', $token)
            ->whereNull('accepted_at')
            ->firstOrFail();

        if ($invitation->expires_at && $invitation->expires_at->isPast()) {
            abort(410, 'This invitation has expired.');
        }

        return view('invitations.accept', compact('invitation'));
    }

    public function accept(Request $request, $token)
    {
        // Validate invitation token and ensure it hasn't been accepted
        $invitation = Invitation::where('token', $token)
            ->whereNull('accepted_at')
            ->firstOrFail();

        if ($invitation->expires_at->isPast()) {
            return back()->withErrors([
                'email' => 'Invitation has expired.',
            ]);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([ // Create the new user
            'name' => $request->name,
            'email' => $invitation->email,
            'password' => Hash::make($request->password),
            'company_id' => $invitation->company_id,
            'role' => $invitation->role,
        ]);

        $invitation->update([
            'accepted_at' => now(),
        ]);
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()
            ->route('login')
            ->with('success', 'Registration successful. Please log in.');
    }
    
    public function createTeam()
    {
        return view('admin.invite-team');
    }
}