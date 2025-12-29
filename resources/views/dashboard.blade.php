@extends('layouts.app')
@section('page-title','Dashboard')

@php
    $role = auth()->user()->role->name ?? '';

    $bgClass = match($role) {
        'SuperAdmin' => 'bg-yellow-100', 
        'Admin'      => 'bg-blue-100',
        'Member'     => 'bg-green-100',
    };
@endphp

@section('content')

<div class="{{ $bgClass }} p-6 rounded shadow">

    <div class="flex justify-between items-center mb-4">
        <h3 class="font-semibold text-lg">Generated Short URLs</h3>
      
    </div>

    <form method="GET" class="flex flex-wrap gap-3 mb-6 items-center">
        <select name="filter" class="border p-2 rounded">
            <option value="">All</option>
            <option value="today" {{ $filter=='today' ? 'selected' : '' }}>Today</option>
            <option value="week" {{ $filter=='week' ? 'selected' : '' }}>Last Week</option>
            <option value="month" {{ $filter=='month' ? 'selected' : '' }}>Last Month</option>
        </select>

        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded">
            Apply
        </button>

        <a href="{{ route('dashboard') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded">
            Reset
        </a>

        <a href="{{ route('dashboard.download', ['filter' => $filter]) }}"
           class="bg-blue-600 text-white px-4 py-2 rounded">
            Download CSV
        </a>
    </form>

  <div class="overflow-x-auto bg-white rounded shadow">
    <table class="w-full border-collapse text-sm table-fixed">

        <thead class="bg-gray-200 sticky top-0 z-10">
            <tr>

                @if(auth()->user()->isSuperAdmin())
                    <th class="p-2 border w-48">Company</th>
                @endif

                <th class="p-2 border w-28">Short URL</th>
                <th class="p-2 border w-[45%]">Long URL</th>
                <th class="p-2 border w-20 text-center">Hits</th>
                <th class="p-2 border w-28">Created</th>
            </tr>
        </thead>

        <tbody>
        @forelse($urls as $url)
            <tr class="hover:bg-gray-50">

                @if(auth()->user()->isSuperAdmin())
                    <td class="p-2 border truncate"
                        title="{{ $url->company->name ?? '' }}">
                        {{ $url->company->name ?? '-' }}
                    </td>
                @endif

                <td class="p-2 border font-mono truncate"
                    title="/s/{{ $url->short_code }}">
                    <a href="{{ url('/s/'.$url->short_code) }}"
                       target="_blank"
                       class="text-blue-600 underline">
                        /s/{{ $url->short_code }}
                    </a>
                </td>

                {{-- LONG URL SAFE --}}
                <td class="p-2 border truncate whitespace-nowrap"
                    title="{{ $url->original_url }}">
                    {{ $url->original_url }}
                </td>

                <td class="p-2 border text-center font-semibold">
                    {{ $url->hits ?? 0 }}
                </td>

                <td class="p-2 border">
                    {{ $url->created_at->format('d M Y') }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ auth()->user()->isSuperAdmin() ? 5 : 4 }}"
                    class="p-4 text-center text-gray-500">
                    No URLs Found
                </td>
            </tr>
        @endforelse
        </tbody>

    </table>
</div>


 @if($urls->hasPages())
<div class="flex justify-between items-center mt-6 text-sm text-gray-700">

    <div>
        Showing {{ $urls->firstItem() ?? 0 }}
        to {{ $urls->lastItem() ?? 0 }}
        of {{ $urls->total() }}
    </div>

    <div class="flex items-center gap-2">

        {{-- First --}}
        <a href="{{ $urls->url(1) }}"
           class="px-3 py-1 border rounded
           {{ $urls->onFirstPage() ? 'text-gray-400 pointer-events-none' : 'hover:bg-gray-100' }}">
            First
        </a>

        <a href="{{ $urls->previousPageUrl() }}"
           class="px-3 py-1 border rounded
           {{ $urls->onFirstPage() ? 'text-gray-400 pointer-events-none' : 'hover:bg-gray-100' }}">
            Prev
        </a>

        <span class="px-3 py-1 border rounded bg-blue-600 text-white">
            {{ $urls->currentPage() }}
        </span>

        <a href="{{ $urls->nextPageUrl() }}"
           class="px-3 py-1 border rounded
           {{ !$urls->hasMorePages() ? 'text-gray-400 pointer-events-none' : 'hover:bg-gray-100' }}">
            Next
        </a>

        <a href="{{ $urls->url($urls->lastPage()) }}"
           class="px-3 py-1 border rounded
           {{ !$urls->hasMorePages() ? 'text-gray-400 pointer-events-none' : 'hover:bg-gray-100' }}">
            Last
        </a>

    </div>
</div>
@endif

</div>

@endsection
