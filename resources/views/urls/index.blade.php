@extends('layouts.app')
@section('page-title','Short URLs')

@php
    $roleBg = match(true) {
        auth()->user()->isSuperAdmin() => 'bg-yellow-100',
        auth()->user()->isAdmin()      => 'bg-green-100',
        auth()->user()->isMember()     => 'bg-blue-100',
        default                        => 'bg-white',
    };
@endphp

@section('content')

@if(auth()->user()->isAdmin() || auth()->user()->isMember())
<div class="{{ $roleBg }} p-6 rounded shadow mb-6">
    <h3 class="font-semibold mb-3">Generate Short URL</h3>

    <form method="POST" action="{{ route('urls.store') }}" class="flex gap-3">
        @csrf
        <input type="url"
               name="original_url"
               class="border p-2 flex-1 rounded"
               placeholder="Enter long URL"
               required>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Generate
        </button>
    </form>
</div>
@endif

@if($urls->count() === 0)
    <div class="{{ $roleBg }} p-6 rounded shadow text-gray-500 text-center">
        No URLs available
    </div>
@else

<div class="{{ $roleBg }} p-6 rounded shadow">
    <h3 class="font-semibold mb-4">Generated URLs</h3>
    <table class="w-full border text-sm bg-white">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-2 border">Short Code</th>
                <th class="p-2 border">Original URL</th>
                @if(auth()->user()->isSuperAdmin())
                    <th class="p-2 border">Company</th>
                @elseif(auth()->user()->isAdmin())
                    <th class="p-2 border">User</th>
                @endif
                <th class="p-2 border text-center">Hits</th>
            </tr>
        </thead>
        <tbody>
        @foreach($urls as $url)
            <tr class="hover:bg-gray-50">
                <td class="p-2 border font-mono">
                    {{ $url->short_code }}
                </td>

                <td class="p-2 border break-all">
                    <a href="{{ $url->original_url }}"
                       target="_blank"
                       class="text-blue-600 underline">
                        {{ $url->original_url }}
                    </a>
                </td>

                @if(auth()->user()->isSuperAdmin())
                    <td class="p-2 border">
                        {{ $url->company->name ?? '-' }}
                    </td>
                @elseif(auth()->user()->isAdmin())
                    <td class="p-2 border">
                        {{ $url->user->name ?? '-' }}
                    </td>
                @endif

                <td class="p-2 border text-center font-semibold">
                    {{ $url->hits ?? 0 }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{-- ================= PAGINATION ================= --}}
    <div class="flex justify-between items-center mt-4 text-sm text-gray-600">
        <div>
            Showing {{ $urls->firstItem() }} to {{ $urls->lastItem() }}
            of {{ $urls->total() }}
        </div>

        <div>
            {{ $urls->links() }}
        </div>
    </div>

</div>

@endif

@endsection
