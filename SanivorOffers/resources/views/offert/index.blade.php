<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Offert List</title>
    <style>
        .edit-delete-btns a,
        .edit-delete-btns button {
            margin-right: 5px;
        }

        .search-form {
            display: inline-block;
            margin-bottom: 10px;
        }

        .search-form input[type="search"] {
            width: 200px;
            margin-right: 5px;
        }
    </style>
</head>

<body>
    @include('layouts.sidebar')
    <div class="content">
        <div class="container">

            <form action="{{ route('offert.index') }}" method="GET" class="search-form">
                <div class="input-group mt-3">
                    <input type="search" name="query" class="form-control" placeholder="Search...">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-secondary"><i class="fa fa-search"></i></button>
                    </div>
                </div>
                
            </form>

            <a href="{{ route('offert.index') }}" class="btn btn-dark mb-1">
                <i class="fas fa-times"></i>
            </a>
            <a href="{{ route('offert.create') }}" class="btn btn-primary float-right mt-3">Create Offert</a>

            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Datum</th>
                        <th>Kunde</th>
                        <th>Ihr Zeichen</th>
                        <th>Objekt</th>
                        <th>
                            <form action="{{ route('offert.index') }}" method="GET">
                                <select name="status" onchange="this.form.submit()">
                                    <option value="">Status</option>
                                    <option value="Neu" {{ request('status') === 'Neu' ? 'selected' : '' }}>Neu - In progress</option>
                                    <option value="Zusage" {{ request('status') === 'Zusage' ? 'selected' : '' }}>Zusage</option>
                                    <option value="Abszage" {{ request('status') === 'Abszage' ? 'selected' : '' }}>Abszage</option>
                                    <option value="Finished" {{ request('status') === 'Finished' ? 'selected' : '' }}>Finished</option>
                                </select>
                            </form>
                        </th>
                        <th>Typ</th>
                        <th>User</th>
                        <th>Handlungen</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($offerts as $offert)
                        <tr>
                            <td>{{ $offert->id }}</td>
                            <td>{{ \Carbon\Carbon::parse($offert->create_date)->format('d/m/y') }}</td>
                            <td>{{ $offert->client->name }}</td>
                            <td>{{ $offert->client_sign }}</td>
                            <td>{{ $offert->object }}</td>
                            <td>
                                @if($offert->status == 'new')
                                    Neu
                                @elseif($offert->status == 'finished')
                                    Finished
                                @else
                                    {{ $offert->status }}
                                @endif
                            </td>
                            <td>
                                @if($offert->type == 'client')
                                    Klient
                                @elseif($offert->type == 'company')
                                    Company
                                @else
                                    {{ $offert->type }}
                                @endif
                            </td>
                            <td>{{ $offert->user->username }}</td>
                            <td style="white-space: nowrap;">
                                <a href="{{ route('offert.pdf-internal', $offert->id) }}" class="btn btn-warning btn-sm"><i class="fa-solid fa-file"></i> Internal</a>
                                <a href="{{ route('offert.pdf', $offert->id) }}" class="btn btn-warning btn-sm"><i class="fa-solid fa-file"></i> External</a>
                                <a href="{{ route('offert.copy', $offert->id) }}" class="btn btn-secondary btn-sm"><i
                                        class="fa fa-clone" aria-hidden="true"></i></a>
                                <a href="{{ route('offert.edit', $offert->id) }}" class="btn btn-primary btn-sm"><i
                                        class="fas fa-pencil"></i></a>
                                <form action="{{ route('offert.destroy', $offert->id) }}" method="post"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick='return confirm("Confirm delete");'><i class="fas fa-trash"></i>
                                        </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $offerts->appends(['query' => $query])->links() }}
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>
