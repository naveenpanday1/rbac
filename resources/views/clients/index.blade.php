@extends('layouts.app')
@section('page-title','Clients')

@section('content')

<a href="{{ route('clients.create') }}"
   class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">
    Invite Client
</a>

<div class="bg-white p-6 rounded shadow">
<table class="w-full border">
<tr class="bg-gray-100">
    <th class="p-2 border">Company</th>
    <th class="p-2 border">Users</th>
</tr>

@foreach($clients as $client)
<tr>
    <td class="p-2 border">{{ $client->name }}</td>
    <td class="p-2 border">{{ $client->users_count }}</td>
</tr>
@endforeach
</table>
</div>
@endsection
