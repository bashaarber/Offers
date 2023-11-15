<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.css" />
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
            <h1 class="mb-3">Positions List</h1>
            <a href="{{ route('position.create') }}?offert_id={{ $offertId }}" class="btn btn-primary float-right mb-3">Create Position</a>
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th></th>
                        <th>Position ID</th>
                        <th>OffertID</th>
                        <th>Price Brutto</th>
                        <th>Preis mit Rabbat</th>
                        <th>Rabbat</th>
                        <th>Kosto CHF</th>
                        <th>Profit CHF</th>
                        <th>Total</th>
                        <th>Handlungen</th>
                    </tr>
                </thead>
                <tbody id="sortable-list">
                    @foreach ($positions as $position)
                        <tr data-position-id="{{ $position->id }}">
                            <td><span class="drag-handle">&#9776;</span></td>
                            <td>{{ $position->position_number }}</td>
                            @foreach ($position->offerts as $offert)
                                <td>{{ $offert->id }}</td>
                            @endforeach
                            <td>{{ $position->price_brutto }}</td>
                            <td>{{ $position->price_discount }}</td>
                            <td>{{ $position->discount }}%</td>
                            <td>{{ $position->costo }}</td>
                            <td>{{ $position->profit }}</td>
                            <td>{{ $position->total }}</td>
                            <td>
                                <a href="{{ route('position.edit', $position->id) }}" class="btn btn-primary btn-sm"><i
                                        class="fas fa-pencil"></i> Edit</a>
                                <form action="{{ route('position.destroy', $position->id) }}" method="post"
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
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var el = document.getElementById('sortable-list');
            var sortable = new Sortable(el, {
                handle: '.drag-handle',
                onUpdate: function (evt) {
                    var rows = Array.from(evt.from.children);
                    rows.forEach((row, index) => {
                        // Update position_number on the front end
                        row.children[1].innerText = index + 1;
    
                        // Get the position ID
                        var positionId = row.getAttribute('data-position-id');
    
                        // Send the new order to the server
                        fetch('{{ route("position.updateOrder") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ position_id: positionId, order: index + 1 })
                        });
                    });
                }
            });
        });
    </script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
</body>

</html>
