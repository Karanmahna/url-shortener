<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invite Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="card shadow-sm mx-auto" style="max-width: 600px;">
        <div class="card-header">
            <h4 class="mb-0">Invite New Client</h4>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('superadmin.invite-client.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Client / Company Name</label>
                    <input
                        type="text"
                        name="company_name"
                        class="form-control"
                        value="{{ old('company_name') }}"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">Admin Email</label>
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email') }}"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary">
                    Send Invitation
                </button>

                <a href="{{ route('superadmin.dashboard') }}"
                   class="btn btn-secondary">
                    Cancel
                </a>
            </form>
        </div>
    </div>
</div>

</body>
</html>