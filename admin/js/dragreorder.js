/*
 * dragreorder.js — turns any admin table that has the legacy up/down order
 * links into a hold-and-drag reorderable list that saves via reorder.php
 * (no page reload, no jump back to page 1).
 *
 * How it works, with zero per-table markup changes:
 *   - finds every "?orderup...&<table>_id=<id>" link already rendered,
 *   - derives the table key + row id from that link,
 *   - destroys the table's DataTable (so all rows are present in stored order
 *     and no redraw can revert a drag), caps the table height + scrolls,
 *   - hides the up/down chevrons, makes the first cell a drag handle,
 *   - on drop, POSTs the new id order to reorder.php.
 *
 * Requires SortableJS to be loaded before this file. jQuery/DataTables are
 * optional (only used to destroy an existing DataTable if present).
 */
(function () {
    'use strict';

    function onReady(fn) {
        if (document.readyState !== 'loading') { fn(); }
        else { document.addEventListener('DOMContentLoaded', fn); }
    }

    function idFromHref(href) {
        var m = (href || '').match(/[?&][a-z0-9_]+_id=(\d+)/i);
        return m ? m[1] : null;
    }

    function keyFromHref(href) {
        var m = (href || '').match(/[?&]([a-z0-9_]+)_id=\d+/i);
        return m ? m[1] : null;
    }

    onReady(function () {
        if (!window.Sortable) { return; }

        // Let any DataTables init (which also runs on ready) finish first.
        setTimeout(init, 0);
    });

    function init() {
        // Group reorderable tables by their key.
        var seen = {};
        var links = document.querySelectorAll('a[href*="orderup"]');
        Array.prototype.forEach.call(links, function (a) {
            var key = keyFromHref(a.getAttribute('href'));
            if (!key) { return; }
            var table = a.closest('table');
            if (!table || seen[key]) { return; }
            seen[key] = true;
            setupTable(key, table);
        });
    }

    function setupTable(key, table) {
        // Drop DataTables so every row is in the DOM in its stored order.
        try {
            if (window.jQuery && jQuery.fn.dataTable && jQuery.fn.dataTable.isDataTable(table)) {
                jQuery(table).DataTable().destroy();
            }
        } catch (e) { /* ignore */ }

        var tbody = table.tBodies[0];
        if (!tbody) { return; }

        Array.prototype.forEach.call(tbody.rows, function (tr) {
            var orderLink = tr.querySelector('a[href*="orderup"]');
            if (!orderLink) { return; }
            var id = idFromHref(orderLink.getAttribute('href'));
            if (id) { tr.setAttribute('data-reorder-id', id); }

            // Hide the legacy chevrons.
            Array.prototype.forEach.call(
                tr.querySelectorAll('a[href*="orderup"], a[href*="orderdown"]'),
                function (c) { c.style.display = 'none'; }
            );

            // Turn the first cell into a drag handle.
            var cell = tr.cells[0];
            if (cell && !cell.querySelector('.dragreorder-grip')) {
                cell.classList.add('dragreorder-handle');
                cell.style.cursor = 'grab';
                var grip = document.createElement('span');
                grip.className = 'dragreorder-grip text-secondary';
                grip.title = 'Drag to reorder';
                grip.innerHTML = ' <i class="fas fa-grip-vertical"></i>';
                cell.appendChild(grip);
            }
        });

        // Make a sticky header + a capped, scrollable container.
        var thead = table.tHead;
        if (thead) {
            Array.prototype.forEach.call(thead.querySelectorAll('th'), function (th) {
                th.style.position = 'sticky';
                th.style.top = '0';
                th.style.zIndex = '2';
                if (!th.style.background) { th.style.background = '#f8f9fc'; }
            });
        }
        var wrap = table.closest('.table-responsive') || table.parentElement;
        if (wrap) {
            wrap.style.maxHeight = '62vh';
            wrap.style.overflow = 'auto';
        }

        // Per-table status line.
        var status = document.createElement('div');
        status.className = 'small mt-2 text-muted';
        var anchor = (wrap && wrap.parentElement) ? wrap.parentElement : table.parentElement;
        anchor.appendChild(status);

        function setStatus(msg, cls) {
            status.textContent = msg;
            status.className = 'small mt-2 ' + cls;
        }

        Sortable.create(tbody, {
            handle: '.dragreorder-handle',
            animation: 150,
            ghostClass: 'table-active',
            onEnd: function () {
                var ids = Array.prototype.map.call(tbody.rows, function (tr) {
                    return tr.getAttribute('data-reorder-id');
                }).filter(Boolean);
                if (!ids.length) { return; }

                setStatus('Saving order…', 'text-muted');

                var body = new URLSearchParams();
                body.append('table', key);
                ids.forEach(function (id) { body.append('ids[]', id); });
                // Central CSRF guard (functions.php) also covers this AJAX POST.
                if (window.CSRF_TOKEN) { body.append('csrf_token', window.CSRF_TOKEN); }

                fetch('reorder.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: body.toString()
                })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (res && res.success) {
                        setStatus('✓ Order saved', 'text-success');
                    } else {
                        setStatus('Could not save order: ' + ((res && res.error) || 'error'), 'text-danger');
                    }
                })
                .catch(function () {
                    setStatus('Could not save order (network error).', 'text-danger');
                });
            }
        });
    }
})();
