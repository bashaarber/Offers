<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

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
                <h3 style="font-weight: 700; color: #1a1d23; margin-bottom: 0;">@lang('public.clients')</h3>
                <div class="d-flex align-items-center" style="gap: 8px;">
                    @if(isset($showArchived) && $showArchived)
                        <a href="{{ route('client.index') }}" class="btn btn-info">
                            <i class="fas fa-list"></i> @lang('public.active_clients')
                        </a>
                    @else
                        <a href="{{ route('client.index', ['show_archived' => 1]) }}" class="btn btn-secondary">
                            <i class="fas fa-archive"></i> @lang('public.archived')
                        </a>
                    @endif
                    <a href="{{ route('client.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> @lang('public.create_client')
                    </a>
                </div>
            </div>

            @include('layouts.partials.list-filter')

            <div class="card" style="border: none; box-shadow: 0 1px 8px rgba(0,0,0,0.06); border-radius: 10px; overflow: hidden;">
                <table class="table table-striped mb-0" data-filterable>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('public.name')</th>
                            <th>@lang('public.email_label')</th>
                            <th>@lang('public.number')</th>
                            <th>@lang('public.address')</th>
                            <th style="text-align: right;">@lang('public.actions')</th>
                        </tr>
                        <tr class="filter-row" style="background:#f8f9fa;">
                            <td><input data-col="0" type="text" placeholder="#" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:3px 6px;font-size:12px;"></td>
                            <td><input data-col="1" type="text" placeholder="{{ __('public.name') }}" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:3px 6px;font-size:12px;"></td>
                            <td><input data-col="2" type="text" placeholder="{{ __('public.email_label') }}" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:3px 6px;font-size:12px;"></td>
                            <td><input data-col="3" type="text" placeholder="{{ __('public.number') }}" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:3px 6px;font-size:12px;"></td>
                            <td><input data-col="4" type="text" placeholder="{{ __('public.address') }}" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:3px 6px;font-size:12px;"></td>
                            <td></td>
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
                                    <a href="{{ route('client.edit', $client->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-pencil"></i> @lang('public.edit')</a>
                                    @if($client->archived)
                                        <form action="{{ route('client.unarchive', $client->id) }}" method="post" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-info btn-sm"><i class="fas fa-box-open"></i> @lang('public.unarchive')</button>
                                        </form>
                                    @else
                                        <form action="{{ route('client.archive', $client->id) }}" method="post" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm" onclick='return confirm("{{ __('public.confirm_archive') }}");'><i class="fas fa-archive"></i> @lang('public.archive')</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('client.destroy', $client->id) }}" method="post" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick='return confirm("{{ __('public.confirm_delete') }}");'><i class="fas fa-trash"></i> @lang('public.delete')</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $clients->appends(['show_archived' => $showArchived ?? ''])->links() }}
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>

</html>
