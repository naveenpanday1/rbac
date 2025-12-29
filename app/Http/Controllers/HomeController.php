<?php

namespace App\Http\Controllers;
use App\Models\ShortUrl;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{

   
public function index(Request $request)
{
    $user   = auth()->user();
    $filter = $request->get('filter');
    $query = ShortUrl::with('company');
    if ($user->isSuperAdmin()) {
    } elseif ($user->isAdmin()) {
        $query->where('company_id', $user->company_id);
    } elseif ($user->isMember()) {
        $query->where('created_by', $user->id);
    }

    if ($filter === 'today') {
        $query->whereDate('created_at', Carbon::today());
    }

    if ($filter === 'week') {
        $query->whereBetween('created_at', [
            Carbon::now()->subDays(7),
            Carbon::now()
        ]);
    }

    if ($filter === 'month') {
        $query->whereMonth('created_at', Carbon::now()->month);
    }

    $urls = $query->latest()->paginate(10)->withQueryString();

    return view('dashboard', compact('urls', 'filter'));
}

public function download()
{
    $user = auth()->user();

    $query = ShortUrl::query();

    if ($user->isAdmin()) {
        $query->where('company_id', $user->company_id);
    }

    $urls = $query->get();

    $filename = 'short_urls_' . now()->format('Ymd') . '.csv';

    $headers = [
        "Content-Type" => "text/csv",
        "Content-Disposition" => "attachment; filename=$filename"
    ];

    $callback = function () use ($urls) {
        $file = fopen('php://output', 'w');

        fputcsv($file, ['Short URL', 'Long URL', 'Created At']);

        foreach ($urls as $url) {
            fputcsv($file, [
                url('/r/'.$url->short_code),
                $url->original_url,
                $url->created_at
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}


}
