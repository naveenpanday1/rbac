@extends('layouts.app')
@section('page-title','Users')

@section('content')

@if(auth()->user()->isSuperAdmin())

<div class="bg-yellow-100 p-6 rounded shadow">
    <h3 class="font-semibold mb-4">Admins & Their Companies</h3> 
    <a href="{{ route('invite.form') }}"
   class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 inline-block">
     Invitation
</a>

    <table class="w-full border">
        <thead>
            <tr class="bg-yellow-200 text-sm">
                <th class="p-2 border">Company</th>
                <th class="p-2 border text-center">Users</th>
                <th class="p-2 border text-center">Total URLs</th>
                <th class="p-2 border text-center">Total Hits</th>
            </tr>
        </thead>

        <tbody>
        @forelse($admins as $admin)
            <tr class="bg-white text-sm">
                <td class="p-2 border">
                    <strong>{{ $admin->company->name ?? '-' }}</strong><br>
                    <span class="text-gray-500">{{ $admin->email }}</span>
                </td>

                <td class="p-2 border text-center">
                    {{ $admin->total_users }}
                </td>

                <td class="p-2 border text-center">
                    {{ $admin->total_urls }}
                </td>

                <td class="p-2 border text-center font-semibold">
                    {{ $admin->total_hits }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="p-4 text-center text-gray-500">
                    No Admins Found
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="flex justify-between items-center mt-4 text-sm text-gray-600">
        <div>
            Showing {{ $admins->firstItem() }} to {{ $admins->lastItem() }}
            of {{ $admins->total() }}
        </div>
        <div>
            {{ $admins->links() }}
        </div>
    </div>

</div>

@elseif(auth()->user()->isAdmin())

<div class="bg-blue-100 p-6 rounded shadow">

    <h3 class="font-semibold mb-4">Team Members</h3>

    <table class="w-full border">
        <thead>
            <tr class="bg-blue-200 text-sm">
                <th class="p-2 border">Name</th>
                <th class="p-2 border">Email</th>
                <th class="p-2 border">Role</th>
            </tr>
        </thead>

        <tbody>
        @forelse($users as $user)
            <tr class="bg-white text-sm">
                <td class="p-2 border">{{ $user->name }}</td>
                <td class="p-2 border">{{ $user->email }}</td>
                <td class="p-2 border">{{ $user->role->name }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="p-4 text-center text-gray-500">
                    No Users Found
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="flex justify-between items-center mt-4 text-sm text-gray-600">
        <div>
            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }}
            of {{ $users->total() }}
        </div>
        <div>
            {{ $users->links() }}
        </div>
    </div>


   </div>
@endif
@endsection
