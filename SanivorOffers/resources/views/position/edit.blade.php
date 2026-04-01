<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update</title>
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
    </style>
</head>

<body>
    @include('layouts.sidebar')

    {{-- Position-specific sidebar content --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var sidebar = document.querySelector('.sidebar');
            var footer = sidebar.querySelector('.sidebar-footer');

            var extras = document.createElement('div');
            extras.style.cssText = 'padding:0 4px;';
            extras.innerHTML = `
                <hr style="border-color:rgba(255,255,255,0.1);margin:6px 0;">
                <div style="padding:2px 4px;font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:0.6px;color:rgba(255,255,255,0.35);">Positions</div>
                <button type="button" class="btn btn-sm btn-success mt-1" onclick="window.location.href='{{ route('position.create', ['index' => ($positions->max('position_number') ? $positions->max('position_number') : 0)]) }}?offert_id={{ $offertId }}'" style="width:100%;border-radius:8px;font-size:12px;">
                    <i class="fa-solid fa-plus"></i> Create New Pos
                </button>
            `;

            var posList = document.createElement('div');
            posList.className = 'pos-list-container';
            posList.innerHTML = `<div id="sortable-position-list">@foreach ($positions as $pos)<div class="position-row" data-position-id="{{ $pos->id }}" style="display:flex;align-items:center;justify-content:space-between;padding:2px 0;border-bottom:1px solid rgba(255,255,255,0.06);"><div style="display:flex;align-items:center;gap:4px;"><i class="fa-solid fa-grip-vertical drag-handle" style="cursor:grab;color:rgba(255,255,255,0.45);font-size:10px;"></i><a href="{{ route('position.edit', $pos->id) }}" style="color:{{ request()->segment(2) == $pos->id ? '#f59e0b' : 'rgba(255,255,255,0.7)' }};font-size:12px;font-weight:500;"><strong class="position-number-label">Pos. {{ $pos->position_number }}</strong>@if($pos->is_optional)<span style="font-size:10px;color:#f59e0b;margin-left:4px;">(Optional)</span>@endif</a></div><div style="display:flex;gap:2px;"><form action="{{ route('position.copy', $pos->id) }}" method="post" style="margin:0;">@csrf<button type="submit" class="btn btn-secondary btn-sm" style="padding:1px 5px;font-size:10px;"><i class="fa-solid fa-copy"></i></button></form><form action="{{ route('position.destroy', $pos->id) }}" method="post" style="margin:0;">@csrf @method('DELETE')<button type="submit" class="btn btn-danger btn-sm" style="padding:1px 5px;font-size:10px;" onclick="return confirm('Are you sure?');"><i class="fa-solid fa-trash-can"></i></button></form></div></div>@endforeach</div>`;
            extras.appendChild(posList);

            // PDF links
            var pdfLinks = document.createElement('div');
            pdfLinks.innerHTML = `<hr style="border-color:rgba(255,255,255,0.1);margin:4px 0;"><a href="{{ route('offert.pdf', $offertId) }}" style="font-size:12px;padding:3px 4px;"><i class="fa-solid fa-file-export" style="margin-right:6px;"></i>External PDF</a>`;
            extras.appendChild(pdfLinks);

            sidebar.insertBefore(extras, footer);

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
        });
    </script>
    <div class="content">
        <div class="row">
            <div class="col-12">
                <form id="updatePositionForm" method="POST" action="{{ route('position.update', $position->id) }}">
                    @csrf
                    <input type="hidden" name="index" id="index" value="">
                    <input type="hidden" name="offert_id" value="{{ $offertId }}">
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
                                <th scope="col"> Desc. <input name="description"
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
                                <th scope="col"> B <input style="width: 150px" name="b"
                                        value="{{ $position->b }}"> cm </th>
                                <th scope="col"> H <input style="width: 150px" name="h"
                                        value="{{ $position->h }}"> cm </th>
                                <th scope="col"> T<input style="width: 150px" name="t"
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
                                    <tr class="table-active">
                                        <td><strong>Materiale Pro Typ</strong></td>
                                        <td id="price-out-input">{{ $position->material_brutto }}</td>
                                        <td id="price-out-input2">{{ $position->material_brutto }}</td>
                                        <td>% <input value="0"></td>
                                        <td id="price-in-input">{{ $position->material_costo }}</td>
                                        <td id="price-profit">{{ $position->material_profit }}</td>
                                    </tr>
                                    <tr class="table-active">
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
                                                value="{{ $position->quantity }}" min="1"> <input type="checkbox" name="is_optional" id="is_optional" value="1" {{ $position->is_optional ? 'checked' : '' }}> <label for="is_optional">Optional</label>
                                        </td>
                                        <td id="total-pro-typ-price" name="total-pro-typ-price">
                                            {{ $position->price_brutto }}</td>
                                        <td id="discounted-total">{{ $position->price_discount }}</td>
                                        <td>% <input id="percentage-input" name="percentage-input"
                                                value="{{ $position->discount }}"></td>
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
                            <h5 class="card-title" style="padding:2px 4px;margin:2px 0;">
                                <input type="checkbox" class="organigram-checkbox" name="selected_organigrams[]"
                                    value="{{ $organigram->id }}"
                                    {{ in_array($organigram->id, old('selected_organigrams', $position->organigrams->pluck('id')->toArray())) ? 'checked' : '' }}>

                                {{ $organigram->name }}
                            </h5>
                            <div class="group-elements">
                                @foreach ($organigram->group_elements as $group_element)
                                    <div class="card mb-1" style="padding:1px;margin-bottom:1px;">
                                        <div class="card-body" style="padding:4px;">
                                            <h6 class="card-subtitle mb-2" style="padding:2px 4px;margin:2px 0;">
                                                <input type="checkbox" class="group-element-checkbox"
                                                    name="selected_group_elements[]" value="{{ $group_element->id }}"
                                                    {{ in_array($group_element->id, old('selected_group_elements', $position->group_elements->pluck('id')->toArray())) ? 'checked' : '' }}>
                                                {{ $group_element->name }}
                                            </h6>
                                            <div class="elements">
                                                @foreach ($group_element->elements as $element)
                                                    <div class="card" style="padding:1px;margin-bottom:1px;">
                                                        <div class="card-body" style="padding:4px;">
                                                            <h6 class="card-subtitle" style="padding:2px 4px;margin:0;">
                                                                <input type="checkbox" name="selected_elements[]"
                                                                    class="element-checkbox"
                                                                    data-element-id="{{ $element->id }}"
                                                                    value="{{ $element->id }}"
                                                                    {{ $position->elements->contains($element->id) ? 'checked' : '' }}>
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
                    <textarea name="description2" rows="3" style="padding:4px;margin-top:6px;">{{ $position->description2 }}</textarea>
                </div>
            </div>
            <div class="col-md-9 position">
                @foreach ($elements as $element)
                    @php
                        $isSelected = $position->elements->contains($element->id);
                        $isRahmeElement = $element
                            ->group_elements()
                            ->whereHas('organigrams', function ($query) {
                                $query->where('name', 'Rahme');
                            })
                            ->whereIn('name', ['Grundrahme', 'Aufstock', 'Nische'])
                            ->exists();
                    @endphp
                    @php
                        $pivotQuantity = $element->positions->first()->pivot->quantity ?? 1;
                    @endphp
                    <table class="table element-materials" id="element-materials-{{ $element->id }}"
                        style="display: {{ $isSelected ? '' : 'none' }}">
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
                                        name="element_quantity[{{ $element->id }}]" value="{{ $pivotQuantity }}">
                                </th>
                                <th scope="col">{{ $element->name }}</th>
                                <th></th>
                                <th scope="col" class="total-materials-header"
                                    data-element-id="{{ $element->id }}">
                                    CHF <span class="total-materials-value">0</span> X
                                    <span class="element-quantity">{{ $pivotQuantity }}</span>
                                </th>
                                <th scope="col" class="total-materials-header"
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
                                @endphp
                                <tr style="text-align: left">
                                    <td>
                                        mit <input style="width: 100px" min="0" step="any" type="number"
                                            class="quantity-input" value="{{ $quantity }}"
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
                                        CHF <span class="price-in">{{ number_format($isRahmeElement ? $material->total / $offert->difficulty : $material->total, 2, '.', '') }}</span> X <span
                                            class="quantity">{{ $quantity }}</span>
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
                                        {{-- {{ $material->total * $quantity }} --}}
                                        {{ number_format(
                                            $isRahmeElement
                                                ? ($material->total / $offert->difficulty) * $quantity
                                                : $material->total * $quantity,
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
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
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
