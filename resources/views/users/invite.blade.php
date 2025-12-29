@extends('layouts.app')
@section('page-title','Send Invitation')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">

    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-6">

        <!-- Header -->
        <div class="mb-6 text-center">
            <h3 class="text-2xl font-semibold text-gray-800">
                Send Invitation
            </h3>
            <p class="text-sm text-gray-500 mt-1">
                Invite a user to join your workspace
            </p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 p-3 rounded-md mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('invite.send') }}" class="space-y-4">
            @csrf

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Email Address
                </label>
                <input type="email" name="email" required
                       placeholder="user@example.com"
                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition">
            </div>

            <!-- SuperAdmin: Company -->
            @if(auth()->user()->isSuperAdmin())
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Company Name
                    </label>
                    <input type="text" name="company_name" required
                           placeholder="Company Pvt Ltd"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition">
                </div>
            @endif

            <!-- Admin: Role -->
            @if(auth()->user()->isAdmin())
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Assign Role
                    </label>
                    <select name="role"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition">
                        <option value="Member">Member</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>
            @endif

            <!-- Submit Button -->
            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition shadow-md">
                Send Invitation
            </button>
        </form>

    </div>

</div>

@endsection
