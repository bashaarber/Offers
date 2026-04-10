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
    </style>
</head>

<body>
    @include('layouts.sidebar')
    <div class="content">
        <div class="container">
            <div class="d-flex align-items-center mt-3 mb-2">
                <a href="{{ route('material.create') }}" class="btn btn-primary ml-auto">Create Material</a>
            </div>

            @include('layouts.partials.list-filter')

            <table class="table table-striped table-bordered" data-filterable>
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
                        <th>Total</th>
                        <th>Total Arbeit</th>
                        <th>Material Pieces</th>
                        <th>Action</th>
                    </tr>
                    <tr class="filter-row" style="background:#f8f9fa;">
                        <td><input data-col="0" type="text" placeholder="#" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:2px 4px;font-size:11px;"></td>
                        <td><input data-col="1" type="text" placeholder="Name" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:2px 4px;font-size:11px;"></td>
                        <td><input data-col="2" type="text" placeholder="E" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:2px 4px;font-size:11px;"></td>
                        <td><input data-col="3" type="text" placeholder="In" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:2px 4px;font-size:11px;"></td>
                        <td><input data-col="4" type="text" placeholder="Out" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:2px 4px;font-size:11px;"></td>
                        <td><input data-col="5" type="text" placeholder="Schl." style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:2px 4px;font-size:11px;"></td>
                        <td><input data-col="6" type="text" placeholder="PE" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:2px 4px;font-size:11px;"></td>
                        <td><input data-col="7" type="text" placeholder="Mont." style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:2px 4px;font-size:11px;"></td>
                        <td><input data-col="8" type="text" placeholder="Total" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:2px 4px;font-size:11px;"></td>
                        <td><input data-col="9" type="text" placeholder="Arbeit" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:2px 4px;font-size:11px;"></td>
                        <td><input data-col="10" type="text" placeholder="Pieces" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:2px 4px;font-size:11px;"></td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($materials as $material)
                        <tr>
                            <td>{{ $material->id }}</td>
                            <td>{{ $material->name }}</td>
                            <td>{{ $material->unit }}</td>
                            <td>{{ $material->price_in }}</td>
                            <td>{{ $material->total }}</td>
                            <td>{{ $material->z_schlosserei }}</td>
                            <td>{{ $material->z_pe }}</td>
                            <td>{{ $material->z_montage }}</td>
                            <td>{{ $material->z_total }}</td>
                            <td>{{ number_format((float) ($material->total_arbeit ?? 0), 2, '.', "'") }}</td>
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
                                    <button type="submit" class="btn btn-danger btn-sm" onclick='return confirm("Are you sure?");'><i class="fas fa-trash"></i>
                                        Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $materials->links() }}
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>

</html>
