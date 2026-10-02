<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Client Admin Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body class="bg-light">

<div class="container-fluid p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Client Admin Dashboard</h2>
            <p class="text-muted mb-0">
                {{ auth()->user()->name ?? 'Client' }}
            </p>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="btn btn-danger">Logout</button>
        </form>
    </div>

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

@if (session('invitation_url'))
    <div class="alert alert-info">
        <strong>Invitation Link:</strong>

        <div class="input-group mt-2">
            <input
                type="text"
                id="teamInvitationLink"
                class="form-control"
                value="{{ session('invitation_url') }}"
                readonly
            >

            <button
                type="button"
                class="btn btn-outline-primary"
                onclick="navigator.clipboard.writeText(
                    document.getElementById('teamInvitationLink').value
                )"
            >
                Copy
            </button>
        </div>
    </div>
@endif
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Generated URLs</h6>
                    <h3>{{ $totalUrls }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Team Members</h6>
                    <h3>{{ $totalMembers }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Generated URLs --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Generated Short URLs</h5>

            <a href="{{ route('create-urls') }}" class="btn btn-primary btn-sm">
                Generate
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Short URL</th>
                            <th>Original URL</th>
                            <th>Total hits</th>
                            <th>Created By</th>
                            <th>Created On</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($shortUrls as $url)
                            <tr>
                                <td><a href="{{ route('urls.redirect', $url->short_code) }}" target="_blank"> {{ route('urls.redirect', $url->short_code) }}
                                </a>
                                </td>
                                <td class="text-break">{{ $url->original_url }}</td>
                                <td>{{ $url->hits ?? 0}}</td>
                                <td>{{ $url->user->name ?? 'N/A' }}</td>
                                <td>{{ $url->created_at->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">
                                    No URLs generated yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $shortUrls->links() }}
        </div>
    </div>

    {{-- Team Members --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Team Members</h5>

            <div>
                <a href="{{ route('admin.team.index') }}"
                   class="btn btn-outline-secondary btn-sm">
                    View All
                </a>

                <a href="{{ route('admin.invite-team') }}"
                   class="btn btn-primary btn-sm">
                    Invite Team Member
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($teamMembers as $member)
                            <tr>
                                <td>{{ $member->name }}</td>
                                <td>{{ $member->email }}</td>
                                <td>{{ ucfirst($member->role) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">
                                    No team members found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
</body>
</html>