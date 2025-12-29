<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light d-flex flex-column min-vh-100 p-4">

    <header class="container mb-4">
        @if (Route::has('login'))
            <nav class="d-flex justify-content-end gap-2">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-outline-primary btn-sm">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm">Log in</a>
                @endauth
            </nav>
        @endif
    </header>

    <main class="container bg-white rounded shadow p-4 flex-grow-1">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-semibold">Generated Short URLs</h3>
        </div>

        <form method="GET" class="d-flex flex-wrap gap-2 align-items-center mb-4">
            <select name="filter" class="form-select form-select-sm w-auto">
                <option value="" {{ $filter == '' ? 'selected' : '' }}>All</option>
                <option value="today" {{ $filter == 'today' ? 'selected' : '' }}>Today</option>
                <option value="week" {{ $filter == 'week' ? 'selected' : '' }}>Last Week</option>
                <option value="month" {{ $filter == 'month' ? 'selected' : '' }}>Last Month</option>
            </select>

            <button type="submit" class="btn btn-primary btn-sm px-4">Apply</button>

            <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm px-4">Reset</a>

            <a href="{{ route('dashboard.download', ['filter' => $filter]) }}" class="btn btn-success btn-sm px-4">Download CSV</a>
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle text-truncate" style="min-width: 600px;">
                <thead class="table-light sticky-top">
                    <tr>
                        <th scope="col" style="width: 140px;">Short URL</th>
                        <th scope="col" class="text-center" style="width: 80px;">Hits</th>
                        <th scope="col" style="width: 140px;">Created</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($urls as $url)
                        <tr>
                            <td class="text-truncate" style="max-width: 180px;">
                                <a href="{{ url('/s/'.$url->short_code) }}" target="_blank" class="text-decoration-none text-primary">
                                    /s/{{ $url->short_code }}
                                </a>
                            </td>
                            <td class="text-center fw-semibold">{{ $url->hits ?? 0 }}</td>
                            <td>{{ $url->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">No URLs Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($urls->hasPages())
            <nav class="d-flex justify-content-between align-items-center mt-4" aria-label="Pagination">
                <div>
                    Showing {{ $urls->firstItem() ?? 0 }} to {{ $urls->lastItem() ?? 0 }} of {{ $urls->total() }}
                </div>

                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item {{ $urls->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $urls->url(1) }}">First</a>
                    </li>
                    <li class="page-item {{ $urls->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $urls->previousPageUrl() }}">Prev</a>
                    </li>
                    <li class="page-item active" aria-current="page">
                        <span class="page-link">{{ $urls->currentPage() }}</span>
                    </li>
                    <li class="page-item {{ !$urls->hasMorePages() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $urls->nextPageUrl() }}">Next</a>
                    </li>
                    <li class="page-item {{ !$urls->hasMorePages() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $urls->url($urls->lastPage()) }}">Last</a>
                    </li>
                </ul>
            </nav>
        @endif

    </main>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
