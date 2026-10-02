<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body class="bg-light">

<div class="container-fluid p-4">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('invitation_url'))
        <div class="alert alert-info">
            <strong>Invitation Link:</strong>

            <div class="input-group mt-2">
                <input
                    type="text"
                    id="invitationLink"
                    class="form-control"
                    value="{{ session('invitation_url') }}"
                    readonly
                >

                <button
                    class="btn btn-outline-primary"
                    type="button"
                    onclick="copyInvitationLink()"
                >
                    Copy
                </button>
            </div>
        </div>
    @endif

    <script>
        function copyInvitationLink() {
            const input = document.getElementById('invitationLink');
            input.select();
            input.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(input.value);
        }
    </script>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Super Admin Dashboard</h2>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="btn btn-danger">Logout</button>
        </form>
    </div>

    {{-- Clients --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Clients</h5>
            <a href="{{ route('superadmin.invite-client') }}"
            class="btn btn-primary btn-sm">
                Invite
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Client Name</th>
                            <th>Users</th>
                            <th>Total Generated URLs</th>
                            <th>Total URLs Hits</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($companies as $company)
                            <tr>
                                <td>{{ $company->name }} <br> {{ $company->email}}</td>
                                <td>{{ $company->users_count }}</td>
                                <td>{{ $company->short_urls_count }}</td>
                                <td>{{ $company->short_urls_sum_hits ?? 0 }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">
                                    No clients found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $companies->links() }}
        </div>
    </div>

    {{-- Generated URLs --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Generated Short URLs</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Short URL</th>
                            <th>Long URL</th>
                            <th>Client</th>
                            <th>Created On</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($shortUrls as $url)
                            <tr>
                                <td>
                                    {{ url('/s/' . $url->short_code) }}
                                </td>
                                <td class="text-break">
                                    {{ $url->original_url }}
                                </td>
                                <td>{{ $url->company->name ?? 'N/A' }}</td>
                                <td>{{ $url->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">
                                    No short URLs found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $shortUrls->links() }}
        </div>
    </div>

</div>
</body>
</html>