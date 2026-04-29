<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create</title>
    <style>
        .group-elements,
        .elements {
            display: none;
            padding-left: 4px;
        }

        .group-element,
        .element {
            border-left: 2px solid #475569;
            padding-left: 4px;
            margin: 2px 0;
        }

        th {
            color: black;
        }

        .position-sidebar-section {
            padding: 0 4px;
        }

        .position-sidebar-section hr {
            border-color: rgba(255,255,255,0.1);
            margin: 4px 0;
        }

        .type-btn {
            margin: 1px;
            border-radius: 6px !important;
            font-size: 11px !important;
            padding: 2px 8px !important;
            font-weight: 600 !important;
        }

        .type-btn.active, .type-btn:hover {
            background-color: #3b82f6 !important;
            border-color: #3b82f6 !important;
            color: #fff !important;
        }

        .pos-list-container {
            max-height: 200px;
            overflow-y: auto;
            padding: 1px 3px;
        }

        .pos-list-container table {
            width: 100%;
        }

        .pos-list-container a {
            padding: 1px 3px !important;
            font-size: 12px !important;
        }

        .pos-list-container .btn {
            padding: 1px 5px !important;
            font-size: 10px !important;
        }


        .card {
            padding: 0 !important;
            margin-bottom: 1px !important;
        }

        .card-body {
            padding: 2px 4px !important;
        }

        .card h5, .card h6 {
            margin-bottom: 2px !important;
        }

        .optional-element-muted {
            opacity: 0.6;
            font-style: italic;
        }

        /* Override global layout h6 { color:#fff; background:gradient } for readability */
        .organigram-toggle {
            font-size: 14px;
            font-weight: 700;
            color: #111827;
            border-left: 3px solid #1e40af;
            background: rgba(30, 64, 175, 0.13);
            padding: 5px 8px !important;
            border-radius: 6px;
        }

        .group-element-toggle {
            font-size: 13px;
            font-weight: 600;
            color: #111827;
            border-left: 2px solid #0e7490;
            background: rgba(14, 116, 144, 0.10);
            padding: 4px 6px 4px 10px !important;
            border-radius: 6px;
        }

        .element-card-title {
            font-size: 12px;
            color: #111827;
            background: #e2e8f0;
            padding: 4px 8px !important;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6px;
            flex-wrap: wrap;
        }

        .element-card-title .element-name-row {
            display: flex;
            align-items: center;
            gap: 6px;
            flex: 1;
            min-width: 0;
            cursor: pointer;
        }

        .element-optional-inline {
            margin: 0;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .element-materials-wrap {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            margin-bottom: 12px;
            overflow: hidden;
        }

        .element-materials thead tr {
            background: #111827;
            color: #fff;
        }

        .element-materials tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .element-materials input[type="number"] {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 2px 6px;
        }

        .element-materials {
            margin-bottom: 0 !important;
            table-layout: fixed;
            width: 100%;
        }

        .element-materials thead th:nth-child(1) {
            width: 14%;
        }

        .element-materials thead th:nth-child(2) {
            width: 38%;
        }

        .element-materials thead th:nth-child(3) {
            width: 30%;
        }

        .element-materials thead th:nth-child(4) {
            width: 18%;
        }

        .element-materials th,
        .element-materials td {
            vertical-align: middle;
        }

        .element-materials .col-name,
        .element-materials td:nth-child(2) {
            text-align: left;
        }

        .element-materials .col-pstk,
        .element-materials td:nth-child(3) {
            text-align: left;
        }

        .element-materials thead th:nth-child(4),
        .element-materials .col-total,
        .element-materials td.total {
            text-align: right;
            font-variant-numeric: tabular-nums;
        }

        .element-materials td.total {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        }

        .element-materials .price-details {
            text-align: left;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            white-space: nowrap;
        }

        .element-materials .element-summary-name {
            font-weight: 700;
        }

        /* Compact density: right-hand materials panel only (not the organigram column) */
        .materials-scroll-area {
            border-radius: 10px;
        }

        .materials-scroll-area .element-materials thead th {
            position: sticky;
            top: 0;
            z-index: 10;
            background: #111827;
            color: #fff;
        }

        .position-materials-panel {
            font-size: 12px;
            line-height: 1.3;
        }

        .position-materials-panel .element-materials-wrap {
            margin-bottom: 8px;
            border-radius: 8px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
        }

        .position-materials-panel .element-materials {
            font-size: 11px;
        }

        .position-materials-panel .element-materials thead th {
            font-size: 10px;
            font-weight: 600;
            padding: 0.2rem 0.35rem;
        }

        .position-materials-panel .element-materials tbody th,
        .position-materials-panel .element-materials tbody td {
            padding: 0.2rem 0.35rem;
            font-size: 11px;
        }

        .position-materials-panel .element-materials tr.table-dark th {
            padding: 0.22rem 0.35rem;
            font-size: 11px;
        }

        .position-materials-panel .element-materials tbody tr:not(.table-dark) td.total {
            padding-right: 3.5rem;
        }

        .position-materials-panel .element-materials input[type="number"] {
            padding: 1px 4px;
            font-size: 11px;
            line-height: 1.2;
            min-height: 22px;
            border-radius: 4px;
        }

        .position-materials-panel .element-quantity-input {
            width: 72px !important;
        }

        .position-materials-panel .quantity-input {
            width: 58px !important;
        }

        .position-materials-panel .element-summary-name {
            font-size: 11px;
        }
    </style>
</head>

<body>
    @include('layouts.sidebar')

    @include('position.partials.sidebar-actions', [
        'positions' => $positions,
        'offertId' => request()->query('offert_id'),
        'currentPositionId' => null,
        'currentCreateNumber' => (int) ($index ?? 0) + 1,
        'nextCreateIndex' => max((int) $positions->count(), ((int) ($index ?? 0)) + 1),
        'showSaveButton' => true,
        'saveFormId' => 'createPositionForm',
        'organigrams' => $organigrams,
    ])
    @include('position.partials.element-selection-js')
    <div class="content">
        <div class="row">
            <div class="col-12">
                <form id="createPositionForm" method="POST" action="{{ route('position.store') }}">
                    @csrf
                    <input type="hidden" name="index" id="index" value="{{ $index ?? '' }}">
                    <input type="hidden" name="offert_id" id="offert_id" value="{{ request()->query('offert_id') }}">
                    <input type="hidden" name="auto_save" id="auto_save" value="0">
                    <input type="hidden" name="totalProTypPrice" id="totalProTypPriceInput" value="0.00">
                    <input type="hidden" name="discountedTotal" id="discountedTotalInput" value="0.00">
                    <input type="hidden" name="percentage" id="percentageInput" value="{{ $offert->default_rabatt ?? 0 }}">
                    <input type="hidden" name="price-out-input" id="priceOutInput" value="0.00">
                    <input type="hidden" name="zeit-cost-input" id="zeitCostInput" value="0.00">
                    <input type="hidden" name="material-costo" id="priceInInput" value="0.00">
                    <input type="hidden" name="material-profit" id="priceProfit" value="0.00">
                    <input type="hidden" name="zeit-costo" id="zeitCosto" value="0.00">
                    <input type="hidden" name="zeit-profit" id="zeitProfit" value="0.00">
                    <input type="hidden" name="costo-total" id="costoTotal" value="0.00">
                    <input type="hidden" name="profit-total" id="profitTotal" value="0.00">
                    @php $ctrl = 'height:26px;padding:1px 6px;font-size:12px;box-sizing:border-box;'; @endphp
                    <div style="display:flex;align-items:center;gap:6px;background:#212529;color:#fff;padding:6px 12px;white-space:nowrap;font-size:12px;">
                        <span>Rahmen</span>
                        <input style="width:75px;{{ $ctrl }}" value="Pos. {{ $nextPositionNumber ?? 1 }}" disabled>
                        <span>mm</span>
                        <span style="margin-left:8px">Desc.</span>
                        <input type="text" id="description" name="description" style="flex:1;min-width:150px;{{ $ctrl }}">
                        <span style="margin-left:8px">Blocktyp</span>
                        <select name="blocktype" id="blocktype" style="{{ $ctrl }}">
                            <option value="" selected> - </option>
                            <option value="Vorwand-Raumhoch">Vorwand-Raumhoch</option>
                            <option value="Vorwand-Raumhoch und Teilhoch">Vorwand-Raumhoch und Teilhoch</option>
                            <option value="Vorwand-Teilhoch">Vorwand-Teilhoch</option>
                            <option value="Freistehend-Raumhoch">Freistehend-Raumhoch</option>
                            <option value="Vorwand-Freistehend">Vorwand-Freistehend</option>
                            <option value="Freistehend-Teilhoch">Freistehend-Teilhoch</option>
                            <option value="Vorwand DeBO-System">Vorwand DeBO-System</option>
                            <option value="Trennwand DeBO-System">Trennwand DeBO-System</option>
                        </select>
                        <div style="flex:1"></div>
                        <span>B</span>
                        <input style="width:75px;{{ $ctrl }}" type="text" id="b" name="b">
                        <span>cm</span>
                        <span style="margin-left:8px">H</span>
                        <input style="width:75px;{{ $ctrl }}" type="text" id="h" name="h">
                        <span>cm</span>
                        <span style="margin-left:8px">T</span>
                        <input style="width:75px;{{ $ctrl }}" type="text" id="t" name="t">
                        <span>cm</span>
                    </div>
                    <table class="table">
                        <thead>
                            <tr class="table-dark">
                                <th></th>
                                <th scope="col">Preis Brutto</th>
                                <th scope="col">Preis mit Rabbat</th>
                                <th scope="col">Rabbat</th>
                                <th scope="col">Kosto CHF</th>
                                <th scope="col">Profit CHF</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="table-active">
                                <td><strong>Materiale Pro Typ</strong></td>
                                <td id="price-out-input" name="price-out-input">0.00</td>
                                <td id="price-out-input2">0.00</td>
                                <td>% <input value="0"></td>
                                <td id="price-in-input" name="material-costo">0.00</td>
                                <td id="price-profit" name="material-profit">0.00</td>
                            </tr>
                            <tr class="table-active">
                                <td><strong>Zeit Pro Typ</strong></td>
                                <td id="zeit-cost-input" name="zeit-cost-input">0.00</td>
                                <td id="zeit-cost-input2">0.00</td>
                                <td>% <input value="0"></td>
                                <td id="zeit-costo" name="zeit-costo">0.00</td>
                                <td id="zeit-profit" name="zeit-profit">0.00</td>
                            </tr>
                            <tr class="table-secondary">
                                <td><strong>Total Pro Typ</strong></td>
                                <td id="total-pro-typ-price2">0.00</td>
                                <td id="discounted-total2">0.00</td>
                                <td>% <input id="percentage-input2" disabled value="0"></td>
                                <td id="costo-total2">0.00</td>
                                <td id="profit-total2">0.00</td>
                            </tr>
                            <tr style="font-weight:700;color:black" class="table-dark">
                                <td>Menge <input id="menge-input" type="number" name="quantity" value="1" min="1"></td>
                                <td id="total-pro-typ-price" name="total-pro-typ-price">0.00</td>
                                <td id="discounted-total">0.00</td>
                                <td>% <input id="percentage-input" name="percentage-input" value="{{ $offert->default_rabatt ?? 0 }}">
                                    <button type="button" id="rabatt-default-btn"
                                        class="btn btn-sm btn-outline-light" style="margin-left:6px;padding:1px 6px;">
                                        Default
                                    </button>
                                </td>
                                <td id="costo-total" name="costo-total">0.00</td>
                                <td id="profit-total" name="profit-total">0.00</td>
                            </tr>
                        </tbody>
                    </table>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        @foreach ($organigrams as $organigram)
                            <h5 class="card-title organigram-toggle" style="cursor:pointer;">
                                <i class="fa-solid fa-chevron-right" style="font-size:10px;margin-right:6px;"></i>{{ $organigram->name }}
                            </h5>
                            <div class="group-elements">
                                @foreach ($organigram->group_elements as $group_element)
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-subtitle group-element-toggle" style="cursor:pointer;">
                                                <i class="fa-solid fa-chevron-right" style="font-size:9px;margin-right:6px;"></i>{{ $group_element->name }}
                                            </h6>
                                            <div class="elements">
                                                @foreach ($group_element->elements as $element)
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h6 class="card-subtitle element-card-title" data-element-id="{{ $element->id }}">
                                                                <span class="element-name-row">
                                                                    <input type="checkbox" name="selected_elements[]"
                                                                        class="element-checkbox"
                                                                        data-element-id="{{ $element->id }}"
                                                                        data-group-element-id="{{ $group_element->id }}"
                                                                        data-organigram-id="{{ $organigram->id }}"
                                                                        value="{{ $element->id }}">
                                                                    <span>{{ $element->name }}</span>
                                                                </span>
                                                                <label class="element-optional-inline mb-0">
                                                                    <input type="checkbox" class="element-optional-checkbox"
                                                                        name="element_optional[{{ $element->id }}]" value="1"
                                                                        data-element-id="{{ $element->id }}">
                                                                    Opt.
                                                                </label>
                                                            </h6>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                    <textarea name="description2" rows="3" style="margin-top:6px;"></textarea>
                </div>
            </div>
            <div class="col-md-9 position position-materials-panel">
                @php
    $totalZTotal = 0; // Initialize the variable to store the sum
@endphp
                <div class="materials-scroll-area">
                {{-- Single column header shown only once at the top --}}
                <table class="table element-materials" style="margin-bottom:0; border-radius:10px 10px 0 0; overflow:hidden;">
                    <colgroup>
                        <col style="width:14%">
                        <col style="width:38%">
                        <col style="width:30%">
                        <col style="width:18%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th scope="col">Ans.</th>
                            <th scope="col">Name</th>
                            <th scope="col">PStk.</th>
                            <th scope="col" class="col-total">Total CHF</th>
                        </tr>
                    </thead>
                </table>
                {{-- All elements are unselected on create — zero HTML tables rendered.
                     Tables are built from JSON on demand when the user checks an element. --}}
                <div id="element-materials-container"></div>
                </div>

                <script>
                window._unselectedElements = @json($allElementsData);

                function getLeftElementOrderIndex(elementId) {
                    var id = String(elementId);
                    var checkboxes = Array.from(document.querySelectorAll('.element-checkbox'));
                    var idx = checkboxes.findIndex(function(cb) {
                        return String(cb.getAttribute('data-element-id')) === id;
                    });
                    return idx === -1 ? Number.MAX_SAFE_INTEGER : idx;
                }

                function insertWrapByLeftOrder(wrap, elementId) {
                    var container = document.getElementById('element-materials-container');
                    if (!container) return;

                    var newIndex = getLeftElementOrderIndex(elementId);
                    var existingWraps = Array.from(container.querySelectorAll('.element-materials-wrap'));
                    for (var i = 0; i < existingWraps.length; i++) {
                        var existingWrap = existingWraps[i];
                        if (existingWrap === wrap) continue;
                        var existingId = (existingWrap.id || '').replace('element-materials-wrap-', '');
                        if (getLeftElementOrderIndex(existingId) > newIndex) {
                            container.insertBefore(wrap, existingWrap);
                            return;
                        }
                    }

                    container.appendChild(wrap);
                }

                function buildElementTable(elementId) {
                    if (document.getElementById('element-materials-wrap-' + elementId)) return;
                    var el = window._unselectedElements[elementId];
                    if (!el) return;

                    var qty = el.qty || 1;
                    var rows = '';
                    el.mats.forEach(function(m) {
                        var total = (m.calc * m.qty).toFixed(2);
                        rows += '<tr style="text-align:left">'
                            + '<td>mit <input style="width:100px" inputmode="decimal" type="text"'
                            + ' pattern="[0-9]*[.,]?[0-9]+" class="quantity-input" value="' + m.qty + '"'
                            + ' name="material_quantity[' + elementId + '][' + m.id + ']"'
                            + ' data-element-id="' + elementId + '" data-material-id="' + m.id + '"> ' + m.unit + '</td>'
                            + '<td class="col-name">' + m.name + '</td>'
                            + '<td class="col-pstk price-details" data-material-id="' + m.id + '" data-material-price="' + m.calc + '">'
                            + 'CHF <span class="price-in">' + m.calc.toFixed(2) + '</span>'
                            + ' X <span class="quantity">' + m.qty + '</span> ' + m.unit
                            + '<div class="element-materials-hidden-metrics" style="display:none" aria-hidden="true">'
                            + '<span class="material-price-out">CHF <span class="price-out">' + m.price_out + '</span></span>'
                            + '<span class="material-price-in">CHF <span class="price-in">' + m.price_in + '</span></span>'
                            + '<span class="material-zeit-cost">CHF <span class="zeit-cost">' + m.zeit_cost + '</span></span>'
                            + '<span class="material-z-total"><span class="z-total">' + (m.z_total || 0) + '</span></span>'
                            + '</div></td>'
                            + '<td class="total" data-material-id="' + m.id + '" data-element-id="' + elementId + '">' + total + '</td>'
                            + '</tr>';
                    });

                    var wrap = document.createElement('div');
                    wrap.className = 'element-materials-wrap';
                    wrap.id = 'element-materials-wrap-' + elementId;
                    wrap.style.display = 'none';
                    wrap.innerHTML = '<table class="table element-materials" id="element-materials-' + elementId + '">'
                        + '<colgroup><col style="width:14%"><col style="width:38%"><col style="width:30%"><col style="width:18%"></colgroup>'
                        + '<tbody>'
                        + '<tr style="text-align:left" class="table-dark">'
                        + '<th><input type="number" min="1" style="width:130px" class="element-quantity-input"'
                        + ' data-element-id="' + elementId + '" name="element_quantity[' + elementId + ']" value="' + qty + '"></th>'
                        + '<th class="col-name"><span class="element-summary-name">' + el.name + '</span></th>'
                        + '<th class="col-pstk total-materials-header" data-element-id="' + elementId + '">'
                        + 'CHF <span class="total-materials-value">0</span> X <span class="element-quantity">' + qty + '</span></th>'
                        + '<th class="col-total total-materials-header" data-element-id="' + elementId + '">'
                        + '<span class="total-materials-value-header">0</span></th>'
                        + '</tr>' + rows + '</tbody></table>';

                    insertWrapByLeftOrder(wrap, elementId);

                    $(wrap).find('.quantity-input').on('input', function() {
                        updateMaterial($(this));
                        updateTotalMaterialsPrice();
                        updateTotalProTypPrice();
                        triggerAutoSave();
                    });
                    $(wrap).find('.element-quantity-input').on('input', function() {
                        var eId = $(this).data('element-id');
                        $('.total-materials-header[data-element-id="' + eId + '"] .element-quantity').text($(this).val() || 1);
                        updateTotalMaterialsPrice();
                        updateTotalProTypPrice();
                    });
                }
                </script>
            </div>
        </div>
        </form>
        {{-- <a href="{{ route('offert.index') }}" class="btn btn-secondary mt-3">Back to Offert</a> --}}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const organigramToggles = document.querySelectorAll('.organigram-toggle');
            const groupElementToggles = document.querySelectorAll('.group-element-toggle');
            const elementCheckboxes = document.querySelectorAll('.element-checkbox');
            const optionalElementCheckboxes = document.querySelectorAll('.element-optional-checkbox');
            const elementNameRows = document.querySelectorAll('.element-card-title .element-name-row');
            // Initialize the running total materials price variable
            let runningTotalMaterialsPrice = 0;
            let percentage = parseFloat({{ $offert->default_rabatt ?? 0 }});
            const materialCoeff = {{ $materialCoeff ?? 1 }};
            const difficultyCoeff = {{ $difficultyCoeff ?? 1 }};
            const inLaborPrice = {{ $inLaborPrice ?? 60 }};
            // Declare mengeInput before any function that references it
            const mengeInput = document.getElementById('menge-input');

            // Update the total materials price on document ready, then recalculate totals
            updateTotalMaterialsPrice();
            updateTotalProTypPrice();

            mengeInput.addEventListener('input', function() {

                // Call the function to update the total based on the new menge value
                updateTotalProTypPrice();
            });

            elementNameRows.forEach(row => {
                row.addEventListener('click', function(event) {
                    // Let native checkbox clicks behave normally; only toggle on text row click.
                    if (event.target.closest('.element-checkbox')) {
                        return;
                    }
                    const checkbox = row.querySelector('.element-checkbox');
                    if (!checkbox || checkbox.disabled) return;
                    checkbox.checked = !checkbox.checked;
                    checkbox.dispatchEvent(new Event('change', { bubbles: true }));
                });
            });

            // Attach an event listener to the quantity input field
            $('.quantity-input').on('input', function() {
                updateMaterial($(this));
                updateTotalMaterialsPrice();
                updateTotalProTypPrice();
            });

            // Function to update material details based on the quantity input
            function updateMaterial(quantityInput) {
                var priceDetails = quantityInput.closest('tr').find('.price-details');
                var totalElement = quantityInput.closest('tr').find('.total');

                var normalizedQuantity = (quantityInput.val() || '').toString().replace(',', '.');
                var currentQuantity = parseFloat(normalizedQuantity);
                if (Number.isNaN(currentQuantity)) currentQuantity = 0;

                priceDetails.find('.quantity').text(currentQuantity);

                var priceIn = parseFloat(priceDetails.data('material-price'));
                var totalPrice = priceIn * currentQuantity;
                totalElement.text(totalPrice.toFixed(2));

            }

            $('.element-quantity-input').on('input', function() {
                updateElementQuantity($(this));
                updateTotalMaterialsPrice(); // Update materials total as well
                updateTotalProTypPrice(); // Recompute brutto from refreshed header totals
            });
            // Function to update element quantity
            function updateElementQuantity(elementQuantityInput) {
                const elementId = elementQuantityInput.data('element-id');
                const totalElement = $(`.total-materials-header[data-element-id="${elementId}"] .element-quantity`);
                const currentQuantity = parseFloat(elementQuantityInput.val());

                // Update the element quantity in the table header
                totalElement.text(currentQuantity);
            }

            // Function to handle the visibility of the element materials table
            function handleElementMaterialsTableVisibility(checkbox) {
                const elementId = checkbox.getAttribute('data-element-id');
                const elementMaterialsTable = document.querySelector(`#element-materials-wrap-${elementId}`);

                if (elementMaterialsTable) {
                    // Update the total materials price when the checkbox is clicked
                    const elementPrice = calculateTotalMaterialsPrice(elementId);

                    // Update the running total based on the checkbox state
                    if (checkbox.checked) {
                        runningTotalMaterialsPrice += elementPrice;
                    } else {
                        runningTotalMaterialsPrice -= elementPrice;
                    }

                    updateTotalProTypPrice();

                    // If the element materials table is visible, trigger the calculations
                    if (checkbox.checked && elementMaterialsTable.style.display === 'block') {
                        // Trigger calculations for the materials table
                        updateTotalMaterialsPrice();
                    }
                }
            }

            elementCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const elementId = this.getAttribute('data-element-id');

                    // Build table from JSON on first check (only runs once per element per session)
                    if (typeof buildElementTable === 'function') {
                        buildElementTable(elementId);
                    }

                    const elementMaterialsTable = document.getElementById(`element-materials-wrap-${elementId}`);
                    if (elementMaterialsTable) {
                        elementMaterialsTable.style.display = this.checked ? 'block' : 'none';
                        const elementPrice = calculateTotalMaterialsPrice(elementId);
                        if (this.checked) {
                            runningTotalMaterialsPrice += elementPrice;
                        } else {
                            runningTotalMaterialsPrice -= elementPrice;
                        }
                        updateTotalMaterialsPrice();
                        updateTotalProTypPrice();
                    }
                });
            });

            // Function to calculate the total materials price for an element
            function calculateTotalMaterialsPrice(elementId) {
                let totalMaterialsPrice = 0;
                document.querySelectorAll(`#element-materials-${elementId} tbody tr td.total`).forEach(cell => {
                    const v = parseFloat(cell.textContent.trim());
                    if (!isNaN(v)) totalMaterialsPrice += v;
                });
                return totalMaterialsPrice;
            }

            // Function to update the total materials price in the HTML
            function updateTotalMaterialsPrice() {
                $('.total-materials-header').each(function() {
                    const elementId = $(this).data('element-id');
                    const totalElement = $(this).find('.total-materials-value-header');
                    const totalElementValue = $(this).find('.total-materials-value');
                    const elementQuantityInput = $(
                        `.element-quantity-input[data-element-id="${elementId}"]`);
                    const elementQuantity = parseFloat(elementQuantityInput.val());
                    const totalMaterialsPrice = calculateTotalMaterialsPrice(elementId);

                    // Update the total in the table header
                    totalElement.text((totalMaterialsPrice * elementQuantity).toFixed(2));
                    totalElementValue.text((totalMaterialsPrice).toFixed(2));

                    // Update the price_out and zeit_cost values
                    const priceOutValue = calculateTotalPriceOut(elementId);
                    const zeitCostValue = calculateTotalZeitCost(elementId);

                    $(this).find('.total-materials-value-header-price-out').text(priceOutValue.toFixed(2));
                    $(this).find('.total-materials-value-header-zeit-cost').text(zeitCostValue.toFixed(2));
                });
            }

            function calculateTotalPriceOut(elementId) {
                const materials = document.querySelectorAll(`#element-materials-${elementId} tbody tr`);
                let totalPriceOut = 0;

                materials.forEach(materialRow => {
                    const priceOutCell = materialRow.querySelector('.material-price-out .price-out');
                    if (priceOutCell) {
                        totalPriceOut += parseFloat(priceOutCell.textContent);
                    }
                });

                return totalPriceOut;
            }

            function calculateTotalPriceIn(elementId) {
                const materials = document.querySelectorAll(`#element-materials-${elementId} tbody tr`);
                let totalPriceIn = 0;

                materials.forEach(materialRow => {
                    const priceInCell = materialRow.querySelector('.material-price-in .price-in');
                    if (priceInCell) {
                        totalPriceIn += parseFloat(priceInCell.textContent);
                    }
                });

                return totalPriceIn;
            }

            function calculateTotalZeitCost(elementId) {
                const materials = document.querySelectorAll(`#element-materials-${elementId} tbody tr`);
                let totalZeitCost = 0;

                materials.forEach(materialRow => {
                    const zeitCostCell = materialRow.querySelector('.material-zeit-cost .zeit-cost');
                    if (zeitCostCell) {
                        totalZeitCost += parseFloat(zeitCostCell.textContent);
                    }
                });

                return totalZeitCost;
            }

            function formatSwissNumber(value, decimals = 2) {
                const num = Number(value);
                if (!Number.isFinite(num)) return decimals > 0 ? `0.${'0'.repeat(decimals)}` : '0';
                const fixed = num.toFixed(decimals);
                const parts = fixed.split('.');
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, "'");
                return decimals > 0 ? `${parts[0]}.${parts[1]}` : parts[0];
            }

            function parseSwissNumber(value) {
                const normalized = String(value ?? '')
                    .replace(/'/g, '')
                    .replace(',', '.')
                    .trim();
                const parsed = parseFloat(normalized);
                return Number.isFinite(parsed) ? parsed : 0;
            }

            // Event listener for the percentage input field
            const percentageInput = document.getElementById('percentage-input');
            percentageInput.addEventListener('input', function() {
                // Get the value from percentage-input
                const inputValue = this.value;

                // Update the value of percentage-input2
                const percentageInput2 = document.getElementById('percentage-input2');
                percentageInput2.value = inputValue;

                // Call the function to update the total based on the new percentage
                updateTotalProTypPrice();

                // Update the hidden input field with the calculated percentage value
                const percentageInputHidden = document.getElementById('percentageInput');
                percentageInputHidden.value = inputValue;
            });

            const rabattDefaultBtn = document.getElementById('rabatt-default-btn');
            if (rabattDefaultBtn) {
                rabattDefaultBtn.addEventListener('click', function() {
                    const defaultRabatt = parseFloat({{ $offert->default_rabatt ?? 0 }}) || 0;
                    const defaultValue = defaultRabatt.toString();
                    const percentageInput2 = document.getElementById('percentage-input2');
                    const percentageInputHidden = document.getElementById('percentageInput');

                    percentageInput.value = defaultValue;
                    if (percentageInput2) percentageInput2.value = defaultValue;
                    if (percentageInputHidden) percentageInputHidden.value = defaultValue;

                    updateTotalProTypPrice();
                    if (typeof triggerAutoSave === 'function') {
                        triggerAutoSave();
                    }
                });
            }

            // Function to update the Total Pro Typ Price and Discounted Total based on the running total and percentage
            function updateTotalProTypPrice() {
                const totalProTypPriceCell = document.getElementById('total-pro-typ-price');
                const totalProTypPriceCell2 = document.getElementById('total-pro-typ-price2');
                const discountedTotalCell = document.getElementById('discounted-total');
                const discountedTotalCell2 = document.getElementById('discounted-total2');
                const percentageInput = document.getElementById('percentage-input');
                const percentageInput2 = document.getElementById('percentage-input2');
                const totalProTypPriceInput = document.getElementById('totalProTypPriceInput');
                const discountedTotalInput = document.getElementById('discountedTotalInput');

                // Add the following lines to fetch price_out and zeit_cost values
                const priceOutInput = document.getElementById('price-out-input');
                const priceOutInput2 = document.getElementById('price-out-input2');
                const zeitCostInput = document.getElementById('zeit-cost-input');
                const zeitCostInput2 = document.getElementById('zeit-cost-input2');
                const priceInInput = document.getElementById('price-in-input');
                const priceProfit = document.getElementById('price-profit');
                const zeitCosto = document.getElementById('zeit-costo');
                const zeitProfit = document.getElementById('zeit-profit');
                const costoTotal = document.getElementById('costo-total');
                const profitTotal = document.getElementById('profit-total');
                const costoTotal2 = document.getElementById('costo-total2');
                const profitTotal2 = document.getElementById('profit-total2');

                if (totalProTypPriceCell && discountedTotalCell && percentageInput &&
                    totalProTypPriceCell2 && discountedTotalCell2 && percentageInput2) {
                    let totalProTypPrice = 0;
                    let totalPriceOut = 0;
                    let totalZeitCost = 0;
                    let totalPriceIn = 0;
                    let totalZHours = 0;

                    // Loop through all element checkboxes
                    elementCheckboxes.forEach(checkbox => {
                        const elementId = checkbox.getAttribute('data-element-id');
                        const elementMaterialsTable = document.querySelector(
                            `#element-materials-${elementId}`);

                        if (elementMaterialsTable && checkbox.checked) {
                            const optionalCheckbox = document.querySelector(
                                `.element-optional-checkbox[data-element-id="${elementId}"]`
                            );
                            const isElementOptional = optionalCheckbox && optionalCheckbox.checked;
                            if (isElementOptional) {
                                return;
                            }
                            const elementHeaderTotalNode = document.querySelector(
                                `.total-materials-header[data-element-id="${elementId}"] .total-materials-value-header`
                            );
                            const elementTotalProTypPrice = elementHeaderTotalNode
                                ? parseSwissNumber(elementHeaderTotalNode.textContent)
                                : 0;
                            totalProTypPrice += elementTotalProTypPrice;

                            const elementQuantityInput = $(
                                `.element-quantity-input[data-element-id="${elementId}"]`);
                            const elementQuantity = parseFloat(elementQuantityInput.val()) || 0;

                            // Fetch and accumulate price_out, zeit_cost, and z_total values
                            const materials = document.querySelectorAll(
                                `#element-materials-${elementId} tbody tr`);
                            materials.forEach(materialRow => {
                                const priceOutCell = materialRow.querySelector(
                                    '.material-price-out .price-out');
                                const priceInCell = materialRow.querySelector(
                                    '.material-price-in .price-in');
                                const zeitCostCell = materialRow.querySelector(
                                    '.material-zeit-cost .zeit-cost');
                                const zTotalCell = materialRow.querySelector(
                                    '.material-z-total .z-total');
                                const quantityInput = materialRow.querySelector('.quantity-input');

                                if (priceOutCell && priceInCell && zeitCostCell && quantityInput) {
                                    const priceOutValue = parseFloat(priceOutCell.textContent);
                                    const priceInValue = parseFloat(priceInCell.textContent);
                                    const zeitCostValue = parseFloat(zeitCostCell.textContent);
                                    const zTotalValue = zTotalCell ? parseFloat(zTotalCell.textContent) : 0;
                                    const quantityValue = parseFloat(quantityInput.value) || 0;

                                    totalPriceOut += priceOutValue * quantityValue *
                                        elementQuantity;
                                    totalPriceIn += priceInValue * quantityValue *
                                        elementQuantity;
                                    totalZeitCost += zeitCostValue * quantityValue *
                                        elementQuantity;
                                    totalZHours += zTotalValue * quantityValue *
                                        elementQuantity;
                                }
                            });
                        }
                    });
                    const mengeValue = parseFloat(mengeInput.value) || 1;

                    const percentage = parseFloat(percentageInput.value) || 0;
                    const discountedTotal = totalProTypPrice * (1 - (percentage / 100)) * mengeValue;

                    totalProTypPriceCell.textContent = formatSwissNumber(totalProTypPrice * mengeValue);
                    totalProTypPriceCell2.textContent = formatSwissNumber(totalProTypPrice * mengeValue);
                    discountedTotalCell.textContent = formatSwissNumber(discountedTotal);
                    discountedTotalCell2.textContent = formatSwissNumber(discountedTotal);

                    // Update the hidden input fields with the calculated values
                    totalProTypPriceInput.value = totalProTypPrice.toFixed(2);
                    discountedTotalInput.value = discountedTotal.toFixed(2);
                    document.getElementById('percentageInput').value = percentageInput.value;

                    // Update the displayed price_out and zeit_cost values
                    priceOutInput.textContent = formatSwissNumber(totalPriceOut);
                    priceOutInput2.textContent = formatSwissNumber(totalPriceOut);

                    const laborKosto = difficultyCoeff > 0 ? totalZHours * inLaborPrice / difficultyCoeff : 0;

                    zeitCostInput.textContent = formatSwissNumber(totalZeitCost);
                    zeitCostInput2.textContent = formatSwissNumber(totalZeitCost);
                    zeitCosto.textContent = formatSwissNumber(laborKosto);
                    zeitProfit.textContent = formatSwissNumber(totalZeitCost - laborKosto);

                    priceInInput.textContent = formatSwissNumber(totalPriceIn);
                    priceProfit.textContent = formatSwissNumber(totalPriceOut - totalPriceIn);

                    const costoTotalValue = (totalPriceIn * materialCoeff + laborKosto).toFixed(2);
                    costoTotal.textContent = formatSwissNumber(costoTotalValue);

                    const profitTotalValue = (discountedTotal - parseFloat(costoTotalValue)).toFixed(2);
                    profitTotal.textContent = formatSwissNumber(profitTotalValue);

                    const costoTotalValue2 = costoTotalValue;
                    costoTotal2.textContent = formatSwissNumber(costoTotalValue2);

                    profitTotal2.textContent = formatSwissNumber(profitTotalValue);

                    // Update the hidden input values
                    document.getElementById('priceOutInput').value = totalPriceOut.toFixed(2);

                    document.getElementById('zeitCostInput').value = totalZeitCost.toFixed(2);
                    document.getElementById('zeitCosto').value = laborKosto.toFixed(2);
                    document.getElementById('zeitProfit').value = (totalZeitCost - laborKosto).toFixed(2);

                    document.getElementById('priceInInput').value = totalPriceIn.toFixed(2);
                    document.getElementById('priceProfit').value = (totalPriceOut - totalPriceIn).toFixed(2);

                    document.getElementById('costoTotal').value = costoTotalValue;
                    document.getElementById('profitTotal').value = profitTotalValue;

                    // Update the hidden input fields
                    totalProTypPriceInput.value = (totalProTypPrice * mengeValue).toFixed(2);
                    discountedTotalInput.value = discountedTotal.toFixed(2);
                }
            }

            initializePositionElementSelection(organigramToggles, groupElementToggles);

            function applyOptionalElementVisualState(elementId) {
                const optionalCheckbox = document.querySelector(`.element-optional-checkbox[data-element-id="${elementId}"]`);
                const isOptional = optionalCheckbox && optionalCheckbox.checked;
                const title = document.querySelector(`.element-card-title[data-element-id="${elementId}"]`);
                const table = document.getElementById(`element-materials-${elementId}`);

                if (title) title.classList.toggle('optional-element-muted', !!isOptional);
                if (table) table.classList.toggle('optional-element-muted', !!isOptional);
            }

            optionalElementCheckboxes.forEach(cb => applyOptionalElementVisualState(cb.dataset.elementId));

            // Auto-save functionality for current position
            let autoSaveTimeout;
            let currentPositionId = null;
            const autoSaveDelay = 1500; // 1.5s debounce — prevents a save on every keystroke

            function triggerAutoSave() {
                clearTimeout(autoSaveTimeout);
                autoSaveTimeout = setTimeout(() => {
                    autoSaveCurrentPosition();
                }, autoSaveDelay);
            }

            // Listen to all checkbox changes
            document.querySelectorAll('.element-checkbox, .element-optional-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    if (this.classList.contains('element-optional-checkbox')) {
                        applyOptionalElementVisualState(this.dataset.elementId);
                    }
                    updateTotalProTypPrice();
                    triggerAutoSave();
                });
            });

            // Listen to quantity and other input changes
            document.querySelectorAll('.quantity-input, .element-quantity-input, #description, textarea[name="description2"], #blocktype, #b, #h, #t, #menge-input, #percentageInput').forEach(input => {
                input.addEventListener('input', function() {
                    updateTotalProTypPrice();
                    triggerAutoSave();
                });
                input.addEventListener('change', function() {
                    updateTotalProTypPrice();
                    triggerAutoSave();
                });
            });

            function autoSaveCurrentPosition() {
                const currentIndex = parseInt(document.getElementById('index').value || '0', 10);
                const formData = collectFormData(currentIndex);
                savePositionForType(formData, currentIndex, true);
            }

            function collectFormData(typeIndex) {
                const form = document.getElementById('createPositionForm');
                const formData = new FormData(form);

                // Collect selected values
                const selectedElements = Array.from(document.querySelectorAll('.element-checkbox:checked'))
                    .map(cb => cb.value);
                const selectedGroupElements = [...new Set(Array.from(document.querySelectorAll('.element-checkbox:checked'))
                    .map(cb => cb.dataset.groupElementId)
                    .filter(Boolean))];
                const selectedOrganigrams = [...new Set(Array.from(document.querySelectorAll('.element-checkbox:checked'))
                    .map(cb => cb.dataset.organigramId)
                    .filter(Boolean))];
                const elementOptional = {};
                selectedElements.forEach(elementId => {
                    const optionalCheckbox = document.querySelector(`.element-optional-checkbox[data-element-id="${elementId}"]`);
                    elementOptional[elementId] = optionalCheckbox && optionalCheckbox.checked ? 1 : 0;
                });

                // Collect element quantities
                const elementQuantities = {};
                selectedElements.forEach(elementId => {
                    const quantityInput = document.querySelector(`.element-quantity-input[data-element-id="${elementId}"]`);
                    if (quantityInput) {
                        elementQuantities[elementId] = quantityInput.value || 1;
                    }
                });

                // Collect material quantities
                const materialQuantities = {};
                document.querySelectorAll('.quantity-input').forEach(input => {
                    let elementId = input.dataset.elementId;
                    let materialId = input.dataset.materialId;
                    if ((!elementId || !materialId) && input.name) {
                        const match = input.name.match(/material_quantity\[(\d+)\]\[(\d+)\]/);
                        if (match) {
                            elementId = match[1];
                            materialId = match[2];
                        }
                    }
                    if (!elementId || !materialId) return;
                    if (!materialQuantities[elementId]) {
                        materialQuantities[elementId] = {};
                    }
                    materialQuantities[elementId][materialId] = input.value;
                });

                return {
                    index: typeIndex,
                    description: document.getElementById('description').value,
                    description2: document.querySelector('textarea[name="description2"]').value,
                    blocktype: document.getElementById('blocktype').value,
                    b: document.getElementById('b').value,
                    h: document.getElementById('h').value,
                    t: document.getElementById('t').value,
                    selected_elements: selectedElements,
                    selected_group_elements: selectedGroupElements,
                    selected_organigrams: selectedOrganigrams,
                    element_quantity: elementQuantities,
                    element_optional: elementOptional,
                    material_quantity: materialQuantities,
                    quantity: document.getElementById('menge-input').value || 1,
                    totalProTypPrice: document.getElementById('totalProTypPriceInput').value || 0,
                    discountedTotal: document.getElementById('discountedTotalInput').value || 0,
                    percentage: document.getElementById('percentageInput').value || 0,
                    price_out: document.getElementById('priceOutInput').value || 0,
                    zeit_cost: document.getElementById('zeitCostInput').value || 0,
                    material_costo: document.getElementById('priceInInput').value || 0,
                    material_profit: document.getElementById('priceProfit').value || 0,
                    zeit_costo: document.getElementById('zeitCosto').value || 0,
                    zeit_profit: document.getElementById('zeitProfit').value || 0,
                    costo_total: document.getElementById('costoTotal').value || 0,
                    profit_total: document.getElementById('profitTotal').value || 0,
                    auto_save: 1
                };
            }

            function savePositionForType(formData, typeIndex, isLast) {
                fetch('{{ route("position.auto-save") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ||
                                      document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({
                        ...formData,
                        position_id: currentPositionId,
                        offert_id: document.getElementById('offert_id').value
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data && data.success && data.position_id) {
                        currentPositionId = parseInt(data.position_id, 10) || currentPositionId;
                    }
                    if (!data.success) {
                        console.warn('Auto-save warning:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Auto-save error:', error);
                });
            }

            function persistPositionBeforeLeave() {
                if (window._autoSaveLock) return;
                window._autoSaveLock = true;
                const currentIndex = parseInt(document.getElementById('index').value || '0', 10);
                const formData = collectFormData(currentIndex);
                fetch('{{ route("position.auto-save") }}', {
                    method: 'POST',
                    keepalive: true,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ||
                                      document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({
                        ...formData,
                        position_id: currentPositionId,
                        offert_id: document.getElementById('offert_id').value
                    })
                }).catch(() => {});
            }

            // Expose auto-save-and-navigate for the "New Position" button
            window.doAutoSaveAndNavigate = function(nextUrl, callback) {
                // Guard: prevent double-invocation (double-click or visibilitychange race)
                if (window._autoSaveLock) return;
                window._autoSaveLock = true;
                // Cancel any pending auto-save timer
                clearTimeout(autoSaveTimeout);

                const currentIndex = parseInt(document.getElementById('index').value || '0', 10);
                const formData = collectFormData(currentIndex);
                const offertId = document.getElementById('offert_id').value;

                // Fire-and-forget with keepalive — navigate immediately, don't wait for the server
                fetch('{{ route("position.auto-save") }}', {
                    method: 'POST',
                    keepalive: true,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ||
                                      document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({ ...formData, position_id: currentPositionId, offert_id: offertId })
                }).catch(() => {});
                if (typeof callback === 'function') {
                    callback();
                } else {
                    window.location.href = nextUrl;
                }
            };

            window.openExternalPdfAfterSave = function(pdfUrl) {
                clearTimeout(autoSaveTimeout);
                const currentIndex = parseInt(document.getElementById('index').value || '0', 10);
                const formData = collectFormData(currentIndex);
                const offertId = document.getElementById('offert_id').value;
                fetch('{{ route("position.auto-save") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ||
                                      document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({ ...formData, position_id: currentPositionId, offert_id: offertId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data && data.success && data.position_id) {
                        currentPositionId = parseInt(data.position_id, 10) || currentPositionId;
                    }
                    window.open(pdfUrl, '_blank', 'noopener,noreferrer');
                })
                .catch(error => {
                    console.error('Save before PDF error:', error);
                    window.open(pdfUrl, '_blank', 'noopener,noreferrer');
                });
            };

            document.addEventListener('visibilitychange', function() {
                if (document.visibilityState === 'hidden') {
                    persistPositionBeforeLeave();
                }
            });

            window.addEventListener('pagehide', function() {
                persistPositionBeforeLeave();
            });

            // Expose local functions needed by buildElementTable (defined in outer script scope)
            window.updateMaterial = updateMaterial;
            window.updateTotalMaterialsPrice = updateTotalMaterialsPrice;
            window.updateTotalProTypPrice = updateTotalProTypPrice;
            window.triggerAutoSave = triggerAutoSave;

        });
        // Toggle sublinks handled by sidebar partial
    </script>
    <script>
    (function () {
        var el = document.getElementById('offert_id');
        if (!el || !el.value) return;
        var offertId = el.value;
        var csrf = (document.querySelector('meta[name="csrf-token"]') || {}).content
                || (document.querySelector('input[name="_token"]') || {}).value || '';

        function acquireLock() {
            fetch('/offert/' + offertId + '/lock', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: '{}'
            }).catch(function () {});
        }

        acquireLock();
        var heartbeat = setInterval(acquireLock, 60000);

        window.addEventListener('pagehide', function () {
            clearInterval(heartbeat);
            var fd = new FormData();
            fd.append('_token', csrf);
            navigator.sendBeacon('/offert/' + offertId + '/unlock', fd);
        });
    })();
    </script>
</body>

</html>
