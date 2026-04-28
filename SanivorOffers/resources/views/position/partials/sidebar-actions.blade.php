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
        <button data-new-position-btn type="button" class="btn btn-sm btn-success mt-1" onclick="addNewPos()"
            style="width:100%;border-radius:8px;font-size:12px;">
            <i class="fa-solid fa-plus"></i> New Position
        </button>
        <div data-new-position-feedback
            style="display:none;margin-top:6px;padding:6px 8px;border-radius:6px;background:rgba(220,38,38,0.15);color:#fecaca;font-size:11px;line-height:1.35;">
        </div>
        <div class="pos-list-container">
            <div data-sortable-position-list>
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
                                onsubmit='return confirm("Are you sure?");'>
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
    <a href="{{ route('offert.pdf', $offertId) }}" class="external-pdf-link" target="_blank" rel="noopener noreferrer">
        <i class="fa-solid fa-file-export"></i><span>External PDF</span>
    </a>
</div>

<script>
    window._positionActionPending = false;

    // _insertedPositionSection holds the live DOM node that was injected into the sidebar.
    // All querySelector calls below use this reference so they never accidentally target
    // the hidden #position-sidebar-template copy (which has the same IDs).
    let _insertedPositionSection = null;

    window.setPositionActionPending = function(isPending) {
        window._positionActionPending = !!isPending;
        const root = _insertedPositionSection || document;
        const btn = root.querySelector('[data-new-position-btn]');
        if (!btn) return;
        btn.disabled = !!isPending;
        btn.style.opacity = isPending ? '0.65' : '';
        btn.style.cursor = isPending ? 'not-allowed' : '';
    };
    window.setNewPositionFeedback = function(message) {
        const root = _insertedPositionSection || document;
        const node = root.querySelector('[data-new-position-feedback]');
        if (!node) return;
        if (!message) {
            node.style.display = 'none';
            node.textContent = '';
            return;
        }
        node.textContent = message;
        node.style.display = 'block';
    };

    window.handlePositionSidebarNavigate = function(linkEl, event) {
        if (!linkEl || !linkEl.href) return true;
        if (window._positionActionPending) return false;
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

            // Store reference to the live node so setPositionActionPending /
            // setNewPositionFeedback always target the visible sidebar elements.
            _insertedPositionSection = positionSection;

            // Clear the hidden template's content so that document.getElementById()
            // and querySelector() calls elsewhere never accidentally find the stale
            // duplicate IDs that were inside the template.
            template.innerHTML = '';
        }

        const pdfFooterTemplate = document.getElementById('external-pdf-footer-slot-template');
        if (sidebar && footer && pdfFooterTemplate) {
            const pdfWrap = document.createElement('div');
            pdfWrap.innerHTML = pdfFooterTemplate.innerHTML.trim();
            const pdfLink = pdfWrap.firstElementChild;
            if (pdfLink) {
                footer.insertBefore(pdfLink, footer.firstElementChild);
            }
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
                    const separator = linkEl.href.includes('?') ? '&' : '?';
                    modalIframe.src = `${linkEl.href}${separator}embed=1`;
                    modalBackdrop.style.display = 'flex';
                    document.body.style.overflow = 'hidden';
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

        // Use the live inserted section to find the sortable list so Sortable is never
        // accidentally bound to the hidden template copy.
        const sortableList = _insertedPositionSection
            ? _insertedPositionSection.querySelector('[data-sortable-position-list]')
            : null;
        if (sortableList && typeof Sortable !== 'undefined') {
            new Sortable(sortableList, {
                handle: '.drag-handle',
                animation: 150,
                onUpdate: async function(evt) {
                    const rows = Array.from(evt.to.children);
                    const orders = [];
                    rows.forEach((row, index) => {
                        const label = row.querySelector('.position-number-label');
                        if (label) {
                            label.textContent = `Pos. ${index + 1}`;
                        }

                        const positionId = row.getAttribute('data-position-id');
                        if (positionId) {
                            orders.push({ position_id: parseInt(positionId, 10), order: index + 1 });
                        }
                    });

                    try {
                        await fetch('{{ route("position.updateOrder") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ orders: orders })
                        });
                    } catch (error) {
                        console.error('Position reorder save failed:', error);
                    }
                }
            });
        }

        // Intercept delete-position form submissions: cancel the debounced auto-save
        // timer first so we don't fire a save request for a position that is about to
        // be removed.  The form is then allowed to submit normally (the confirm() dialog
        // has already returned true at this point).
        if (_insertedPositionSection) {
            _insertedPositionSection.addEventListener('submit', function(e) {
                const form = e.target;
                const methodInput = form.querySelector('input[name="_method"]');
                if (!methodInput || methodInput.value !== 'DELETE') return;
                // Cancel any pending debounced auto-save (in-flight fetches complete on
                // their own — they won't interfere with the delete).
                if (typeof window.cancelPositionAutoSave === 'function') {
                    window.cancelPositionAutoSave();
                }
                // Allow the form to proceed normally.
            });
        }

        // addNewPos: async so we can await the auto-save flush before creating the new
        // position.  This prevents a race condition where the createEmpty request and an
        // in-flight auto-save both try to lock the offert row at the same time.
        window.addNewPos = async function() {
            if (window._positionActionPending) return;
            window.setPositionActionPending(true);
            window.setNewPositionFeedback('');

            // Flush any pending auto-save before creating the new position so that the
            // server doesn't have to juggle two concurrent writes on the same offert.
            if (typeof window.flushPositionAutoSave === 'function') {
                try { await window.flushPositionAutoSave(); } catch (e) { /* non-fatal */ }
            }

            const offertId = '{{ $offertId }}';
            fetch('{{ route("position.create-empty") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ offert_id: parseInt(offertId, 10) || offertId })
            })
            .then(async response => {
                const data = await response.json().catch(() => ({}));
                if (!response.ok) {
                    const error = new Error((data && data.message) || 'Could not create position');
                    error.requestId = data && data.request_id ? data.request_id : null;
                    throw error;
                }
                return data;
            })
            .then(data => {
                if (data && data.success && data.edit_url) {
                    window.location.href = data.edit_url;
                    return;
                }
                throw new Error((data && data.message) || 'Could not create position');
            })
            .catch(error => {
                console.error('Create empty position failed:', error);
                const requestHint = error && error.requestId ? ` (Ref: ${error.requestId})` : '';
                window.setNewPositionFeedback(`${error.message || 'Could not create position'}${requestHint}`);
                window.setPositionActionPending(false);
            });
        };

    });
</script>
