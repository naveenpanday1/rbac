<?php

namespace App\Http\Controllers;
use App\Models\ShortUrl;

class RedirectController extends Controller
{
    public function resolve($code)
    {
        $shortUrl = ShortUrl::where('short_code', $code)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->firstOrFail();

        return redirect()->away($shortUrl->original_url);
    }
}
