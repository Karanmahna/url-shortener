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

    public function inviteClient(Request $request)
    {
        abort_unless(
            auth()->user()->role === User::ROLE_SUPER_ADMIN,
            403
        );

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        if (User::where('email', $validated['email'])->exists()) {
            return back()
                ->withErrors(['email' => 'This email already has an account.'])
                ->withInput();
        }

        $invitation = DB::transaction(function () use ($validated) {
            $company = Company::create([
                'name' => $validated['company_name'],
            ]);

            return Invitation::create([
                'company_id' => $company->id,
                'invited_by' => auth()->id(),
                'email' => $validated['email'],
                'role' => User::ROLE_ADMIN,
                'token' => Str::random(64),
                'expires_at' => now()->addDays(7),
            ]);
        });

        $invitationUrl = route('invitations.accept', [
            'token' => $invitation->token,
        ]);

        return redirect()
            ->route('superadmin.dashboard')
            ->with('invitation_url', $invitationUrl)
            ->with('success', 'Client invitation created successfully.');
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

        $user = User::create([
            'name' => $request->name,
            'email' => $invitation->email,
            'password' => Hash::make($request->password),
            'company_id' => $invitation->company_id,
            'role' => $invitation->role,
        ]);

        $invitation->update([
            'accepted_at' => now(),
        ]);

        return redirect()->route('login')
            ->with('success', 'Registration successful. Please log in.');
    }
    public function createTeam()
    {
        return view('admin.invite-team');
    }

    public function inviteTeam(Request $request)
    {
        $admin = auth()->user();

        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'role' => 'required|in:admin,member',
        ]);

        if (User::where('email', $validated['email'])->exists()) {
            return back()
                ->withErrors(['email' => 'This email already has an account.'])
                ->withInput();
        }

        $pendingInvitation = Invitation::where('email', $validated['email'])
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->exists();

        if ($pendingInvitation) {
            return back()
                ->withErrors(['email' => 'An active invitation already exists for this email.'])
                ->withInput();
        }

        $invitation = Invitation::create([
            'company_id' => $admin->company_id,
            'invited_by' => $admin->id,
            'email' => $validated['email'],
            'role' => $validated['role'],
            'token' => Str::random(64),
            'expires_at' => now()->addDays(7),
        ]);

        $invitationUrl = route('invitations.accept', [
            'token' => $invitation->token,
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Team invitation created successfully.')
            ->with('invitation_url', $invitationUrl);
    }
}