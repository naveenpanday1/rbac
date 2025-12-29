@extends('layouts.app')
@section('page-title', 'Edit User')
@section('content')

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
    <form method="POST" action="{{ route('users.update', $user->id) }}">
        @csrf
        <div class="mb-4">
            <label class="block mb-1">Name</label>
            <input type="text" name="name"
                   value="{{ $user->name }}"
                   class="border p-2 rounded w-full">
        </div>
        <div class="mb-4">
            <label class="block mb-1">Email</label>
            <input type="email" name="email"
                   value="{{ $user->email }}"
                   class="border p-2 rounded w-full">
                 </div>
        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Update User
        </button>
        <a href="{{ route('users.index') }}"
           class="ml-4 text-gray-600">Cancel</a>
    </form>
</div>

@endsection
