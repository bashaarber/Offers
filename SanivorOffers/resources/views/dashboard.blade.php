<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    @include('layouts.sidebar')
    <div class="content">
        <div class="container-fluid px-4 py-4">
            <h3 style="font-weight: 700; color: #1a1d23; margin-bottom: 4px;">Dashboard</h3>
            <p class="text-muted" style="font-size: 14px;">Welcome to Sanivor Offers</p>

            @if (session('repair_status'))
                @php($status = session('repair_status'))
                <div style="margin-top: 16px; padding: 12px 16px; border-radius: 8px; background: {{ $status['ok'] ? '#e8f7ee' : '#fdeaea' }}; color: {{ $status['ok'] ? '#146c43' : '#842029' }};">
                    Repair completed. Pivot rows: {{ $status['before'] }} -> {{ $status['after'] }}
                </div>
            @endif

            @if (auth()->check() && auth()->user()->role === 'admin' && filter_var(env('REPAIR_ENDPOINT_ENABLED', false), FILTER_VALIDATE_BOOLEAN))
                <form method="POST" action="{{ route('ops.repair-element-material') }}" style="margin-top: 18px;">
                    @csrf
                    <button type="submit" style="background: #0d6efd; color: #fff; border: none; border-radius: 6px; padding: 10px 14px; font-weight: 600; cursor: pointer;">
                        Repair Element-Material Connections
                    </button>
                </form>
            @endif
        </div>
    </div>
</body>
</html>
