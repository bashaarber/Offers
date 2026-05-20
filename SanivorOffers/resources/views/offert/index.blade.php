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
                                <tr>
                                    <td>
                                        <strong>{{ $offert->display_number }}</strong>
                                        @if($offert->isLockedByOther())
                                            <span title="Being edited by {{ $offert->lockingUser?->username ?? 'another user' }}" style="color:#dc3545; margin-left:4px;"><i class="fas fa-lock" style="font-size:11px;"></i></span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($offert->create_date)->format('d/m/y') }}</td>
                                    <td>{{ $offert->client->name }}</td>
                                    <td>{{ $offert->client_sign }}</td>
                                    <td>{{ $offert->object }}</td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'Neu' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                                                'new' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                                                'Zusage' => ['bg' => '#dcfce7', 'text' => '#166534'],
                                                'Abszage' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                                                'Finished' => ['bg' => '#f3e8ff', 'text' => '#6b21a8'],
                                                'finished' => ['bg' => '#f3e8ff', 'text' => '#6b21a8'],
                                            ];
                                            $colors = $statusColors[$offert->status] ?? ['bg' => '#f3f4f6', 'text' => '#374151'];
                                            $label = $offert->status == 'new' ? 'Neu' : ($offert->status == 'finished' ? 'Finished' : $offert->status);
                                        @endphp
                                        <span style="background: {{ $colors['bg'] }}; color: {{ $colors['text'] }}; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                            {{ $label }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($offert->type == 'client')
                                            <span style="font-size: 13px;">Klient</span>
                                        @elseif($offert->type == 'company')
                                            <span style="font-size: 13px;">Company</span>
                                        @else
                                            <span style="font-size: 13px;">{{ $offert->type }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $offert->user->username }}</td>
                                    <td style="white-space: nowrap; text-align: right;">
                                        @php $lockedByOther = $offert->isLockedByOther(); @endphp
                                        <div class="btn-group" style="gap: 4px;">
                                            <a href="{{ route('offert.pdf', $offert->id) }}" class="btn btn-info btn-sm" title="External PDF" target="_blank" rel="noopener noreferrer"><i class="fa-solid fa-file-export"></i></a>
                                            <a href="{{ route('offert.copy', $offert->id) }}" class="btn btn-secondary btn-sm" title="{{ __('public.copy') }}" onclick='return confirm("{{ __('public.confirm_copy_offer') }}")'><i class="fa fa-clone"></i></a>
                                            @if($lockedByOther)
                                                <span class="btn btn-primary btn-sm disabled" title="Being edited by {{ $offert->lockingUser?->username ?? 'another user' }}" style="opacity:0.45; cursor:not-allowed; pointer-events:none;"><i class="fas fa-pencil"></i></span>
                                                <span class="btn btn-danger btn-sm disabled" title="Being edited by {{ $offert->lockingUser?->username ?? 'another user' }}" style="opacity:0.45; cursor:not-allowed; pointer-events:none;"><i class="fas fa-trash"></i></span>
                                            @else
                                                <a href="{{ route('offert.edit', $offert->id) }}" class="btn btn-primary btn-sm" title="{{ __('public.edit') }}"><i class="fas fa-pencil"></i></a>
                                                <form action="{{ route('offert.destroy', $offert->id) }}" method="post" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick='return confirm("{{ __('public.confirm_delete_offer') }}")' title="{{ __('public.delete') }}"><i class="fas fa-trash"></i></button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
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
</body>
</html>
