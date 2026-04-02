<style>
    .sidebar .sidebar-footer {
        bottom: 120px;
    }

    .position-sidebar-section {
        padding: 0 4px;
    }
</style>

<div id="position-sidebar-template" style="display:none;">
    <div class="position-sidebar-section">
        <hr style="border-color:rgba(255,255,255,0.1);margin:4px 0;">
        <div style="padding:2px 4px;font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:0.6px;color:rgba(255,255,255,0.35);">
            Positions
        </div>
        <button type="button" class="btn btn-sm btn-success mt-1" onclick="addNewPos()"
            style="width:100%;border-radius:8px;font-size:12px;">
            <i class="fa-solid fa-plus"></i> New Position
        </button>
        <div class="pos-list-container">
            <div id="sortable-position-list">
                @foreach ($positions as $pos)
                    <div class="position-row" data-position-id="{{ $pos->id }}"
                        style="display:flex;align-items:center;justify-content:space-between;padding:2px 0;border-bottom:1px solid rgba(255,255,255,0.06);">
                        <div style="display:flex;align-items:center;gap:4px;">
                            <i class="fa-solid fa-grip-vertical drag-handle"
                                style="cursor:grab;color:rgba(255,255,255,0.45);font-size:10px;"></i>
                            <a href="{{ route('position.edit', $pos->id) }}"
                                style="color:{{ isset($currentPositionId) && (int) $currentPositionId === (int) $pos->id ? '#3b82f6' : 'rgba(255,255,255,0.7)' }};font-size:12px;font-weight:500;">
                                <strong class="position-number-label">Pos. {{ $pos->position_number }}</strong>
                                @if ($pos->is_optional)
                                    <span style="font-size:10px;color:#3b82f6;margin-left:4px;">(Optional)</span>
                                @endif
                            </a>
                        </div>
                        <div style="display:flex;gap:2px;">
                            <form action="{{ route('position.copy', $pos->id) }}" method="post" style="margin:0;">
                                @csrf
                                <button type="submit" class="btn btn-secondary btn-sm"
                                    style="padding:1px 5px;font-size:10px;">
                                    <i class="fa-solid fa-copy"></i>
                                </button>
                            </form>
                            <form action="{{ route('position.destroy', $pos->id) }}" method="post" style="margin:0;"
                                onsubmit='return confirm("Are you sure?");'>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    style="padding:1px 5px;font-size:10px;">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach

                {{-- Show the current position being created (not yet saved) --}}
                @if (isset($currentCreateNumber) && !$positions->contains('position_number', $currentCreateNumber))
                    <div style="display:flex;align-items:center;padding:2px 0;border-bottom:1px solid rgba(255,255,255,0.06);">
                        <div style="display:flex;align-items:center;gap:4px;">
                            <i class="fa-solid fa-pen" style="color:#3b82f6;font-size:10px;"></i>
                            <span style="color:#3b82f6;font-size:12px;font-weight:500;">
                                <strong>Pos. {{ $currentCreateNumber }}</strong>
                                <span style="font-size:10px;margin-left:4px;">(new)</span>
                            </span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div style="padding:4px 0;">
            <hr style="border-color:rgba(255,255,255,0.1);margin:4px 0;">
            <a href="{{ route('offert.pdf', $offertId) }}" class="external-pdf-link" target="_blank" rel="noopener noreferrer" style="font-size:12px;padding:3px 4px;">
                <i class="fa-solid fa-file-export" style="margin-right:6px;"></i>External PDF
            </a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.querySelector('.sidebar');
        const footer = sidebar?.querySelector('.sidebar-footer');
        const template = document.getElementById('position-sidebar-template');

        if (sidebar && footer && template) {
            const wrapper = document.createElement('div');
            wrapper.innerHTML = template.innerHTML;
            sidebar.insertBefore(wrapper.firstElementChild, footer);
        }

        // External PDF: persist current position (and any unsaved edits) before opening, so the PDF matches the UI
        if (sidebar) {
            sidebar.addEventListener('click', function(e) {
                const link = e.target.closest('a.external-pdf-link');
                if (!link) return;
                if (typeof window.openExternalPdfAfterSave === 'function') {
                    e.preventDefault();
                    window.openExternalPdfAfterSave(link.getAttribute('href'));
                }
            });
        }

        const sortableList = document.getElementById('sortable-position-list');
        if (sortableList && typeof Sortable !== 'undefined') {
            new Sortable(sortableList, {
                handle: '.drag-handle',
                animation: 150,
                onUpdate: function(evt) {
                    const rows = Array.from(evt.to.children);
                    rows.forEach((row, index) => {
                        const label = row.querySelector('.position-number-label');
                        if (label) {
                            label.textContent = `Pos. ${index + 1}`;
                        }

                        const positionId = row.getAttribute('data-position-id');
                        fetch('{{ route("position.updateOrder") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                position_id: positionId,
                                order: index + 1
                            })
                        });
                    });
                }
            });
        }

        window.addNewPos = function() {
            const offertId = '{{ $offertId }}';
            const nextIndex = {{ $nextCreateIndex ?? (int) $positions->count() }};
            const nextUrl = '{{ url("/position/create") }}/' + nextIndex + '?offert_id=' + offertId;

            // Auto-save current position before navigating (if available)
            if (typeof window.doAutoSaveAndNavigate === 'function') {
                window.doAutoSaveAndNavigate(nextUrl);
            } else {
                window.location.href = nextUrl;
            }
        };
    });
</script>
