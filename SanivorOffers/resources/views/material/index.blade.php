<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

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
                <a href="{{ route('material.create') }}" class="btn btn-primary ml-auto">@lang('public.create_material')</a>
            </div>

            @include('layouts.partials.list-filter')

            <table class="table table-striped table-bordered" data-filterable>
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>@lang('public.name')</th>
                        <th>@lang('public.unit')</th>
                        <th class="text-center" colspan="2">@lang('public.price_chf')</th>
                        <th class="text-center" colspan="5">@lang('public.time_hours')</th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>@lang('public.price_in')</th>
                        <th>@lang('public.price_out')</th>
                        <th>schlosserei</th>
                        <th>PE</th>
                        <th>Montage</th>
                        <th>@lang('public.total')</th>
                        <th>@lang('public.total_arbeit_label')</th>
                        <th>Material Pieces</th>
                        <th>@lang('public.actions')</th>
                    </tr>
                    @php $f = (array) request('f', []); @endphp
                    <tr class="filter-row" style="background:#f8f9fa;">
                        <td><input name="f[id]" value="{{ $f['id'] ?? '' }}" type="text" placeholder="#" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:2px 4px;font-size:11px;"></td>
                        <td><input name="f[name]" value="{{ $f['name'] ?? '' }}" type="text" placeholder="{{ __('public.name') }}" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:2px 4px;font-size:11px;"></td>
                        <td><input name="f[unit]" value="{{ $f['unit'] ?? '' }}" type="text" placeholder="{{ __('public.unit') }}" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:2px 4px;font-size:11px;"></td>
                        <td><input name="f[price_in]" value="{{ $f['price_in'] ?? '' }}" type="text" placeholder="{{ __('public.price_in') }}" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:2px 4px;font-size:11px;"></td>
                        <td><input name="f[price_out]" value="{{ $f['price_out'] ?? '' }}" type="text" placeholder="{{ __('public.price_out') }}" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:2px 4px;font-size:11px;"></td>
                        <td><input name="f[z_schlosserei]" value="{{ $f['z_schlosserei'] ?? '' }}" type="text" placeholder="Schl." style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:2px 4px;font-size:11px;"></td>
                        <td><input name="f[z_pe]" value="{{ $f['z_pe'] ?? '' }}" type="text" placeholder="PE" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:2px 4px;font-size:11px;"></td>
                        <td><input name="f[z_montage]" value="{{ $f['z_montage'] ?? '' }}" type="text" placeholder="Mont." style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:2px 4px;font-size:11px;"></td>
                        <td><input name="f[z_total]" value="{{ $f['z_total'] ?? '' }}" type="text" placeholder="Total" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:2px 4px;font-size:11px;"></td>
                        <td><input name="f[total_arbeit]" value="{{ $f['total_arbeit'] ?? '' }}" type="text" placeholder="Arbeit" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:2px 4px;font-size:11px;"></td>
                        <td><input name="f[material_pieces]" value="{{ $f['material_pieces'] ?? '' }}" type="text" placeholder="Pieces" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:2px 4px;font-size:11px;"></td>
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
                                        class="fas fa-pencil"></i> @lang('public.edit')</a>
                                <form action="{{ route('material.destroy', $material->id) }}" method="post"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick='return confirm("{{ __('public.confirm_delete') }}");'><i class="fas fa-trash"></i>
                                        @lang('public.delete')</button>
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
