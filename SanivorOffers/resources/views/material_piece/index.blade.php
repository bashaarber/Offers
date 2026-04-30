<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

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
    </style>
</head>

<body>
    @include('layouts.sidebar')
    <div class="content">
        <div class="container">
            <div class="d-flex align-items-center mt-3 mb-2">
                <a href="{{ route('material_piece.create') }}" class="btn btn-primary ml-auto">@lang('public.create_material_piece')</a>
            </div>

            @include('layouts.partials.list-filter')

            <table class="table table-striped table-bordered" data-filterable>
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>@lang('public.name')</th>
                        <th class="text-center" colspan="2">@lang('public.price_chf')</th>
                        <th></th>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>@lang('public.price_in')</th>
                        <th>@lang('public.price_out')</th>
                        <th>@lang('public.actions')</th>
                    </tr>
                    <tr class="filter-row" style="background:#f8f9fa;">
                        <td><input data-col="0" type="text" placeholder="#" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:2px 4px;font-size:11px;"></td>
                        <td><input data-col="1" type="text" placeholder="{{ __('public.name') }}" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:2px 4px;font-size:11px;"></td>
                        <td><input data-col="2" type="text" placeholder="{{ __('public.price_in') }}" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:2px 4px;font-size:11px;"></td>
                        <td><input data-col="3" type="text" placeholder="{{ __('public.price_out') }}" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:2px 4px;font-size:11px;"></td>
                        <td></td>
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
            {{ $materials->links() }}
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>

</html>
