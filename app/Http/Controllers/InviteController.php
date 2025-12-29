<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\InviteMail;

class InviteController extends Controller
{
    
    public function create()
    {
        return view('users.invite');
    }

    public function send(Request $request)
    {
        $auth = auth()->user();
        $request->validate([
            'email' => 'required|email|unique:users,email',
        ]);

        $token = Str::uuid();

        if ($auth->isSuperAdmin()) {

            $request->validate([
                'company_name' => 'required|string|max:255',
            ]);

            $company = Company::create([
                'name' => $request->company_name,
            ]);

            $role = Role::where('name', 'Admin')->firstOrFail();

            User::create([
                'name'         => 'Invited Admin',
                'email'        => $request->email,
                'password'     => Hash::make(Str::random(20)), 
                'company_id'   => $company->id,
                'role_id'      => $role->id,
                'invite_token' => $token,
                'is_active'    => false,
            ]);
        }
        elseif ($auth->isAdmin()) {

            $request->validate([
                'role' => 'required|exists:roles,name',
            ]);

            $role = Role::where('name', $request->role)->firstOrFail();

            User::create([
                'name'         => 'Invited User',
                'email'        => $request->email,
                'password'     => Hash::make(Str::random(20)),
                'company_id'   => $auth->company_id,
                'role_id'      => $role->id,
                'invite_token' => $token,
                'is_active'    => false,
            ]);
        }
        else {
            abort(403);
        }

        Mail::to($request->email)->send(new InviteMail($token));

        return back()->with('success', 'Invitation sent successfully');
    }

    
     //Show accept invite form
     
    public function acceptForm($token)
    {
        $user = User::where('invite_token', $token)->firstOrFail();
        return view('auth.accept-invite', compact('user'));
    }

     //Accept invitation
    public function accept(Request $request, $token)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::where('invite_token', $token)->firstOrFail();

        $user->update([
            'name'              => $request->name,
            'password'          => Hash::make($request->password), 
            'invite_token'      => null,
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        auth()->login($user);

        return redirect('/dashboard');
    }
}
