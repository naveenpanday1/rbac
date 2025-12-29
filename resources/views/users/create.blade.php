@extends('layouts.app')

@section('page-title', 'Create User')

@section('content')

{{-- ERROR MESSAGE --}}
@if($errors->any())
    <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white p-6 rounded shadow max-w-xl">

    <form method="POST" action="{{ route('users.store') }}">
        @csrf

        <div class="mb-4">
            <label class="block mb-1">Name</label>
            <input type="text" name="name" class="border p-2 rounded w-full" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Email</label>
            <input type="email" name="email" class="border p-2 rounded w-full" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Role</label>
            <select name="role" class="border p-2 rounded w-full" required>
                <option value="">Select Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Only SuperAdmin --}}
        @if(auth()->user()->isSuperAdmin())
            <div class="mb-4">
                <label class="block mb-1">Company Name</label>
                <input type="text" name="company_name" class="border p-2 rounded w-full">
            </div>
        @endif

        <div class="flex gap-4">
            <button class="bg-blue-600 text-white px-4 py-2 rounded">
                Create User
            </button>

            <a href="{{ route('users.index') }}" class="text-gray-600">
                Cancel
            </a>
        </div>

    </form>

</div>

@endsection
