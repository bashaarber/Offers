<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Material Pieces List</title>
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

            <form action="{{ route('material_piece.index') }}" method="GET" class="search-form">
                <div class="input-group mt-3">
                    <input type="search" name="query" class="form-control" placeholder="Search...">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-secondary"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </form>

            <a href="{{ route('material_piece.index') }}" class="btn btn-dark mb-1">
                <i class="fas fa-times"></i>
            </a>
            <a href="{{ route('material_piece.create') }}" class="btn btn-primary float-right mt-3">Create Material Piece</a>

            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th class="text-center" colspan="2">Price(CHF)</th>
                        <th></th>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>In</th>
                        <th>Out</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($materials as $material)
                    <tr>
                        <td>{{ $material->id }}</td>
                        <td>{{ $material->name }}</td>
                        <td>{{ $material->price_in }}</td>
                        <td>{{ $material->price_out }}</td>
                        <td class="edit-delete-btns" style="white-space: nowrap;">
                            <a href="{{ route('material_piece.edit',$material->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-pencil"></i> Edit</a>
                            <form action="{{ route('material_piece.destroy', $material->id) }}" method="post" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick='return confirm("Are you sure?");'><i class="fas fa-trash"></i> Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $materials->appends(['query' => $query])->links() }}
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>

</html>
