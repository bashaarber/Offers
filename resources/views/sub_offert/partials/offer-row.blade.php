@php
    $isSub = $isSub ?? false;
    $expandAll = $expandAll ?? false;
    $lockedByOther = $offert->isLockedByOther();
    $subCount = $isSub ? 0 : $offert->subOfferts->count();

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
<tr class="{{ $isSub ? 'sub-offer-row subs-of-' . $offert->parent_id : 'offer-row' }}"
    @if($isSub) style="{{ $expandAll ? '' : 'display:none;' }} background:#f8fbff;" @endif>
    <td style="{{ $isSub ? 'padding-left:24px;' : '' }}">
        @if($subCount > 0)
            <i class="fas fa-chevron-right toggle-subs" role="button"
               data-target="subs-of-{{ $offert->id }}"
               style="cursor:pointer; margin-right:6px; color:#3b82f6; transition:transform .15s;"></i>
        @elseif($isSub)
            <span style="display:inline-block; width:16px; color:#94a3b8;">&#8627;</span>
        @endif
        <strong>{{ $offert->display_number }}</strong>
        @if($lockedByOther)
            <span title="Being edited by {{ $offert->lockingUser?->username ?? 'another user' }}" style="color:#dc3545; margin-left:4px;"><i class="fas fa-lock" style="font-size:11px;"></i></span>
        @endif
        @if($subCount > 0)
            <span class="badge badge-info" style="margin-left:6px; font-size:10px;">{{ $subCount }}</span>
        @endif
    </td>
    <td>{{ \Carbon\Carbon::parse($offert->create_date)->format('d/m/y') }}</td>
    <td>{{ $offert->client->name }}</td>
    <td>{{ $offert->client_sign }}</td>
    <td>{{ $offert->object }}</td>
    <td>
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
        <div class="btn-group" style="gap: 4px;">
            <a href="{{ route('sub-offert.pdf', $offert->id) }}" class="btn btn-info btn-sm" title="External PDF" target="_blank" rel="noopener noreferrer"><i class="fa-solid fa-file-export"></i></a>
            <a href="{{ route('sub-offert.copy', $offert->id) }}" class="btn btn-secondary btn-sm" title="{{ __('public.copy') }}" onclick='return confirm("{{ __('public.confirm_copy_offer') }}")'><i class="fa fa-clone"></i></a>
            @unless($isSub)
                <a href="{{ route('sub-offert.create', ['parent_id' => $offert->id]) }}" class="btn btn-success btn-sm" title="{{ __('public.add_sub_offer') }}"><i class="fa-solid fa-code-branch"></i></a>
            @endunless
            @if($lockedByOther)
                <span class="btn btn-primary btn-sm disabled" title="Being edited by {{ $offert->lockingUser?->username ?? 'another user' }}" style="opacity:0.45; cursor:not-allowed; pointer-events:none;"><i class="fas fa-pencil"></i></span>
                <span class="btn btn-danger btn-sm disabled" title="Being edited by {{ $offert->lockingUser?->username ?? 'another user' }}" style="opacity:0.45; cursor:not-allowed; pointer-events:none;"><i class="fas fa-trash"></i></span>
            @else
                <a href="{{ route('sub-offert.edit', $offert->id) }}" class="btn btn-primary btn-sm" title="{{ __('public.edit') }}"><i class="fas fa-pencil"></i></a>
                <form action="{{ route('sub-offert.destroy', $offert->id) }}" method="post" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick='return confirm("{{ $isSub ? __('public.confirm_delete_offer') : __('public.confirm_delete_offer_with_subs') }}")' title="{{ __('public.delete') }}"><i class="fas fa-trash"></i></button>
                </form>
            @endif
        </div>
    </td>
</tr>
