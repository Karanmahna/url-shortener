<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Generate Short URL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="card shadow-sm mx-auto" style="max-width: 600px;">
        <div class="card-header">
            <h4>Generate Short URL</h4>
        </div>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('urls.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Original URL</label>
                    <input
                        type="url"
                        name="original_url"
                        class="form-control"
                        value="{{ old('original_url') }}"
                        placeholder="https://example.com"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary">
                    Generate
                </button>

                <a href="{{ route(auth()->user()->role . '.dashboard') }}"
                   class="btn btn-secondary">
                    Back
                </a>
            </form>
        </div>
    </div>
</div>

</body>
</html>