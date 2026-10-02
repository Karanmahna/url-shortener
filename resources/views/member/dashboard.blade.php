<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('short_link'))
            <div class="alert alert-info">
                <strong>Generated Short URL:</strong>

                <a href="{{ session('short_link') }}" target="_blank">
                    {{ session('short_link') }}
                </a>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Member's Dashboard</h2>
                <h3 class="text-muted mb-0">
                    {{ auth()->user()->name}}
                </h3>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-danger">Logout</button>
            </form>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Generated Short URLs</h5>

            <a href="{{ route('create-urls') }}" class="btn btn-primary btn-sm">
                Generate
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Short URL</th>
                        <th>Original URL</th>
                        <th>Hits</th>
                        <th>Created At</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($shortUrls as $index => $url)
                        <tr>
                            <td>{{ $index + 1 }}</td>                            
                            <td>
                                <a href="{{ route('urls.redirect', $url->short_code) }}"
                                    target="_blank">
                                    {{ route('urls.redirect', $url->short_code) }}
                                </a>
                            </td>
                            <td>
                                {{ $url->original_url }}
                            </td>

                            <td>{{ $url->hits ?? 0 }}</td>

                            <td>{{ $url->created_at?->format('d-m-Y h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                No URLs generated yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</body>
</html>
