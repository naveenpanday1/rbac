<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\Role;
use App\Models\ShortUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    
  public function index()
{
    $authUser = auth()->user();
    if ($authUser->isSuperAdmin()) {
        $admins = User::with(['company', 'role'])
            ->whereHas('role', function ($q) {
                $q->where('name', 'Admin');
            })
            ->paginate(10);
       
            $admins->getCollection()->transform(function ($admin) {
            $admin->total_users = User::where('company_id', $admin->company_id)->count();
            $userIds = User::where('company_id', $admin->company_id)->pluck('id');
            $admin->total_urls = ShortUrl::whereIn('created_by', $userIds)->count();
            $admin->total_hits = ShortUrl::whereIn('created_by', $userIds)->sum('hits');
            return $admin;
        });

        return view('users.index', compact('admins'));
    }

    if ($authUser->isAdmin()) {

        $users = User::with('role')
            ->where('company_id', $authUser->company_id)
            ->paginate(10);

        return view('users.index', compact('users'));
    }

    abort(403);
}


    public function create()
    {
        return view('users.create', [
            'roles' => Role::all()
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'role'         => 'required|exists:roles,name',
            'company_name' => 'nullable|string|max:255',
        ]);

        $authUser = auth()->user();
        $role     = Role::where('name', $request->role)->first();
        $password = 'Demo@123';

        if ($authUser->isSuperAdmin()) {

            if ($role->name !== 'Admin') {
                return back()->withErrors([
                    'role' => 'SuperAdmin can create only Admin'
                ]);
            }

            if (!$request->company_name) {
                return back()->withErrors([
                    'company_name' => 'Company name is required'
                ]);
            }

            $company = Company::create([
                'name' => $request->company_name
            ]);

            User::create([
                'name'       => $request->name,
                'email'      => $request->email,
                'password'   => Hash::make($password),
                'company_id' => $company->id,
                'role_id'    => $role->id
            ]);

            return redirect()->back()
                ->with('success', 'Admin created successfully');
        }

         if ($authUser->isAdmin()) {

            if ($role->name === 'SuperAdmin') {
                return back()->withErrors([
                    'role' => 'Admin cannot create SuperAdmin'
                ]);
            }

            User::create([
                'name'       => $request->name,
                'email'      => $request->email,
                'password'   => Hash::make($password),
                'company_id' => $authUser->company_id,
                'role_id'    => $role->id
            ]);

            return redirect()->back()
                ->with('success', 'User created successfully');
        }

        abort(403, 'You are not allowed to create users');
    }
}
