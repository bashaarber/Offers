<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Offers</title>
</head>

<body>
    @include('layouts.sidebar')
    <div class="content">
        <div class="container-fluid px-4 py-4">
            {{-- Page Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 style="font-weight: 700; color: #1a1d23; margin-bottom: 4px;">Offers</h3>
                    <p class="text-muted mb-0" style="font-size: 14px;">Manage and track all your offers</p>
                </div>
                <a href="{{ route('offert.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-1"></i> Create Offer
                </a>
            </div>

            {{-- Search & Filter Bar --}}
            <div class="card mb-4" style="border: none; box-shadow: 0 1px 8px rgba(0,0,0,0.06); border-radius: 12px;">
                <div class="card-body py-3 d-flex align-items-center flex-wrap" style="gap: 12px;">
                    <form action="{{ route('offert.index') }}" method="GET" class="d-flex align-items-center" style="gap: 8px;">
                        <div class="input-group" style="max-width: 280px;">
                            <input type="search" name="query" class="form-control" placeholder="Search offers..." value="{{ request('query') }}" style="border-radius: 8px 0 0 8px;">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary" style="border-radius: 0 8px 8px 0;"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                    <a href="{{ route('offert.index') }}" class="btn btn-light" style="border-radius: 8px;" title="Clear filters">
                        <i class="fas fa-times"></i> Clear
                    </a>
                    <form action="{{ route('offert.index') }}" method="GET" class="ml-auto">
                        <select name="status" onchange="this.form.submit()" class="form-control" style="min-width: 180px; border-radius: 8px; font-size: 14px;">
                            <option value="">All Statuses</option>
                            <option value="Neu" {{ request('status') === 'Neu' ? 'selected' : '' }}>Neu - In progress</option>
                            <option value="Zusage" {{ request('status') === 'Zusage' ? 'selected' : '' }}>Zusage</option>
                            <option value="Abszage" {{ request('status') === 'Abszage' ? 'selected' : '' }}>Abszage</option>
                            <option value="Finished" {{ request('status') === 'Finished' ? 'selected' : '' }}>Finished</option>
                        </select>
                    </form>
                </div>
            </div>

            {{-- Table --}}
            <div class="card" style="border: none; box-shadow: 0 1px 8px rgba(0,0,0,0.06); border-radius: 12px; overflow: hidden;">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Datum</th>
                                <th>Kunde</th>
                                <th>Ihr Zeichen</th>
                                <th>Objekt</th>
                                <th>Status</th>
                                <th>Typ</th>
                                <th>User</th>
                                <th style="text-align: right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($offerts as $offert)
                                <tr>
                                    <td><strong>{{ $offert->id }}</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($offert->create_date)->format('d/m/y') }}</td>
                                    <td>{{ $offert->client->name }}</td>
                                    <td>{{ $offert->client_sign }}</td>
                                    <td>{{ $offert->object }}</td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'Neu' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                                                'new' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                                                'Zusage' => ['bg' => '#dcfce7', 'text' => '#166534'],
                                                'Abszage' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                                                'Finished' => ['bg' => '#f3e8ff', 'text' => '#6b21a8'],
                                                'finished' => ['bg' => '#f3e8ff', 'text' => '#6b21a8'],
                                            ];
                                            $colors = $statusColors[$offert->status] ?? ['bg' => '#f3f4f6', 'text' => '#374151'];
                                            $label = $offert->status == 'new' ? 'Neu' : ($offert->status == 'finished' ? 'Finished' : $offert->status);
                                        @endphp
                                        <span style="background: {{ $colors['bg'] }}; color: {{ $colors['text'] }}; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                            {{ $label }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($offert->type == 'client')
                                            <span style="font-size: 13px;">Klient</span>
                                        @elseif($offert->type == 'company')
                                            <span style="font-size: 13px;">Company</span>
                                        @else
                                            <span style="font-size: 13px;">{{ $offert->type }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $offert->user->username }}</td>
                                    <td style="white-space: nowrap; text-align: right;">
                                        <div class="btn-group" style="gap: 4px;">
                                            <a href="{{ route('offert.pdf-internal', $offert->id) }}" class="btn btn-warning btn-sm" title="Internal PDF"><i class="fa-solid fa-file-lines"></i></a>
                                            <a href="{{ route('offert.pdf', $offert->id) }}" class="btn btn-info btn-sm" title="External PDF"><i class="fa-solid fa-file-export"></i></a>
                                            <a href="{{ route('offert.copy', $offert->id) }}" class="btn btn-secondary btn-sm" title="Copy"><i class="fa fa-clone"></i></a>
                                            <a href="{{ route('offert.edit', $offert->id) }}" class="btn btn-primary btn-sm" title="Edit"><i class="fas fa-pencil"></i></a>
                                            <form action="{{ route('offert.destroy', $offert->id) }}" method="post" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick='return confirm("Are you sure you want to delete this offer?");' title="Delete"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        <i class="fa-solid fa-inbox" style="font-size: 32px; margin-bottom: 8px; display: block; opacity: 0.3;"></i>
                                        No offers found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-body py-3">
                    {{ $offerts->appends(['query' => $query])->links() }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
