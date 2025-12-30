<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;


class UrlController extends Controller
{
 public function welcome(Request $request)
{
    $filter = $request->get('filter');

    $query = ShortUrl::query();

    if ($filter === 'today') {
        $query->whereDate('created_at', Carbon::today());
    } elseif ($filter === 'week') {
        $query->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()]);
    } elseif ($filter === 'month') {
        $query->whereMonth('created_at', Carbon::now()->month);
    }

    $urls = $query->latest()->paginate(10)->withQueryString();

    return view('welcome', compact('urls', 'filter'));
}
public function index()
{
    $user = auth()->user();
    $totalHits = 0;
    if ($user->isSuperAdmin()) {
        $urls = ShortUrl::with('user')
            ->latest()
            ->paginate(10);
        $totalHits = ShortUrl::sum('hits');
    } elseif ($user->isAdmin()) {
        $urls = ShortUrl::with('user')
            ->where('company_id', $user->company_id)
            ->latest()
            ->paginate(10);
        $totalHits = ShortUrl::where('company_id', $user->company_id)
            ->sum('hits');
    } else {
        $urls = ShortUrl::with('user')
            ->where('created_by', $user->id)
            ->latest()
            ->paginate(10);
        $totalHits = ShortUrl::where('created_by', $user->id)
            ->sum('hits');
    }
    return view('urls.index', compact('urls', 'totalHits'));
}

    public function create()
    {
        if (auth()->user()->isSuperAdmin()) {
            abort(403, 'SuperAdmin cannot create short urls');
        }
        return view('urls.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            abort(403);
        }
        $request->validate([
            'original_url' => 'required|url',
        ]);
        ShortUrl::create([
            'original_url' => $request->original_url,
            'short_code'   => Str::random(6),
            'company_id'   => $user->company_id,
            'created_by'   => $user->id,
            'hits'         => 0,
        ]);
        return redirect()->route('urls.index')
            ->with('success', 'Short URL created successfully');
    }

    public function resolve(string $code)
    {
        $url = ShortUrl::where('short_code', $code)->firstOrFail();
        $url->increment('hits');
        return redirect()->away($url->original_url);
    }
}
