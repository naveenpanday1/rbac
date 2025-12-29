@extends('layouts.app')

@section('page-title', 'Create Short URL')

@section('content')

{{-- ❌ SuperAdmin Block --}}
@if(auth()->user()->isSuperAdmin())
    <div class="p-4 bg-red-100 text-red-700 rounded">
        SuperAdmin is not allowed to create short URLs.
    </div>
@else

<div class="max-w-xl bg-white p-6 rounded shadow">

    {{-- Errors --}}
    @if($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('urls.store') }}">
        @csrf

        <div class="mb-4">
            <label class="block mb-1 font-medium">Original URL</label>
            <input
                type="url"
                name="original_url"
                class="border p-2 rounded w-full"
                placeholder="https://example.com"
                required
            >
        </div>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Generate Short URL
        </button>

        <a href="{{ route('urls.index') }}" class="ml-4 text-gray-600">
            Cancel
        </a>
    </form>

</div>
@endif

@endsection
