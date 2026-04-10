(function () {
    'use strict';

    var DEBOUNCE_MS = 120;
    var timer = null;

    var toggleBtn = document.getElementById('toggleFilterBtn');
    var table = document.querySelector('table[data-filterable]');
    if (!table) return;

    var tbody = table.querySelector('tbody');
    if (!tbody) return;

    var filterRow = table.querySelector('thead tr.filter-row');
    if (!filterRow) return;

    var filterCells = filterRow.querySelectorAll('td, th');

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

    hideFilters();
    var filtersVisible = false;

    var colInputs = [];
    var inputs = filterRow.querySelectorAll('input[data-col], select[data-col]');
    inputs.forEach(function (inp) {
        colInputs.push({ el: inp, col: parseInt(inp.getAttribute('data-col'), 10) });
        inp.addEventListener('input', scheduleFilter);
        inp.addEventListener('change', scheduleFilter);
    });

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            if (filtersVisible) {
                hideFilters();
                filtersVisible = false;
                toggleBtn.classList.remove('btn-secondary');
                toggleBtn.classList.add('btn-outline-secondary');
                colInputs.forEach(function (ci) { ci.el.value = ''; });
                scheduleFilter();
            } else {
                showFilters();
                filtersVisible = true;
                toggleBtn.classList.remove('btn-outline-secondary');
                toggleBtn.classList.add('btn-secondary');
                if (colInputs.length > 0) colInputs[0].el.focus();
            }
        });
    }

    function scheduleFilter() {
        clearTimeout(timer);
        timer = setTimeout(runFilter, DEBOUNCE_MS);
    }

    function runFilter() {
        var rows = tbody.querySelectorAll('tr');

        rows.forEach(function (row) {
            var cells = row.querySelectorAll('td');
            if (cells.length === 0) return;

            var show = true;

            for (var i = 0; i < colInputs.length; i++) {
                var ci = colInputs[i];
                var term = ci.el.value.toLowerCase().trim();
                if (!term) continue;
                var cell = cells[ci.col];
                if (!cell) { show = false; break; }
                if (cell.textContent.toLowerCase().indexOf(term) === -1) {
                    show = false;
                    break;
                }
            }

            row.style.display = show ? '' : 'none';
        });
    }
})();
