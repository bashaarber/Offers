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
            Allgemeine Parameter
        </div>
        <a href="{{ route('offert.edit', ['offert' => $offertId, 'from_position' => 1, 'return_url' => url()->full()]) }}"
            data-overview-popup="true"
            onclick="return window.openOffertOverviewPopup ? window.openOffertOverviewPopup(this) : true;"
            class="btn btn-sm btn-primary mt-1"
            style="width:100%;border-radius:8px;font-size:12px;display:block;">
            <i class="fa-solid fa-sliders"></i> Allgemeine Parameter
        </a>
        <hr style="border-color:rgba(255,255,255,0.08);margin:8px 0 4px;">
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
                                data-position-nav-link="1"
                                onclick="return window.handlePositionSidebarNavigate ? window.handlePositionSidebarNavigate(this, event) : true;"
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
                            @if ($positions->count() > 1)
                            <form action="{{ route('position.destroy', $pos->id) }}" method="post" style="margin:0;"
                                onsubmit='if(!confirm("Are you sure?")) return false; window._autoSaveLock = true;'>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    style="padding:1px 5px;font-size:10px;">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                            @else
                            <button type="button" class="btn btn-danger btn-sm" disabled
                                title="Cannot delete the only position"
                                style="padding:1px 5px;font-size:10px;opacity:0.35;cursor:not-allowed;">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                            @endif
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
    </div>
</div>

<div id="offert-overview-modal-template" style="display:none;">
    <div id="offert-overview-modal-backdrop"
        style="position:fixed;inset:0;background:rgba(15,23,42,0.55);z-index:3000;display:none;align-items:center;justify-content:center;padding:20px;">
        <div
            style="background:#fff;width:min(1200px,96vw);height:min(90vh,920px);border-radius:12px;overflow:hidden;box-shadow:0 20px 50px rgba(0,0,0,0.35);display:flex;flex-direction:column;">
            <div
                style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;border-bottom:1px solid #e5e7eb;background:#f8fafc;">
                <strong style="font-size:14px;color:#111827;">Allgemeine Parameter</strong>
                <button type="button" data-overview-modal-close
                    style="border:none;background:transparent;color:#334155;font-size:20px;line-height:1;cursor:pointer;">&times;</button>
            </div>
            <iframe data-overview-modal-iframe src="about:blank"
                style="border:0;width:100%;height:100%;background:#fff;"></iframe>
        </div>
    </div>
</div>

<div id="external-pdf-footer-slot-template" style="display:none;">
    <a href="javascript:void(0);" class="custom-external-pdf-link" onclick="window.openCustomPdfModal && window.openCustomPdfModal();">
        <i class="fa-solid fa-file-pdf"></i><span>Custom External PDF</span>
    </a>
    <a href="{{ route('offert.pdf', $offertId) }}" class="external-pdf-link" target="_blank" rel="noopener noreferrer">
        <i class="fa-solid fa-file-export"></i><span>External PDF</span>
    </a>
</div>

