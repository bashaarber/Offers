<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Users</title>
</head>

<body>
    @include('layouts.sidebar')
    <div class="content">
        <div class="container">
            <div class="d-flex align-items-center mt-3 mb-2">
                <a href="{{ route('register') }}" class="btn btn-primary ml-auto">Register User</a>
            </div>

            @include('layouts.partials.list-filter')

            <table class="table table-striped table-bordered" data-filterable>
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>@lang('public.username_label')</th>
                        <th>@lang('public.email_label')</th>
                        <th>@lang('public.role')</th>
                    </tr>
                    <tr class="filter-row" style="background:#f8f9fa;">
                        <td><input data-col="0" type="text" placeholder="#" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:2px 4px;font-size:11px;"></td>
                        <td><input data-col="1" type="text" placeholder="{{ __('public.username_label') }}" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:2px 4px;font-size:11px;"></td>
                        <td><input data-col="2" type="text" placeholder="{{ __('public.email_label') }}" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:2px 4px;font-size:11px;"></td>
                        <td>
                            <select data-col="3" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:2px 4px;font-size:11px;background:#fff;">
                                <option value="">All</option>
                                <option value="admin">admin</option>
                                <option value="seller">seller</option>
                            </select>
                        </td>
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
            {{ $users->links() }}

        </div>
    </div>

</body>

</html>
