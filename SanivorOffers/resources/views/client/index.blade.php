<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Client List</title>
</head>

<body>
    @include('layouts.sidebar')
    <div class="content">
        <div class="container-fluid px-4 py-3">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 style="font-weight: 700; color: #1a1d23; margin-bottom: 0;">Clients</h3>
                <a href="{{ route('client.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-1"></i> Create Client
                </a>
            </div>

            <div class="d-flex align-items-center mb-3" style="gap: 8px;">
                <form action="{{ route('client.index') }}" method="GET" class="search-form">
                    <div class="input-group">
                        <input type="search" name="query" class="form-control" placeholder="Search..." value="{{ $query ?? '' }}">
                        @if(isset($showArchived) && $showArchived)
                            <input type="hidden" name="show_archived" value="1">
                        @endif
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-secondary"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </form>
                <a href="{{ route('client.index') }}" class="btn btn-dark" title="Clear">
                    <i class="fas fa-times"></i>
                </a>
                @if(isset($showArchived) && $showArchived)
                    <a href="{{ route('client.index') }}" class="btn btn-info">
                        <i class="fas fa-list"></i> Active Clients
                    </a>
                @else
                    <a href="{{ route('client.index', ['show_archived' => 1]) }}" class="btn btn-secondary">
                        <i class="fas fa-archive"></i> Archived
                    </a>
                @endif
            </div>

            <div class="card" style="border: none; box-shadow: 0 1px 8px rgba(0,0,0,0.06); border-radius: 10px; overflow: hidden;">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Number</th>
                            <th>Address</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clients as $client)
                        <tr>
                            <td>{{ $client->id }}</td>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->email }}</td>
                            <td>{{ $client->number }}</td>
                            <td>{{ $client->address }}</td>
                            <td style="white-space: nowrap; text-align: right;">
                                <div class="btn-group" style="gap: 4px;">
                                    <a href="{{ route('client.edit', $client->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-pencil"></i> Edit</a>
                                    @if($client->archived)
                                        <form action="{{ route('client.unarchive', $client->id) }}" method="post" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-info btn-sm"><i class="fas fa-box-open"></i> Unarchive</button>
                                        </form>
                                    @else
                                        <form action="{{ route('client.archive', $client->id) }}" method="post" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm" onclick='return confirm("Are you sure you want to archive this client?");'><i class="fas fa-archive"></i> Archive</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('client.destroy', $client->id) }}" method="post" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick='return confirm("Are you sure you want to delete this client?");'><i class="fas fa-trash"></i> Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $clients->appends(['query' => $query ?? '', 'show_archived' => $showArchived ?? ''])->links() }}
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>

</html>
