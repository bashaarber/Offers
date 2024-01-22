<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Material List</title>
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
            <form action="{{ route('material.index') }}" method="GET" class="search-form">
                <div class="input-group mt-3">
                    <input type="search" name="query" class="form-control" placeholder="Search...">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-secondary"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </form>

            <a href="{{ route('material.index') }}" class="btn btn-dark mb-1">
                <i class="fas fa-times"></i>
            </a>
            <a href="{{ route('material.create') }}" class="btn btn-primary float-right mt-3">Create Material</a>

            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>E</th>
                        <th class="text-center" colspan="2">Price(CHF)</th>
                        <th class="text-center" colspan="5">Zeit(Uhr)</th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>In</th>
                        <th>Out</th>
                        <th>schlosserei</th>
                        <th>PE</th>
                        <th>Montage</th>
                        <th>Fermacell</th>
                        <th>Total</th>
                        <th>Material Pieces</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($materials as $material)
                        <tr>
                            <td>{{ $material->id }}</td>
                            <td>{{ $material->name }}</td>
                            <td>{{ $material->unit }}</td>
                            <td>{{ $material->price_in }}</td>
                            <td>{{ $material->price_out }}</td>
                            <td>{{ $material->z_schlosserei }}</td>
                            <td>{{ $material->z_pe }}</td>
                            <td>{{ $material->z_montage }}</td>
                            <td>{{ $material->z_fermacell }}</td>
                            <td>{{ $material->z_total }}</td>
                            <td>
                                @foreach ($material->material_pieces as $material_piece)
                                - {{ $material_piece->name }} <br>
                                @endforeach
                            </td>
                            
                            <td style="white-space: nowrap;">
                                <a href="{{ route('material.edit', $material->id) }}" class="btn btn-primary btn-sm"><i
                                        class="fas fa-pencil"></i> Edit</a>
                                <form action="{{ route('material.destroy', $material->id) }}" method="post"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i>
                                        Delete</button>
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
