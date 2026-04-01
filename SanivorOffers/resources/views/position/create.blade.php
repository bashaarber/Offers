<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create</title>
    <style>
        .organigram-label,
        .group-element-label,
        .element-label {
            cursor: pointer;
            display: flex;
            align-items: center;
            padding: 1px 2px;
        }

        .organigram-checkbox,
        .group-element-checkbox,
        .element-checkbox {
            margin-right: 10px;
            display: inline;
        }

        .group-elements,
        .elements {
            display: none;
            padding-left: 4px;
        }

        .group-element,
        .element {
            border-left: 2px solid #3498db;
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
            background-color: #f59e0b !important;
            border-color: #f59e0b !important;
            color: #000 !important;
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

        #auto-save-status {
            font-size: 11px;
            padding: 2px 0;
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
    </style>
</head>

<body>
    @include('layouts.sidebar')

    {{-- Position-specific sidebar content injected into the modern sidebar --}}
    <style>
        .sidebar .sidebar-footer { bottom: 120px; }
        .sidebar .position-extras {
            position: absolute;
            bottom: 0;
            width: 100%;
            max-height: calc(100vh - 400px);
            overflow-y: auto;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inject position-specific content into sidebar
            var sidebar = document.querySelector('.sidebar');
            var footer = sidebar.querySelector('.sidebar-footer');

            // Create position extras container
            var extras = document.createElement('div');
            extras.className = 'position-sidebar-section';
            extras.innerHTML = `
                <hr>
                <div class="sidebar-section-label" style="padding:2px 4px;font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:0.6px;color:rgba(255,255,255,0.35);">Positions</div>
                <button type="button" class="btn btn-sm btn-success mt-1" onclick="addNewPos()" style="width:100%;border-radius:8px;font-size:12px;">
                    <i class="fa-solid fa-plus"></i> Create New Pos
                </button>
                <div style="padding:4px 0;text-align:center;">
                    <button type="button" class="btn btn-warning btn-sm" onclick="document.getElementById('createPositionForm').submit();" style="width:100%;border-radius:8px;font-weight:600;">
                        <i class="fa-solid fa-save" style="margin-right:6px;"></i>Create Position
                    </button>
                </div>
                <div id="auto-save-status" class="mt-1" style="color:#4ade80;font-size:12px;display:none;text-align:center;">
                    <i class="fa-solid fa-check-circle"></i> Auto-saving...
                </div>
                <hr>
                <div class="sidebar-section-label" style="padding:2px 4px;font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:0.6px;color:rgba(255,255,255,0.35);">Positions</div>
            `;

            // Create positions list
            var posList = document.createElement('div');
            posList.className = 'pos-list-container';
            posList.innerHTML = `@foreach ($positions as $position)@php $latestPositionNumber = $positions->max('position_number'); $nextPositionNumber = $latestPositionNumber + 1; @endphp<div style="display:flex;align-items:center;justify-content:space-between;padding:2px 0;border-bottom:1px solid rgba(255,255,255,0.06);"><a href="{{ route('position.edit', $position->id) }}" style="color:rgba(255,255,255,0.7);font-size:12px;font-weight:500;"><strong>Pos. {{ $position->position_number }}</strong></a><div style="display:flex;gap:2px;"><form action="{{ route('position.copy', $position->id) }}" method="post" style="margin:0;">@csrf<button type="submit" class="btn btn-secondary btn-sm" style="padding:1px 5px;font-size:10px;"><i class="fa-solid fa-copy"></i></button></form><form action="{{ route('position.destroy', $position->id) }}" method="post" style="margin:0;" onsubmit='return confirm("Are you sure?");'>@csrf @method('DELETE')<button type="submit" class="btn btn-danger btn-sm" style="padding:1px 5px;font-size:10px;"><i class="fa-solid fa-trash-can"></i></button></form></div></div>@endforeach`;
            extras.appendChild(posList);

            // Add PDF links
            @if (request()->has('offert_id'))
            var pdfLinks = document.createElement('div');
            pdfLinks.style.cssText = 'padding:4px 0;';
            pdfLinks.innerHTML = `<hr><a href="{{ route('offert.pdf', request()->query('offert_id')) }}" style="font-size:12px;padding:3px 4px;"><i class="fa-solid fa-file-export" style="margin-right:6px;"></i>External PDF</a>`;
            extras.appendChild(pdfLinks);
            @endif

            // Insert before footer
            sidebar.insertBefore(extras, footer);
        });
    </script>
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
                    <input type="hidden" name="percentage" id="percentageInput" value="0">
                    <input type="hidden" name="price-out-input" id="priceOutInput" value="0.00">
                    <input type="hidden" name="zeit-cost-input" id="zeitCostInput" value="0.00">
                    <input type="hidden" name="material-costo" id="priceInInput" value="0.00">
                    <input type="hidden" name="material-profit" id="priceProfit" value="0.00">
                    <input type="hidden" name="zeit-costo" id="zeitCosto" value="0.00">
                    <input type="hidden" name="zeit-profit" id="zeitProfit" value="0.00">
                    <input type="hidden" name="costo-total" id="costoTotal" value="0.00">
                    <input type="hidden" name="profit-total" id="profitTotal" value="0.00">
                    <input type="hidden" name="is_optional" id="is_optional_input" value="0">
                    <table class="table">
                        <thead>
                            <tr class="table-dark">
                                <th scope="col">Rahmen <input value="Pos. {{ $nextPositionNumber ?? 1 }}"
                                        style="width: 150px" disabled> mm </th>
                                <th scope="col"> Desc. <input type="text" id="description" name="description">
                                </th>
                                <th>
                                    Blocktyp <select name="blocktype" id="blocktype">
                                        <option value="" selected> - </option>
                                        <option value="Vorwand-Raumhoch" {{ $index == 1 ? 'selected' : '' }}>
                                            Vorwand-Raumhoch</option>
                                        <option value="Vorwand-Raumhoch und Teilhoch"
                                            {{ $index == 3 ? 'selected' : '' }}>Vorwand-Raumhoch und Teilhoch
                                        </option>
                                        <option value="Vorwand-Teilhoch">Vorwand-Teilhoch</option>
                                        <option value="Freistehend-Raumhoch"{{ $index == 2 ? 'selected' : '' }}>
                                            Freistehend-Raumhoch</option>
                                        <option value="Vorwand-Freistehend">Vorwand-Freistehend</option>
                                        <option value="Freistehend-Teilhoch">Freistehend-Teilhoch</option>
                                        <option value="Vorwand DeBO-System">Vorwand DeBO-System</option>
                                        <option value="Trennwand DeBO-System">Trennwand DeBO-System</option>
                                    </select>
                                </th>
                                <th scope="col"> B <input style="width: 150px" type="text" id="b"
                                        name="b"> cm</th>
                                <th scope="col"> H <input style="width: 150px" type="text" id="h"
                                        name="h"> cm</th>
                                <th scope="col"> T <input style="width: 150px" type="text" id="t"
                                        name="t"> cm</th>
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
                                        <td>Menge <input id="menge-input" type="number" name="quantity"
                                                value="1" min="1"> <input type="checkbox" name="is_optional" id="is_optional" value="1"> <label for="is_optional">Optional</label>
                                        </td>
                                        <td id="total-pro-typ-price" name="total-pro-typ-price">0.00</td>
                                        <td id="discounted-total">0.00</td>
                                        <td>% <input id="percentage-input" name="percentage-input" value="0">
                                        </td>
                                        <td id="costo-total" name="costo-total">0.00</td>
                                        <td id="profit-total" name="profit-total">0.00</td>
                                    </tr>
                                </tbody>
                        </thead>
                    </table>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        @foreach ($organigrams as $organigram)
                            <h5 class="card-title">
                                <input type="checkbox" name="selected_organigrams[]" class="organigram-checkbox"
                                    value="{{ $organigram->id }}">
                                {{ $organigram->name }}
                            </h5>
                            <div class="group-elements">
                                @foreach ($organigram->group_elements as $group_element)
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-subtitle">
                                                <input type="checkbox" name="selected_group_elements[]"
                                                    class="group-element-checkbox" value="{{ $group_element->id }}">
                                                {{ $group_element->name }}
                                            </h6>
                                            <div class="elements">
                                                @foreach ($group_element->elements as $element)
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h6 class="card-subtitle">
                                                                <input type="checkbox" name="selected_elements[]"
                                                                    class="element-checkbox"
                                                                    data-element-id="{{ $element->id }}"
                                                                    value="{{ $element->id }}">
                                                                {{ $element->name }}
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
            <div class="col-md-9 position">
                @php
    $totalZTotal = 0; // Initialize the variable to store the sum
