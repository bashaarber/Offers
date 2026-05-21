(function () {
    'use strict';

    var DEBOUNCE_MS = 450;
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

    // Build the target URL from the current filter input values.
    function buildUrl() {
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

        return url.toString();
    }

    function navigate() {
        loadUrl(buildUrl());
    }

    // Fetch the target URL and swap only the table body + pagination,
    // leaving the rest of the page (and the focused filter input) untouched.
    function loadUrl(targetUrl) {
        var curTbody = table.querySelector('tbody');
        if (curTbody) curTbody.style.opacity = '0.4';

        fetch(targetUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (res) {
                if (!res.ok) throw new Error('bad status');
                return res.text();
            })
            .then(function (html) {
                var doc = new DOMParser().parseFromString(html, 'text/html');
                var newTable = doc.querySelector('table[data-filterable]');
                if (!newTable) throw new Error('no table');

                var newTbody = newTable.querySelector('tbody');
                if (newTbody && curTbody) {
                    curTbody.innerHTML = newTbody.innerHTML;
                    curTbody.style.opacity = '';
                }

                // Pagination (<ul class="pagination">) may appear or disappear.
                var newPag = doc.querySelector('.pagination');
                var curPag = document.querySelector('.pagination');
                if (newPag) newPag = document.importNode(newPag, true);
                if (newPag && curPag) {
                    curPag.parentNode.replaceChild(newPag, curPag);
                } else if (newPag && !curPag) {
                    table.parentNode.insertBefore(newPag, table.nextSibling);
                } else if (!newPag && curPag) {
                    curPag.remove();
                }

                history.pushState({}, '', targetUrl);
            })
            .catch(function () {
                // Network/parse failure — fall back to a normal full navigation.
                window.location.href = targetUrl;
            });
    }

    // Pagination links use AJAX too, so paging never triggers a full reload.
    document.addEventListener('click', function (e) {
        var link = e.target.closest ? e.target.closest('.pagination a') : null;
        if (!link || !link.href) return;
        e.preventDefault();
        loadUrl(link.href);
    });

    // Back/forward should reflect the URL that was pushed.
    window.addEventListener('popstate', function () {
        window.location.reload();
    });
})();
