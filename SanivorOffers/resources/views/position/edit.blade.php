<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update</title>
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

        .type-btn {
            margin: 1px;
            border-radius: 6px !important;
            font-size: 11px !important;
            padding: 2px 8px !important;
            font-weight: 600 !important;
        }

        .pos-list-container {
            max-height: 200px;
            overflow-y: auto;
            padding: 1px 3px;
        }

        .pos-list-container a {
            padding: 1px 3px !important;
            font-size: 12px !important;
        }

        .pos-list-container .btn {
            padding: 1px 5px !important;
            font-size: 10px !important;
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

        /* Sub-row material totals: inset from the right vs. element header total */
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
        'offertId' => $offertId,
        'currentPositionId' => $position->id,
        'nextCreateIndex' => (int) $positions->count(),
        'showSaveButton' => false,
        'saveFormId' => null,
    ])
    @include('position.partials.element-selection-js')
    <div class="content">
        <div class="row">
            <div class="col-12">
                <form id="updatePositionForm" method="POST" action="{{ route('position.update', $position->id) }}">
                    @csrf
                    <input type="hidden" name="index" id="index" value="{{ max(0, (int) $position->position_number - 1) }}">
                    <input type="hidden" name="offert_id" id="offert_id" value="{{ $offertId }}">
                    @method('PUT')
                    <input type="hidden" name="totalProTypPrice" id="totalProTypPriceInput"
                        value="{{ $position->price_brutto }}">
                    <input type="hidden" name="discountedTotal" id="discountedTotalInput"
                        value="{{ $position->price_discount }}">
                    <input type="hidden" name="percentage" id="percentageInput" value="{{ $position->discount }}">
                    <input type="hidden" name="price-out-input" id="priceOutInput"
                        value="{{ $position->material_brutto }}">
                    <input type="hidden" name="zeit-cost-input" id="zeitCostInput"
                        value="{{ $position->zeit_brutto }}">
                    <input type="hidden" name="material-costo" id="priceInInput"
                        value="{{ $position->material_costo }}">
                    <input type="hidden" name="material-profit" id="priceProfit"
                        value="{{ $position->material_profit }}">
                    <input type="hidden" name="zeit-costo" id="zeitCosto" value="{{ $position->ziet_costo }}">
                    <input type="hidden" name="zeit-profit" id="zeitProfit" value="{{ $position->ziet_profit }}">
                    <input type="hidden" name="costo-total" id="costoTotal" value="{{ $position->costo_total }}">
                    <input type="hidden" name="profit-total" id="profitTotal"
                        value="{{ $position->profit_total }}">
                    <table class="table">
                        <thead>
                            <tr class="table-dark">
                                <th scope="col">Rahmen <input style="width: 150px"
                                        value="Pos. {{ $position->position_number }}" disabled> mm </th>
                                <th scope="col"> Desc. <input id="description" name="description"
                                        value="{{ $position->description }}"> </th>
                                <th>
                                    Blocktyp <select name="blocktype" id="blocktype">
                                        <option value="" @if (is_null($position->blocktype)) selected @endif> -
                                        </option>
                                        <option value="Vorwand-Raumhoch"
                                            @if ($position->blocktype == 'Vorwand-Raumhoch') selected @endif>Vorwand-Raumhoch</option>
                                        <option value="Vorwand-Raumhoch und Teilhoch"
                                            @if ($position->blocktype == 'Vorwand-Raumhoch und Teilhoch') selected @endif>Vorwand-Raumhoch und
                                            Teilhoch</option>
                                        <option value="Vorwand-Teilhoch"
                                            @if ($position->blocktype == 'Vorwand-Teilhoch') selected @endif>Vorwand-Teilhoch</option>
                                        <option value="Freistehend-Raumhoch"
                                            @if ($position->blocktype == 'Freistehend-Raumhoch') selected @endif>Freistehend-Raumhoch
                                        </option>
                                        <option value="Vorwand-Freistehend"
                                            @if ($position->blocktype == 'Vorwand-Freistehend') selected @endif>Vorwand-Freistehend
                                        </option>
                                        <option value="Freistehend-Teilhoch"
                                            @if ($position->blocktype == 'Freistehend-Teilhoch') selected @endif>Freistehend-Teilhoch
                                        </option>
                                        <option value="Vorwand DeBO-System"
                                            @if ($position->blocktype == 'Vorwand DeBO-System') selected @endif>Vorwand DeBO-System
                                        </option>
                                        <option value="Trennwand DeBO-System"
                                            @if ($position->blocktype == 'Trennwand DeBO-System') selected @endif>Trennwand DeBO-System
                                        </option>
                                    </select>
                                </th>
                                <th scope="col"> B <input id="b" style="width: 150px" name="b"
                                        value="{{ $position->b }}"> cm </th>
                                <th scope="col"> H <input id="h" style="width: 150px" name="h"
                                        value="{{ $position->h }}"> cm </th>
                                <th scope="col"> T <input id="t" style="width: 150px" name="t"
                                        value="{{ $position->t }}"> cm </th>
                            </tr>
                            <tr class="table-dark">
                                <thead>
                                    <thead>
                                        <tr class="table-dark">
                                            <th></th>
                                            <th scope="col">Preis Brutto </th>
                                            <th scope="col">Preis mit Rabbat</th>
                                            <th scope="col">Rabbat</th>
                                            <th scope="col">Kosto CHF</th>
                                            <th scope="col">Profit CHF</th>
                                        </tr>
                                    </thead>
                                <tbody>
                                    <tr class="table-active" style="display:none;">
                                        <td><strong>Materiale Pro Typ</strong></td>
                                        <td id="price-out-input">{{ $position->material_brutto }}</td>
                                        <td id="price-out-input2">{{ $position->material_brutto }}</td>
                                        <td>% <input value="0"></td>
                                        <td id="price-in-input">{{ $position->material_costo }}</td>
                                        <td id="price-profit">{{ $position->material_profit }}</td>
                                    </tr>
                                    <tr class="table-active" style="display:none;">
                                        <td><strong>Zeit Pro Typ</strong></td>
                                        <td id="zeit-cost-input">{{ $position->zeit_brutto }}</td>
                                        <td id="zeit-cost-input2">{{ $position->zeit_brutto }}</td>
                                        <td>% <input value="0"></td>
                                        <td id="zeit-costo">{{ $position->ziet_costo }}</td>
                                        <td id="zeit-profit">{{ $position->ziet_profit }}</td>
                                    </tr>
                                    <tr class="table-secondary">
                                        <td><strong>Total Pro Typ</strong></td>
                                        <td id="total-pro-typ-price2">{{ $position->price_brutto }}</td>
                                        <td id="discounted-total2">{{ $position->price_discount }}</td>
                                        <td>% <input id="percentage-input2" disabled
                                                value="{{ $position->discount }}">
                                        </td>
                                        <td id="costo-total">{{ $position->costo_total }}</td>
                                        <td id="profit-total">{{ $position->profit_total }}</td>
                                    </tr>
                                    <tr style="font-weight:700;color:black" class="table-dark">
                                        <td>Menge <input id="menge-input" type="number" name="quantity"
                                                value="{{ $position->quantity }}" min="1">
                                        </td>
                                        <td id="total-pro-typ-price" name="total-pro-typ-price">
                                            {{ $position->price_brutto }}</td>
                                        <td id="discounted-total">{{ $position->price_discount }}</td>
                                        <td>% <input id="percentage-input" name="percentage-input"
                                                value="{{ $position->discount }}"
                                                style="{{ (float)$position->discount !== (float)($offert->default_rabatt ?? 0) ? 'color:red;font-weight:bold;' : '' }}">
                                            <button type="button" id="rabatt-default-btn"
                                                class="btn btn-sm btn-outline-light" style="margin-left:6px;padding:1px 6px;">
                                                Default
                                            </button>
                                        </td>
                                        <td id="costo-total2">{{ $position->costo_total }}</td>
                                        <td id="profit-total2">{{ $position->profit_total }}</td>
                                    </tr>
                                </tbody>
                        </thead>
                    </table>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body" style="padding:4px;">
                        @foreach ($organigrams as $organigram)
                            <h5 class="card-title organigram-toggle" style="padding:2px 4px;margin:2px 0;cursor:pointer;">
                                <i class="fa-solid fa-chevron-right" style="font-size:10px;margin-right:6px;"></i>{{ $organigram->name }}
                            </h5>
                            <div class="group-elements">
                                @foreach ($organigram->group_elements as $group_element)
                                    <div class="card mb-1" style="padding:1px;margin-bottom:1px;">
                                        <div class="card-body" style="padding:4px;">
                                            <h6 class="card-subtitle mb-2 group-element-toggle" style="padding:2px 4px;margin:2px 0;cursor:pointer;">
                                                <i class="fa-solid fa-chevron-right" style="font-size:9px;margin-right:6px;"></i>{{ $group_element->name }}
                                            </h6>
                                            <div class="elements">
                                                @foreach ($group_element->elements as $element)
                                                    @php
                                                        $elPos = $position->elements->firstWhere('id', $element->id);
                                                        $elOptionalSaved = $elPos ? (bool) ($elPos->pivot->is_optional ?? false) : false;
                                                    @endphp
                                                    <div class="card" style="padding:1px;margin-bottom:1px;">
                                                        <div class="card-body" style="padding:4px;">
                                                            <h6 class="card-subtitle element-card-title" data-element-id="{{ $element->id }}" style="margin:0;">
                                                                <span class="element-name-row">
                                                                    <input type="checkbox" name="selected_elements[]"
                                                                        class="element-checkbox"
                                                                        data-element-id="{{ $element->id }}"
                                                                        data-group-element-id="{{ $group_element->id }}"
                                                                        data-organigram-id="{{ $organigram->id }}"
                                                                        value="{{ $element->id }}"
                                                                        {{ $position->elements->contains($element->id) ? 'checked' : '' }}>
                                                                    <span>{{ $element->name }}</span>
                                                                </span>
                                                                <label class="element-optional-inline mb-0">
                                                                    <input type="checkbox" class="element-optional-checkbox"
                                                                        name="element_optional[{{ $element->id }}]" value="1"
                                                                        data-element-id="{{ $element->id }}"
                                                                        {{ $elOptionalSaved ? 'checked' : '' }}>
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
                    <textarea name="description2" rows="3" style="padding:4px;margin-top:6px;">{{ $position->description2 }}</textarea>
                </div>
            </div>
            <div class="col-md-9 position position-materials-panel">
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
                @php
                    $matCoeff  = max(floatval($offert->material  ?? 1.3), 0.001);
                    $diffCoeff = max(floatval($offert->difficulty ?? 0.7), 0.001);
                @endphp
                @foreach ($elements as $element)
                    @php
                        $isSelected    = $position->elements->contains($element->id);
                        $pivotQuantity = $element->positions->first()->pivot->quantity ?? 1;
                    @endphp
                    <div class="element-materials-wrap" id="element-materials-wrap-{{ $element->id }}" style="display: {{ $isSelected ? 'block' : 'none' }};">
                    <table class="table element-materials" id="element-materials-{{ $element->id }}">
                        <colgroup>
                            <col style="width:14%">
                            <col style="width:38%">
                            <col style="width:30%">
                            <col style="width:18%">
                        </colgroup>
                        <tbody>
                            <tr style="text-align: left" class="table-dark">
                                <th scope="col">
                                    <input type="number" min="1" style="width: 130px"
                                        class="element-quantity-input" data-element-id="{{ $element->id }}"
                                        name="element_quantity[{{ $element->id }}]" value="{{ $pivotQuantity }}">
                                </th>
                                <th scope="col" class="col-name">
                                    <span class="element-summary-name">{{ $element->name }}</span>
                                </th>
                                <th scope="col" class="col-pstk total-materials-header"
                                    data-element-id="{{ $element->id }}">
                                    CHF <span class="total-materials-value">0</span> X
                                    <span class="element-quantity">{{ $pivotQuantity }}</span>
                                </th>
                                <th scope="col" class="col-total total-materials-header"
                                    data-element-id="{{ $element->id }}">
                                    <span class="total-materials-value-header">0</span>
                                </th>
                            </tr>

                            @foreach ($element->materials as $material)
                                @php
                                    $positionMaterial = $positionMaterials
                                        ->where('element_id', $element->id)
                                        ->where('material_id', $material->id)
                                        ->first();
                                    $quantity = $positionMaterial ? $positionMaterial->quantity : $material->pivot->quantity;
                                    // preis = price_out × mat_coeff + total_arbeit / difficulty_coeff
                                    $computedPrice = ($material->price_out * $matCoeff)
                                        + (($material->total_arbeit ?? 0) / $diffCoeff);
                                @endphp
                                <tr style="text-align: left">
                                    <td>
                                        mit <input style="width: 100px" min="0" step="any" type="number"
                                            class="quantity-input" value="{{ $quantity }}"
                                            name="material_quantity[{{ $element->id }}][{{ $material->id }}]"
                                            data-element-id="{{ $element->id }}"
                                            data-material-id="{{ $material->id }}"> {{ $material->unit }}
                                    </td>
                                    <td class="col-name">
                                        {{ $material->name }}
                                    </td>
                                    <td class="col-pstk price-details"
                                        data-material-id="{{ $material->id }}"
                                        data-material-price="{{ number_format($computedPrice, 4, '.', '') }}">
                                        CHF <span class="price-in">{{ number_format($computedPrice, 2, '.', '') }}</span> X <span
                                            class="quantity">{{ $quantity }}</span>
                                        {{ $material->unit }}
                                        <div class="element-materials-hidden-metrics" style="display:none" aria-hidden="true">
                                            <span class="material-price-out">CHF <span class="price-out">{{ $material->price_out }}</span></span>
                                            <span class="material-price-in">CHF <span class="price-in">{{ $material->price_in }}</span></span>
                                            <span class="material-zeit-cost">CHF <span class="zeit-cost">{{ $material->zeit_cost }}</span></span>
                                        </div>
                                    </td>
                                    <td class="total" data-material-id="{{ $material->id }}"
                                        data-element-id="{{ $element->id }}">
                                        {{ number_format($computedPrice * $quantity, 2, '.', '') }}
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                    </div>
                @endforeach
            </div>
        </div>
        </form>
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
            // Initialize the running total materials price variable
            let runningTotalMaterialsPrice = 0;
            let percentage = 0;
            // Update the total materials price on document ready
            updateTotalMaterialsPrice();
            // Recalculate totals on load so hidden inputs are correct before any save
            updateTotalProTypPrice();
            // Add an event listener to the menge-input element
            const mengeInput = document.getElementById('menge-input');

            mengeInput.addEventListener('input', function() {

                // Call the function to update the total based on the new menge value
                updateTotalProTypPrice();
            });

            // Attach an event listener to the quantity input field
            $('.quantity-input').on('input', function() {
                updateMaterial($(this));
                updateTotalMaterialsPrice();
            });

            // Function to update material details based on the quantity input
            function updateMaterial(quantityInput) {
                // Get the related elements
                var priceDetails = quantityInput.closest('tr').find('.price-details');
                var totalElement = quantityInput.closest('tr').find('.total');

                // Get the current quantity value
                var currentQuantity = parseFloat(quantityInput.val());

                // Check if the quantity has changed
                if (quantityInput.data('currentQuantity') !== currentQuantity) {
                    // Update the quantity in the price details
                    priceDetails.find('.quantity').text(currentQuantity);

                    // Update the total based on the new quantity
                    var priceIn = parseFloat(priceDetails.data('material-price'));
                    var totalPrice = priceIn * currentQuantity;

                    totalElement.text(totalPrice.toFixed(2));

                    // Update the currentQuantity data attribute
                    quantityInput.data('currentQuantity', currentQuantity);
                    updateTotalProTypPrice();
                }
            }

            $('.element-quantity-input').on('input', function() {
                updateElementQuantity($(this));
                updateTotalProTypPrice(); // Update total immediately
                updateTotalMaterialsPrice(); // Update materials total as well
            });
            // Function to update element quantity
            function updateElementQuantity(elementQuantityInput) {
                const elementId = elementQuantityInput.data('element-id');
                const totalElement = $(`.total-materials-header[data-element-id="${elementId}"] .element-quantity`);
                const currentQuantity = parseFloat(elementQuantityInput.val());

                // Update the element quantity in the table header
                totalElement.text(currentQuantity);
            }

            elementCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const elementId = this.getAttribute('data-element-id');
                    const elementMaterialsTable = document.querySelector(
                        `#element-materials-wrap-${elementId}`);

                    if (elementMaterialsTable) {
                        elementMaterialsTable.style.display = this.checked ? 'block' : 'none';

                        // Calculate the total materials price when the checkbox is clicked
                        const elementPrice = calculateTotalMaterialsPrice(elementId);

                        // Update the running total based on the checkbox state
                        if (this.checked) {
                            runningTotalMaterialsPrice += elementPrice;
                        } else {
                            runningTotalMaterialsPrice -= elementPrice;
                        }

                        updateTotalProTypPrice();
                    }
                });
            });
            optionalElementCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    applyOptionalElementVisualState(this.dataset.elementId);
                    updateTotalProTypPrice();
                });
            });

            function applyOptionalElementVisualState(elementId) {
                const optionalCheckbox = document.querySelector(`.element-optional-checkbox[data-element-id="${elementId}"]`);
                const isOptional = optionalCheckbox && optionalCheckbox.checked;
                const title = document.querySelector(`.element-card-title[data-element-id="${elementId}"]`);
                const table = document.getElementById(`element-materials-${elementId}`);

                if (title) title.classList.toggle('optional-element-muted', !!isOptional);
                if (table) table.classList.toggle('optional-element-muted', !!isOptional);
            }
            optionalElementCheckboxes.forEach(cb => applyOptionalElementVisualState(cb.dataset.elementId));

            // Function to calculate the total materials price for an element
            function calculateTotalMaterialsPrice(elementId) {
                const materials = document.querySelectorAll(`#element-materials-${elementId} tbody tr`);
                let totalMaterialsPrice = 0;

                materials.forEach(materialRow => {
                    const priceCell = materialRow.querySelector('td:last-child');
                    if (priceCell) {
                        totalMaterialsPrice += parseFloat(priceCell.textContent);
                    }
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

            // Default rabatt from offert header
            const defaultRabatt = parseFloat({{ $offert->default_rabatt ?? 0 }});

            // Colour the % input red if it differs from the offert default
            function updateRabattColour(input) {
                const val = parseFloat(input.value) || 0;
                if (val !== defaultRabatt) {
                    input.style.color = 'red';
                    input.style.fontWeight = 'bold';
                } else {
                    input.style.color = '';
                    input.style.fontWeight = '';
                }
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

                // Update colour indicator
                updateRabattColour(this);
            });

            const rabattDefaultBtn = document.getElementById('rabatt-default-btn');
            if (rabattDefaultBtn) {
                rabattDefaultBtn.addEventListener('click', function() {
                    const defaultValue = defaultRabatt.toString();
                    const percentageInput2 = document.getElementById('percentage-input2');
                    const percentageInputHidden = document.getElementById('percentageInput');

                    percentageInput.value = defaultValue;
                    if (percentageInput2) percentageInput2.value = defaultValue;
                    if (percentageInputHidden) percentageInputHidden.value = defaultValue;

                    updateRabattColour(percentageInput);
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

                    // Loop through all element checkboxes
                    elementCheckboxes.forEach(checkbox => {
                        const elementId = checkbox.getAttribute('data-element-id');
                        const elementMaterialsTable = document.querySelector(
                            `#element-materials-wrap-${elementId}`);

                        if (elementMaterialsTable && checkbox.checked) {
                            const optionalCheckbox = document.querySelector(
                                `.element-optional-checkbox[data-element-id="${elementId}"]`
                            );
                            const isElementOptional = optionalCheckbox && optionalCheckbox.checked;
                            if (isElementOptional) {
                                return;
                            }
                            const elementQuantityInput = $(
                                `.element-quantity-input[data-element-id="${elementId}"]`);
                            const elementQuantity = parseFloat(elementQuantityInput.val()) || 0;
                            const elementTotalProTypPrice = calculateTotalMaterialsPrice(elementId) *
                                elementQuantity;
                            totalProTypPrice += elementTotalProTypPrice;

                            // Fetch and accumulate price_out and zeit_cost values
                            const materials = document.querySelectorAll(
                                `#element-materials-${elementId} tbody tr`);
                            materials.forEach(materialRow => {
                                const priceOutCell = materialRow.querySelector(
                                    '.material-price-out .price-out');
                                const priceInCell = materialRow.querySelector(
                                    '.material-price-in .price-in');
                                const zeitCostCell = materialRow.querySelector(
                                    '.material-zeit-cost .zeit-cost');
                                const quantityInput = materialRow.querySelector('.quantity-input');

                                if (priceOutCell && priceInCell && zeitCostCell && quantityInput) {
                                    const priceOutValue = parseFloat(priceOutCell.textContent);
                                    const priceInValue = parseFloat(priceInCell.textContent);
                                    const zeitCostValue = parseFloat(zeitCostCell.textContent);
                                    const quantityValue = parseFloat(quantityInput.value) || 0;

                                    totalPriceOut += priceOutValue * quantityValue *
                                        elementQuantity;
                                    totalPriceIn += priceInValue * quantityValue *
                                        elementQuantity;
                                    totalZeitCost += zeitCostValue * quantityValue *
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

                    zeitCostInput.textContent = formatSwissNumber(totalZeitCost);
                    zeitCostInput2.textContent = formatSwissNumber(totalZeitCost);
                    zeitCosto.textContent = formatSwissNumber(totalZeitCost / 2.5);
                    zeitProfit.textContent = formatSwissNumber(totalZeitCost - (totalZeitCost / 2.5));

                    priceInInput.textContent = formatSwissNumber(totalPriceIn);
                    priceProfit.textContent = formatSwissNumber(totalPriceOut - totalPriceIn);

                    const costoTotalValue = (totalPriceIn + (totalZeitCost / 2.5)).toFixed(2);
                    costoTotal.textContent = formatSwissNumber(costoTotalValue);

                    const profitTotalValue = ((totalPriceOut - totalPriceIn) + totalZeitCost - (totalZeitCost /
                        2.5)).toFixed(2);
                    const discountedProfitTotalValue = profitTotalValue * (1 - (percentage / 100)).toFixed(2);
                    profitTotal.textContent = formatSwissNumber(discountedProfitTotalValue);

                    const costoTotalValue2 = (totalPriceIn + (totalZeitCost / 2.5)).toFixed(2);
                    costoTotal2.textContent = formatSwissNumber(costoTotalValue2);

                    const profitTotalValue2 = ((totalPriceOut - totalPriceIn) + totalZeitCost - (totalZeitCost /
                        2.5)).toFixed(2);
                    const discountedProfitTotalValue2 = profitTotalValue2 * (1 - (percentage / 100)).toFixed(2);
                    profitTotal2.textContent = formatSwissNumber(discountedProfitTotalValue2);

                    // Update the hidden input values
                    document.getElementById('priceOutInput').value = totalPriceOut.toFixed(2);

                    document.getElementById('zeitCostInput').value = totalZeitCost.toFixed(2);
                    document.getElementById('zeitCosto').value = (totalZeitCost / 2.5).toFixed(2);
                    document.getElementById('zeitProfit').value = (totalZeitCost - (totalZeitCost / 2.5)).toFixed(
                        2);

                    document.getElementById('priceInInput').value = totalPriceIn.toFixed(2);
                    document.getElementById('priceProfit').value = (totalPriceOut - totalPriceIn).toFixed(2);

                    document.getElementById('costoTotal').value = costoTotalValue;
                    document.getElementById('profitTotal').value = discountedProfitTotalValue.toFixed(2);

                    // Update the hidden input fields
                    totalProTypPriceInput.value = (totalProTypPrice * mengeValue).toFixed(2);
                    discountedTotalInput.value = discountedTotal.toFixed(2);
                }
            }

            // Auto-save (same flow as create): persists Menge (quantity) and other fields on change / tab away
            let autoSaveTimeout;
            let currentPositionId = {{ (int) $position->id }};
            const autoSaveDelay = 2000;

            function triggerAutoSave() {
                clearTimeout(autoSaveTimeout);
                autoSaveTimeout = setTimeout(() => {
                    autoSaveCurrentPosition();
                }, autoSaveDelay);
            }

            function autoSaveCurrentPosition() {
                const currentIndex = parseInt(document.getElementById('index').value || '0', 10);
                const formData = collectFormData(currentIndex);
                savePositionForType(formData, currentIndex, true);
            }

            function collectFormData(typeIndex) {
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
                const elementQuantities = {};
                selectedElements.forEach(elementId => {
                    const quantityInput = document.querySelector(`.element-quantity-input[data-element-id="${elementId}"]`);
                    if (quantityInput) {
                        elementQuantities[elementId] = quantityInput.value || 1;
                    }
                });
                const materialQuantities = {};
                selectedElements.forEach(elementId => {
                    materialQuantities[elementId] = {};
                    document.querySelectorAll(`#element-materials-${elementId} .quantity-input`).forEach(input => {
                        const materialId = input.dataset.materialId;
                        if (materialId) {
                            materialQuantities[elementId][materialId] = input.value;
                        }
                    });
                });
                return {
                    index: typeIndex,
                    description: document.getElementById('description') ? document.getElementById('description').value : '',
                    description2: document.querySelector('textarea[name="description2"]') ? document.querySelector('textarea[name="description2"]').value : '',
                    blocktype: document.getElementById('blocktype') ? document.getElementById('blocktype').value : '',
                    b: document.getElementById('b') ? document.getElementById('b').value : '',
                    h: document.getElementById('h') ? document.getElementById('h').value : '',
                    t: document.getElementById('t') ? document.getElementById('t').value : '',
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

            window.doAutoSaveAndNavigate = function(nextUrl) {
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
                    window.location.href = nextUrl;
                })
                .catch(error => {
                    console.error('Save before navigate error:', error);
                    window.location.href = nextUrl;
                });
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

            document.querySelectorAll('.element-checkbox, .element-optional-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    triggerAutoSave();
                });
            });
            document.querySelectorAll('.quantity-input, .element-quantity-input, #description, textarea[name="description2"], #blocktype, #b, #h, #t, #menge-input, #percentageInput, #percentage-input').forEach(input => {
                input.addEventListener('input', function() {
                    triggerAutoSave();
                });
                input.addEventListener('change', function() {
                    triggerAutoSave();
                });
            });

            initializePositionElementSelection(organigramToggles, groupElementToggles, {
                expandSelected: true
            });

        });
        // Add an event listener to toggle sublinks
        document.querySelectorAll('.toggle-sublinks').forEach(link => {
            link.addEventListener('click', () => {
                const targetId = link.getAttribute('data-target');
                const targetSublinks = document.getElementById(`${targetId}-sublinks`);
                if (targetSublinks) {
                    targetSublinks.style.display = (targetSublinks.style.display === 'none' ||
                        targetSublinks.style.display === '') ? 'block' : 'none';
                }
            });
        });
    </script>
</body>

</html>