@endphp
                @foreach ($elements as $element)
                    @php
                        $isRahmeElement = $element
                            ->group_elements()
                            ->whereHas('organigrams', function ($query) {
                                $query->where('name', 'Rahme');
                            })
                            ->whereIn('name', ['Grundrahme', 'Aufstock', 'Nische'])
                            ->exists();
                    @endphp
                    <table class="table element-materials" id="element-materials-{{ $element->id }}"
                        style="display: none">
                        <thead style="text-align: left">
                            <tr>
                                <th scope="col">Ans.</th>
                                <th scope="col">Name</th>
                                <th></th>
                                <th scope="col">PStk.</th>
                                <th scope="col">Total CHF</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="text-align: left" class="table-dark">
                                <th scope="col">
                                    <input type="number" min="1" style="width: 130px"
                                        class="element-quantity-input" data-element-id="{{ $element->id }}"
                                        name="element_quantity[{{ $element->id }}]"
                                        value="{{ $element->quantity }}">
                                </th>
                                <th scope="col">{{ $element->name }}</th>
                                <th></th>
                                <th scope="col" class="total-materials-header"
                                    data-element-id="{{ $element->id }}">
                                    CHF <span class="total-materials-value">0</span> X
                                    <span class="element-quantity">{{ $element->quantity }}</span>
                                </th>
                                <th scope="col" class="total-materials-header"
                                    data-element-id="{{ $element->id }}">
                                    <span class="total-materials-value-header">0</span>
                                </th>
                            </tr>

                            @foreach ($element->materials as $material)
                                <tr style="text-align: left">
                                    <td>
                                        mit <input style="width: 100px" min="0" step="any" type="number"
                                            class="quantity-input" value="{{ $material->pivot->quantity }}"
                                            name="material_quantity[{{ $element->id }}][{{ $material->id }}]"
                                            data-element-id="{{ $element->id }}"
                                            data-material-id="{{ $material->id }}"> {{ $material->unit }}
                                    </td>
                                    <td>
                                        {{ $material->name }}
                                    </td>
                                    <td style="text-align: right" class="price-details"
                                        data-material-id="{{ $material->id }}"
                                        data-material-price="{{ $material->total }}">
                                        CHF <span
                                            class="price-in">{{ number_format($isRahmeElement ? $material->total / $offert->difficulty : $material->total, 2, '.', '') }}</span>
                                        X <span class="quantity">{{ $material->pivot->quantity }}</span>
                                        {{ $material->unit }}
                                    </td>
                                    {{-- Hidden inputs --}}
                                    <td style="text-align: right;display:none" class="material-price-out">
                                        CHF <span class="price-out">{{ $material->price_out }}</span>
                                    </td>
                                    <td style="text-align: right;display:none" class="material-price-in">
                                        CHF <span class="price-in">{{ $material->price_in }}</span>
                                    </td>
                                    <td style="text-align: right;display:none" class="material-zeit-cost">
                                        CHF <span class="zeit-cost">{{ $material->zeit_cost }}</span>
                                    </td>
                                    {{-- Hidden inputs --}}

                                    <td class="total" data-material-id="{{ $material->id }}"
                                        data-element-id="{{ $element->id }}">
                                        {{-- {{ $material->total * $material->pivot->quantity }} --}}
                                        {{ number_format(
                                            $isRahmeElement
                                                ? ($material->total / $offert->difficulty) * $material->pivot->quantity
                                                : $material->total * $material->pivot->quantity,
                                            2,
                                            '.',
                                            '',
                                        ) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endforeach
            </div>
        </div>
        <button hidden type="submit" class="btn btn-primary mt-3">Create Position</button>
        </form>
        {{-- <a href="{{ route('offert.index') }}" class="btn btn-secondary mt-3">Back to Offert</a> --}}
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const organigramCheckboxes = document.querySelectorAll('.organigram-checkbox');
            const groupElementCheckboxes = document.querySelectorAll('.group-element-checkbox');
            const elementCheckboxes = document.querySelectorAll('.element-checkbox');
            // Initialize the running total materials price variable
            let runningTotalMaterialsPrice = 0;
            let percentage = 0;
            // Update the total materials price on document ready
            updateTotalMaterialsPrice();

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

            // Function to handle the visibility of the element materials table
            function handleElementMaterialsTableVisibility(checkbox) {
                const elementId = checkbox.getAttribute('data-element-id');
                const elementMaterialsTable = document.querySelector(`#element-materials-${elementId}`);

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
                    const elementMaterialsTable = document.querySelector(
                        `#element-materials-${elementId}`);

                    if (elementMaterialsTable) {
                        elementMaterialsTable.style.display = this.checked ? '' : 'none';

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
                            `#element-materials-${elementId}`);

                        if (elementMaterialsTable && checkbox.checked) {
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

                    totalProTypPriceCell.textContent = (totalProTypPrice * mengeValue).toFixed(2);
                    totalProTypPriceCell2.textContent = (totalProTypPrice * mengeValue).toFixed(2);
                    discountedTotalCell.textContent = discountedTotal.toFixed(2);
                    discountedTotalCell2.textContent = discountedTotal.toFixed(2);

                    // Update the hidden input fields with the calculated values
                    totalProTypPriceInput.value = totalProTypPrice.toFixed(2);
                    discountedTotalInput.value = discountedTotal.toFixed(2);

                    // Update the displayed price_out and zeit_cost values
                    priceOutInput.textContent = totalPriceOut.toFixed(2);
                    priceOutInput2.textContent = totalPriceOut.toFixed(2);

                    zeitCostInput.textContent = totalZeitCost.toFixed(2);
                    zeitCostInput2.textContent = totalZeitCost.toFixed(2);
                    zeitCosto.textContent = (totalZeitCost / 2.5).toFixed(2);
                    zeitProfit.textContent = totalZeitCost - (totalZeitCost / 2.5);

                    priceInInput.textContent = totalPriceIn.toFixed(2);
                    priceProfit.textContent = (totalPriceOut - totalPriceIn).toFixed(2);

                    const costoTotalValue = (totalPriceIn + (totalZeitCost / 2.5)).toFixed(2);
                    costoTotal.textContent = costoTotalValue;

                    const profitTotalValue = ((totalPriceOut - totalPriceIn) + totalZeitCost - (totalZeitCost /
                        2.5)).toFixed(2);
                    const discountedProfitTotalValue = profitTotalValue * (1 - (percentage / 100)).toFixed(2);
                    profitTotal.textContent = discountedProfitTotalValue.toFixed(2);

                    const costoTotalValue2 = (totalPriceIn + (totalZeitCost / 2.5)).toFixed(2);
                    costoTotal2.textContent = costoTotalValue2;

                    const profitTotalValue2 = ((totalPriceOut - totalPriceIn) + totalZeitCost - (totalZeitCost /
                        2.5)).toFixed(2);
                    const discountedProfitTotalValue2 = profitTotalValue2 * (1 - (percentage / 100)).toFixed(2);
                    profitTotal2.textContent = discountedProfitTotalValue2.toFixed(2);

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

            organigramCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const groupElements = this.parentElement.nextElementSibling;
                    groupElements.style.display = this.checked ? 'block' : 'none';
                });
            });

            groupElementCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const elements = this.parentElement.nextElementSibling;
                    elements.style.display = this.checked ? 'block' : 'none';
                });
            });
            // Function to toggle visibility for a specific checkbox type
            function toggleCheckboxVisibility(checkboxes, className) {
                checkboxes.forEach(checkbox => {
                    const elements = checkbox.parentElement.nextElementSibling;
                    if (checkbox.checked) {
                        elements.style.display = 'block';
                    }
                    checkbox.addEventListener('change', function() {
                        const elements = checkbox.parentElement.nextElementSibling;
                        elements.style.display = this.checked ? 'block' : 'none';
                    });
                });
            }
            toggleCheckboxVisibility(organigramCheckboxes, 'organigram');
            toggleCheckboxVisibility(groupElementCheckboxes, 'group-element');
            toggleCheckboxVisibility(elementCheckboxes, 'element');

            // Auto-save functionality for current position
            let autoSaveTimeout;
            const autoSaveDelay = 2000; // 2 seconds delay after last change

            function triggerAutoSave() {
                clearTimeout(autoSaveTimeout);
                autoSaveTimeout = setTimeout(() => {
                    const selectedElements = Array.from(document.querySelectorAll('.element-checkbox:checked'))
                        .map(cb => cb.value);
                    const selectedGroupElements = Array.from(document.querySelectorAll('.group-element-checkbox:checked'))
                        .map(cb => cb.value);
                    const selectedOrganigrams = Array.from(document.querySelectorAll('.organigram-checkbox:checked'))
                        .map(cb => cb.value);

                    // Only auto-save if there are selections
                    if (selectedElements.length > 0 || selectedGroupElements.length > 0 || selectedOrganigrams.length > 0) {
                        autoSaveCurrentPosition();
                    }
                }, autoSaveDelay);
            }

            // Listen to all checkbox changes
            document.querySelectorAll('.organigram-checkbox, .group-element-checkbox, .element-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateTotalProTypPrice();
                    triggerAutoSave();
                });
            });

            // Listen to quantity and other input changes
            document.querySelectorAll('.quantity-input, .element-quantity-input, #description, #blocktype, #b, #h, #t, #is_optional').forEach(input => {
                input.addEventListener('input', function() {
                    updateTotalProTypPrice();
                    triggerAutoSave();
                });
            });

            // Listen to optional checkbox changes
            document.getElementById('is_optional').addEventListener('change', function() {
                triggerAutoSave();
            });

            function autoSaveCurrentPosition() {
                const statusDiv = document.getElementById('auto-save-status');
                statusDiv.style.display = 'block';
                statusDiv.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Auto-saving...';

                const currentIndex = parseInt(document.getElementById('index').value || '0', 10);
                const formData = collectFormData(currentIndex);
                if (formData.selected_elements && formData.selected_elements.length > 0) {
                    savePositionForType(formData, currentIndex, true);
                }
            }

            function collectFormData(typeIndex) {
                const form = document.getElementById('createPositionForm');
                const formData = new FormData(form);

                // Collect selected values
                const selectedElements = Array.from(document.querySelectorAll('.element-checkbox:checked'))
                    .map(cb => cb.value);
                const selectedGroupElements = Array.from(document.querySelectorAll('.group-element-checkbox:checked'))
                    .map(cb => cb.value);
                const selectedOrganigrams = Array.from(document.querySelectorAll('.organigram-checkbox:checked'))
                    .map(cb => cb.value);

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
                    material_quantity: materialQuantities,
                    quantity: document.getElementById('menge-input').value || 1,
                    is_optional: document.getElementById('is_optional').checked ? 1 : 0,
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
                        offert_id: document.getElementById('offert_id').value
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (isLast) {
                        const statusDiv = document.getElementById('auto-save-status');
                        if (data.success) {
                            statusDiv.innerHTML = '<i class="fa-solid fa-check-circle"></i> Auto-saved Pos ' + (typeIndex + 1);
                            statusDiv.style.color = '#28a745';
                            setTimeout(() => {
                                statusDiv.style.display = 'none';
                            }, 3000);
                        } else {
                            statusDiv.innerHTML = '<i class="fa-solid fa-exclamation-circle"></i> Error saving';
                            statusDiv.style.color = '#dc3545';
                        }
                    }
                })
                .catch(error => {
                    console.error('Auto-save error:', error);
                    if (isLast) {
                        const statusDiv = document.getElementById('auto-save-status');
                        statusDiv.innerHTML = '<i class="fa-solid fa-exclamation-circle"></i> Error saving';
                        statusDiv.style.color = '#dc3545';
                    }
                });
            }

            function addNewPos() {
                const offertId = document.getElementById('offert_id').value;
                const nextIndex = {{ $positions->count() }};
                window.location.href = '{{ url("/position/create") }}/' + nextIndex + '?offert_id=' + offertId;
            }
        });
        // Toggle sublinks handled by sidebar partial
    </script>
</body>

</html>
