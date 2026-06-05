<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>@lang('public.offers')</title>
</head>

<body>
    @include('layouts.sidebar')
    <div class="content">
        <div class="container-fluid px-4 py-4">
            {{-- Page Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 style="font-weight: 700; color: #1a1d23; margin-bottom: 4px;">@lang('public.offers')</h3>
                    <p class="text-muted mb-0" style="font-size: 14px;">@lang('public.offers_subtitle')</p>
                </div>
                <a href="{{ route('offert.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-1"></i> @lang('public.new_offer')
                </a>
            </div>

            @if (session('lock_error'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert" style="border-radius: 8px;">
                    <i class="fas fa-lock mr-2"></i>{{ session('lock_error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            @endif

            @include('layouts.partials.list-filter')

            {{-- Table --}}
            <div class="card" style="border: none; box-shadow: 0 1px 8px rgba(0,0,0,0.06); border-radius: 12px; overflow: hidden;">
                <div class="table-responsive">
                    <table class="table mb-0" data-filterable>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('public.date')</th>
                                <th>@lang('public.client')</th>
                                <th>@lang('public.your_reference')</th>
                                <th>@lang('public.object')</th>
                                <th>@lang('public.status')</th>
                                <th>@lang('public.type')</th>
                                <th>@lang('public.user')</th>
                                <th style="text-align: right;">@lang('public.actions')</th>
                            </tr>
                            @php $f = (array) request('f', []); @endphp
                            <tr class="filter-row" style="background:#f8f9fa;">
                                <td><input name="f[id]" value="{{ $f['id'] ?? '' }}" type="text" placeholder="#" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:3px 6px;font-size:12px;"></td>
                                <td><input name="f[date]" value="{{ $f['date'] ?? '' }}" type="text" placeholder="{{ __('public.date') }}" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:3px 6px;font-size:12px;"></td>
                                <td><input name="f[client]" value="{{ $f['client'] ?? '' }}" type="text" placeholder="{{ __('public.client') }}" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:3px 6px;font-size:12px;"></td>
                                <td><input name="f[client_sign]" value="{{ $f['client_sign'] ?? '' }}" type="text" placeholder="{{ __('public.your_reference') }}" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:3px 6px;font-size:12px;"></td>
                                <td><input name="f[object]" value="{{ $f['object'] ?? '' }}" type="text" placeholder="{{ __('public.object') }}" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:3px 6px;font-size:12px;"></td>
                                <td>
                                    @php $statusVal = $f['status'] ?? ''; @endphp
                                    <select name="f[status]" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:3px 6px;font-size:12px;background:#fff;">
                                        <option value="">All</option>
                                        <option value="Neu" {{ $statusVal === 'Neu' ? 'selected' : '' }}>Neu</option>
                                        <option value="Zusage" {{ $statusVal === 'Zusage' ? 'selected' : '' }}>Zusage</option>
                                        <option value="Abszage" {{ $statusVal === 'Abszage' ? 'selected' : '' }}>Abszage</option>
                                        <option value="Finished" {{ $statusVal === 'Finished' ? 'selected' : '' }}>Finished</option>
                                    </select>
                                </td>
                                <td><input name="f[type]" value="{{ $f['type'] ?? '' }}" type="text" placeholder="{{ __('public.type') }}" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:3px 6px;font-size:12px;"></td>
                                <td><input name="f[user]" value="{{ $f['user'] ?? '' }}" type="text" placeholder="{{ __('public.user') }}" style="width:100%;border:1px solid #dee2e6;border-radius:4px;padding:3px 6px;font-size:12px;"></td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($offerts as $offert)
                                @include('offert.partials.offer-row', ['offert' => $offert, 'isSub' => false, 'expandAll' => $expandAll ?? false])
                                @foreach ($offert->subOfferts as $sub)
                                    @include('offert.partials.offer-row', ['offert' => $sub, 'isSub' => true, 'expandAll' => $expandAll ?? false])
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        <i class="fa-solid fa-inbox" style="font-size: 32px; margin-bottom: 8px; display: block; opacity: 0.3;"></i>
                                        @lang('public.no_offers_found')
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-body py-3">
                    {{ $offerts->links() }}
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>
        // Expand / collapse a parent offer's sub-offers.
        document.addEventListener('DOMContentLoaded', function () {
            function setRotation(icon, expanded) {
                icon.style.transform = expanded ? 'rotate(90deg)' : 'rotate(0deg)';
            }

            document.querySelectorAll('.toggle-subs').forEach(function (icon) {
                const targetClass = icon.getAttribute('data-target');
                const rows = document.querySelectorAll('tr.' + targetClass);
                // Reflect the initial (server-rendered) visibility on the caret.
                const startExpanded = Array.from(rows).some(r => r.style.display !== 'none');
                setRotation(icon, startExpanded);

                icon.addEventListener('click', function () {
                    const willExpand = Array.from(rows).some(r => r.style.display === 'none');
                    rows.forEach(function (r) { r.style.display = willExpand ? '' : 'none'; });
                    setRotation(icon, willExpand);
                });
            });
        });
    </script>
</body>
</html>
