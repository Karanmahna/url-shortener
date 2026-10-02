<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body class="bg-light">

<div class="container-fluid p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>{{ $title }}</h2>

        <div>
            @if($type === 'team')
                <a href="{{ route('admin.dashboard') }}"
                   class="btn btn-secondary">
                    Back to Dashboard
                </a>

                <a href="{{ route('admin.invite-team') }}"
                   class="btn btn-primary">
                    Invite Team Member
                </a>
            @elseif ($type === 'team_url')
                <a href="{{ route('admin.dashboard') }}"
                   class="btn btn-secondary">
                    Back to Dashboard
                </a>
            @else
                <a href="{{ route('superadmin.dashboard') }}"
                   class="btn btn-secondary">
                    Back to Dashboard
                </a>
            @endif
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">

                    <thead class="table-light">
                        @if($type === 'team')
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Total Generated URLs</th>
                                <th>Total Hits</th>
                            </tr>

                        @elseif($type === 'clients')
                            <tr>
                                <th>Client name</th>
                                <th>Users</th>
                                <th>Total Generated URLs</th>
                                <th>Total Hits</th>
                            </tr>

                        @elseif($type === 'urls' || $type === 'team_url')
                            <tr>
                                <th>Short URL</th>
                                <th>Original URL</th>
                                <th>Hits</th>
                                <th>User</th>
                                <th>Created On</th>
                            </tr>
                        @endif
                    </thead>

                    <tbody>
                        @forelse($items as $item)

                            @if($type === 'team')
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ ucfirst($item->role) }}</td>
                                    <td>{{ $item->short_urls_count }}</td>
                                    <td>{{ $item->short_urls_sum_hits ?? 0 }}</td>
                                </tr>

                            @elseif($type === 'clients')
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->users_count }}</td>
                                    <td>{{ $item->short_urls_count }}</td>
                                    <td>{{ $item->short_urls_sum_hits ?? 0 }}</td>
                                </tr>

                            @elseif($type === 'urls' || $type === 'team_url')
                                <tr>
                                    <td>
                                        <a href="{{ url('/s/' . $item->short_code) }}"
                                           target="_blank"
                                           rel="noopener noreferrer">
                                            {{ url('/s/' . $item->short_code) }}
                                        </a>
                                    </td>
                                    <td>
                                            {{ $item->original_url }}
                                    </td>
                                    <td>{{ $item->hits }}</td>
                                    <td>{{ $item->user->name ?? 'N/A' }}</td>
                                    <td>{{ $item->created_at?->format('d M Y') }}</td>
                                </tr>
                            @endif

                        @empty
                            <tr>
                                <td colspan="{{ $type === 'team' ? 4 : ($type === 'clients' ? 6 : 6) }}"
                                    class="text-center">
                                    No records found.
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