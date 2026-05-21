(function () {
    'use strict';

    var DEBOUNCE_MS = 600;
    var FOCUS_KEY = 'listFilterFocus';
    var timer = null;

    var toggleBtn = document.getElementById('toggleFilterBtn');
    var table = document.querySelector('table[data-filterable]');
    if (!table) return;

    var filterRow = table.querySelector('thead tr.filter-row');
    if (!filterRow) return;

    var filterCells = filterRow.querySelectorAll('td, th');
    var inputs = filterRow.querySelectorAll('input[name^="f["], select[name^="f["]');

    function anyFilterActive() {
        for (var i = 0; i < inputs.length; i++) {
            if (inputs[i].value && String(inputs[i].value).trim() !== '') return true;
        }
        return false;
    }

    function hideFilters() {
        filterRow.style.visibility = 'hidden';
        filterRow.style.height = '0';
        filterRow.style.overflow = 'hidden';
        filterCells.forEach(function (c) {
            c.style.padding = '0';
            c.style.height = '0';
            c.style.lineHeight = '0';
            c.style.fontSize = '0';
            c.style.borderTop = 'none';
            c.style.borderBottom = 'none';
        });
    }

    function showFilters() {
        filterRow.style.visibility = '';
        filterRow.style.height = '';
        filterRow.style.overflow = '';
        filterCells.forEach(function (c) {
            c.style.padding = '';
            c.style.height = '';
            c.style.lineHeight = '';
            c.style.fontSize = '';
            c.style.borderTop = '';
            c.style.borderBottom = '';
        });
    }

    // Restore focus + caret after the filter-triggered page reload.
    (function restoreFocus() {
        var raw = null;
        try { raw = sessionStorage.getItem(FOCUS_KEY); } catch (e) { return; }
        if (!raw) return;
        try { sessionStorage.removeItem(FOCUS_KEY); } catch (e) {}
        var saved;
        try { saved = JSON.parse(raw); } catch (e) { return; }
        for (var i = 0; i < inputs.length; i++) {
            if (inputs[i].name === saved.name) {
                var inp = inputs[i];
                inp.focus();
                if (typeof inp.setSelectionRange === 'function') {
                    var pos = typeof saved.caret === 'number' ? saved.caret : inp.value.length;
                    try { inp.setSelectionRange(pos, pos); } catch (e) {}
                }
                break;
            }
        }
    })();

    // Start visible if any filter has a server-echoed value, else hidden.
    var filtersVisible = anyFilterActive();
    if (filtersVisible) {
        showFilters();
        if (toggleBtn) {
            toggleBtn.classList.remove('btn-outline-secondary');
            toggleBtn.classList.add('btn-secondary');
        }
    } else {
        hideFilters();
    }

    inputs.forEach(function (inp) {
        inp.addEventListener('input', scheduleSubmit);
        inp.addEventListener('change', scheduleSubmit);
        inp.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(timer);
                navigate();
            }
        });
    });

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            if (filtersVisible) {
                hideFilters();
                filtersVisible = false;
                toggleBtn.classList.remove('btn-secondary');
                toggleBtn.classList.add('btn-outline-secondary');
                if (anyFilterActive()) {
                    inputs.forEach(function (i) { i.value = ''; });
                    navigate();
                }
            } else {
                showFilters();
                filtersVisible = true;
                toggleBtn.classList.remove('btn-outline-secondary');
                toggleBtn.classList.add('btn-secondary');
                if (inputs.length > 0) inputs[0].focus();
            }
        });
    }

    function scheduleSubmit() {
        clearTimeout(timer);
        timer = setTimeout(navigate, DEBOUNCE_MS);
    }

    function navigate() {
        // Remember which filter input is focused so we can restore it after reload.
        var active = document.activeElement;
        if (active && active.name && active.name.indexOf('f[') === 0) {
            try {
                sessionStorage.setItem(FOCUS_KEY, JSON.stringify({
                    name: active.name,
                    caret: typeof active.selectionStart === 'number' ? active.selectionStart : null
                }));
            } catch (e) {}
        }

        var url = new URL(window.location.href);

        // Drop existing f[*] params + page so filter reset goes back to page 1.
        var keysToDrop = [];
        url.searchParams.forEach(function (val, key) {
            if (key === 'page' || key.indexOf('f[') === 0) keysToDrop.push(key);
        });
        keysToDrop.forEach(function (k) { url.searchParams.delete(k); });

        inputs.forEach(function (inp) {
            var v = String(inp.value || '').trim();
            if (v !== '') url.searchParams.append(inp.name, v);
        });

        window.location.href = url.toString();
    }
})();
