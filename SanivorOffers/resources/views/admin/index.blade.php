<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
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
            <h1 class="mb-3">Users List</h1>
            <form action="{{ route('user.index') }}" method="GET" class="search-form">
                <div class="input-group">
                    <input type="search" name="query" class="form-control" placeholder="Search...">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-secondary"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </form>
            <a href="{{ url('/users') }}" class="btn btn-dark mb-1">
                <i class="fas fa-times"></i>
            </a>
            <a href="{{ route('register') }}" class="btn btn-primary float-right mb-3">Register User</a>
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role }}</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $users->appends(['query' => $query])->links() }}

        </div>
    </div>

</body>

</html>
