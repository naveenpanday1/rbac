@extends('layouts.app')
@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h3 class="font-semibold text-lg mb-4">Activate Your Account</h3>
    <form method="POST">
        @csrf
        <input type="text"
               name="name"
               placeholder="Your Name"
               class="border p-2 w-full mb-3"
               required>
        <input type="password"
               name="password"
               placeholder="Password"
               class="border p-2 w-full mb-3"
               required>
        <input type="password"
               name="password_confirmation"
               placeholder="Confirm Password"
               class="border p-2 w-full mb-3"
               required>
        <button class="bg-blue-600 text-white px-4 py-2 rounded w-full">
            Activate Account
        </button>
    </form>
</div>
@endsection