<div id="custom-pdf-modal-backdrop"
    style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.55);z-index:3000;align-items:center;justify-content:center;padding:20px;">
    <div style="background:#fff;width:min(440px,96vw);border-radius:12px;overflow:hidden;box-shadow:0 20px 50px rgba(0,0,0,0.35);">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-bottom:1px solid #e5e7eb;background:#f8fafc;">
            <strong style="font-size:14px;color:#111827;">Custom External PDF</strong>
            <button type="button" onclick="window.closeCustomPdfModal && window.closeCustomPdfModal();"
                style="border:none;background:transparent;color:#334155;font-size:20px;line-height:1;cursor:pointer;">&times;</button>
        </div>
        <div style="padding:16px;color:#111827;font-size:13px;">
            <div style="margin-bottom:10px;color:#475569;">Select which organigrams to include:</div>
            <div id="custom-pdf-organigrams-list" style="display:flex;flex-direction:column;gap:6px;max-height:50vh;overflow:auto;">
                @foreach ($organigrams ?? [] as $org)
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding:6px 8px;border:1px solid #e5e7eb;border-radius:6px;">
                        <input type="checkbox" class="custom-pdf-org-checkbox" value="{{ $org->id }}" checked>
                        <span>{{ $org->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>
        <div style="display:flex;justify-content:flex-end;gap:8px;padding:12px 16px;border-top:1px solid #e5e7eb;background:#f8fafc;">
            <button type="button" onclick="window.closeCustomPdfModal && window.closeCustomPdfModal();"
                class="btn btn-sm btn-secondary">Cancel</button>
            <button type="button" onclick="window.submitCustomPdf && window.submitCustomPdf();"
                class="btn btn-sm btn-primary">Generate PDF</button>
        </div>
    </div>
</div>

<script>
    window.handlePositionSidebarNavigate = function(linkEl, event) {
        if (!linkEl || !linkEl.href) return true;
        if (event) event.preventDefault();
        const nextUrl = linkEl.href;
        if (typeof window.doAutoSaveAndNavigate === 'function') {
            window.doAutoSaveAndNavigate(nextUrl);
        } else {
            window.location.href = nextUrl;
        }
        return false;
    };

    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('click', function(event) {
            const navLink = event.target.closest('a[data-position-nav-link="1"]');
            if (!navLink) return;
            event.preventDefault();
            window.handlePositionSidebarNavigate(navLink, event);
        });

        const sidebar = document.querySelector('.sidebar');
        const footer = sidebar?.querySelector('.sidebar-footer');
        const template = document.getElementById('position-sidebar-template');

        if (sidebar && footer && template) {
            const wrapper = document.createElement('div');
            wrapper.innerHTML = template.innerHTML;
            const positionSection = wrapper.firstElementChild;
            const scrollable = sidebar.querySelector('.sidebar-scrollable');
            const firstSidebarSection = scrollable?.querySelector('.sidebar-section');

            // Place Positions directly under "Main > Offer"
            if (scrollable && firstSidebarSection) {
                firstSidebarSection.insertAdjacentElement('afterend', positionSection);
            } else {
                sidebar.insertBefore(positionSection, footer);
            }
        }

        const pdfFooterTemplate = document.getElementById('external-pdf-footer-slot-template');
        if (sidebar && footer && pdfFooterTemplate) {
            const pdfWrap = document.createElement('div');
            pdfWrap.innerHTML = pdfFooterTemplate.innerHTML.trim();
            const links = Array.from(pdfWrap.children);
            const anchor = footer.firstElementChild;
            links.forEach(link => footer.insertBefore(link, anchor));
        }

        const modalTemplate = document.getElementById('offert-overview-modal-template');
        let modalBackdrop = null;
        let modalIframe = null;
        if (modalTemplate) {
            const modalWrap = document.createElement('div');
            modalWrap.innerHTML = modalTemplate.innerHTML.trim();
            const modalNode = modalWrap.firstElementChild;
            if (modalNode) {
                document.body.appendChild(modalNode);
                modalBackdrop = modalNode;
                modalIframe = modalNode.querySelector('[data-overview-modal-iframe]');
                const closeBtn = modalNode.querySelector('[data-overview-modal-close]');

                const closeModal = () => {
                    if (!modalBackdrop || !modalIframe) return;
                    modalBackdrop.style.display = 'none';
                    modalIframe.src = 'about:blank';
                    document.body.style.overflow = '';
                };

                if (closeBtn) closeBtn.addEventListener('click', closeModal);
                if (modalBackdrop) {
                    modalBackdrop.addEventListener('click', function(e) {
                        if (e.target === modalBackdrop) closeModal();
                    });
                }

                window.addEventListener('message', function(e) {
                    if (!e || !e.data || e.data.type !== 'offert-overview-close') return;
                    closeModal();
                });

                if (modalIframe) {
                    modalIframe.addEventListener('load', function() {
                        try {
                            const href = modalIframe.contentWindow.location.href || '';
                            if (href.includes('/position/') && href !== 'about:blank') {
                                closeModal();
                                window.location.href = href;
                            }
                        } catch (err) {}
                    });
                }

                window.openOffertOverviewPopup = function(linkEl) {
                    if (!linkEl || !modalBackdrop || !modalIframe) return true;

                    const openIt = () => {
                        const separator = linkEl.href.includes('?') ? '&' : '?';
                        modalIframe.src = `${linkEl.href}${separator}embed=1`;
                        modalBackdrop.style.display = 'flex';
                        document.body.style.overflow = 'hidden';
                    };

                    // Flush any pending position changes to the server and wait for the
                    // ACK before opening the popup. This prevents data loss: the popup
                    // triggers a full page reload (the offert save redirects back to the
                    // position URL and the iframe load-handler reloads the parent), so we
                    // must persist the position first or the reload would discard edits.
                    if (typeof window.doAutoSaveAndWait === 'function') {
                        window.doAutoSaveAndWait(openIt);
                    } else {
                        openIt();
                    }
                    return false;
                };

                document.addEventListener('click', function(e) {
                    const link = e.target.closest('a[data-overview-popup="true"]');
                    if (!link || !modalBackdrop || !modalIframe) return;
                    e.preventDefault();
                    window.openOffertOverviewPopup(link);
                });
            }
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
            const csrfToken = (document.querySelector('meta[name="csrf-token"]') || document.querySelector('input[name="_token"]'))?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value;

            const doCreate = function() {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("position.createEmpty") }}';
                form.innerHTML = `<input type="hidden" name="_token" value="${csrfToken}">
                                  <input type="hidden" name="offert_id" value="${offertId}">`;
                document.body.appendChild(form);
                form.submit();
            };

            // Auto-save current position first, then create the new empty one
            if (typeof window.doAutoSaveAndNavigate === 'function') {
                window.doAutoSaveAndNavigate(null, doCreate);
            } else {
                doCreate();
            }
        };

        // --- Custom External PDF modal ---
        const customPdfBackdrop = document.getElementById('custom-pdf-modal-backdrop');

        window.openCustomPdfModal = function() {
            if (!customPdfBackdrop) return;
            customPdfBackdrop.style.display = 'flex';
        };

        window.closeCustomPdfModal = function() {
            if (!customPdfBackdrop) return;
            customPdfBackdrop.style.display = 'none';
        };

        if (customPdfBackdrop) {
            customPdfBackdrop.addEventListener('click', function(e) {
                if (e.target === customPdfBackdrop) window.closeCustomPdfModal();
            });
        }

        window.submitCustomPdf = function() {
            const checked = Array.from(document.querySelectorAll('.custom-pdf-org-checkbox:checked'))
                .map(cb => cb.value);
            if (checked.length === 0) {
                alert('Please select at least one organigram.');
                return;
            }
            const params = checked.map(id => 'organigrams[]=' + encodeURIComponent(id)).join('&');
            const baseUrl = '{{ route("offert.pdf", $offertId) }}';
            const url = baseUrl + (baseUrl.includes('?') ? '&' : '?') + params;
            window.closeCustomPdfModal();
            if (typeof window.openExternalPdfAfterSave === 'function') {
                window.openExternalPdfAfterSave(url);
            } else {
                window.open(url, '_blank', 'noopener');
            }
        };
    });
</script>
