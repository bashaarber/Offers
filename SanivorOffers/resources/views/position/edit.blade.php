<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .organigram-label,
        .group-element-label,
        .element-label {
            cursor: pointer;
            display: flex;
            align-items: center;
            padding: 5px;
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
            padding-left: 10px;
        }

        .group-element,
        .element {
            border-left: 2px solid #3498db;
            padding-left: 10px;
            margin: 5px 0;
        }

        th {
            color: black;
        }

        .position {
            width: 600px;
            height: 600px;
            overflow-x: hidden;
            overflow-y: auto;
            text-align: center;
        }
    </style>
</head>

<body>
    @include('layouts.sidebar')
    <div class="content">
        <div class="row">
            <div class="col-12">
                <form method="POST" action="{{ route('position.update', $position->id) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="totalProTypPrice" id="totalProTypPriceInput"
                        value="{{ $position->price_brutto }}">
                    <input type="hidden" name="discountedTotal" id="discountedTotalInput"
                        value="{{ $position->price_discount }}">
                    <input type="hidden" name="percentage" id="percentageInput" value="{{ $position->discount }}">

                    <table class="table">
                        <thead>
                            <tr class="table-dark">
                                {{-- <th> Rahmen <input> mm | Desc. <input> Blocktyp <input> B <input> cm | H <input> cm | T <input> cm --}}
                                <th scope="col">Rahmen <input value="Pos. {{ $position->position_number }}" disabled> mm </th>
                                <th scope="col"> Desc. <input name="description" value="{{ $position->description }}"> </th>
                                <th>
                                    Blocktyp <select name="blocktype" id="blocktype">
                                        <option value="" @if (is_null($position->blocktype)) selected @endif> - </option>
                                        <option value="Vorwand-Raumhoch" @if ($position->blocktype == 'Vorwand-Raumhoch') selected @endif>Vorwand-Raumhoch</option>
                                        <option value="Vorwand-Raumhoch und Teilhoch"  @if ($position->blocktype == 'Vorwand-Raumhoch und Teilhoch') selected @endif>Vorwand-Raumhoch und Teilhoch</option>
                                        <option value="Vorwand-Teilhoch" @if ($position->blocktype == 'Vorwand-Teilhoch') selected @endif>Vorwand-Teilhoch</option>
                                        <option value="Freistehend-Raumhoch" @if ($position->blocktype == 'Freistehend-Raumhoch') selected @endif>Freistehend-Raumhoch</option>
                                        <option value="Vorwand-Freistehend" @if ($position->blocktype == 'Vorwand-Freistehend') selected @endif>Vorwand-Freistehend</option>
                                        <option value="Freistehend-Teilhoch" @if ($position->blocktype == 'Freistehend-Teilhoch') selected @endif>Freistehend-Teilhoch</option>
                                        <option value="Vorwand DeBO-System" @if ($position->blocktype == 'Vorwand DeBO-System') selected @endif>Vorwand DeBO-System</option>
                                        <option value="Trennwand DeBO-System" @if ($position->blocktype == 'Trennwand DeBO-System') selected @endif>Trennwand DeBO-System</option>
                                    </select>
                                </th>
                                <th scope="col"> B <input name="b" value="{{ $position->b }}"> cm </th>
                                <th scope="col"> H <input name="h" value="{{ $position->h }}"> cm </th>
                                <th scope="col"> T<input name="t" value="{{ $position->t }}"> cm </th>
                                <th></th>
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
                                        <td>0.00</td>
                                        <td>0.00</td>
                                        <td>% <input value="0"></td>
                                        <td>0.00</td>
                                        <td>0.00</td>
                                    </tr>
                                    <tr class="table-active">
                                        <td><strong>Zeit Pro Typ</strong></td>
                                        <td>0.00</td>
                                        <td>0.00</td>
                                        <td>% <input value="0"></td>
                                        <td>0.00</td>
                                        <td>0.00</td>
                                    </tr>
                                    <tr class="table-secondary">
                                        <td><strong>Total Pro Typ</strong></td>
                                        <td id="total-pro-typ-price" name="total-pro-typ-price">
                                            {{ $position->price_brutto }}</td>
                                        <td id="discounted-total">{{ $position->price_discount }}</td>
                                        <td>% <input id="percentage-input" name="percentage-input"
                                                value="{{ $position->discount }}"></td>
                                        <td>0.00</td>
                                        <td>0.00</td>
                                    </tr>
                                    <tr class="table-dark">
                                        <td>Menge <input></td>
                                        <td>0.00</td>
                                        <td>0.00</td>
                                        <td>% <input value="0"></td>
                                        <td>0.00</td>
                                        <td>0.00</td>
                                    </tr>
                                </tbody>
                    </table>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        @foreach ($organigrams as $organigram)
                            <h5 class="card-title">

                                <input type="checkbox" class="organigram-checkbox" name="selected_organigrams[]"
                                    value="{{ $organigram->id }}"
                                    {{ in_array($organigram->id, old('selected_organigrams', $position->organigrams->pluck('id')->toArray())) ? 'checked' : '' }}>

                                {{ $organigram->name }}
                            </h5>
                            <div class="group-elements">
                                @foreach ($organigram->group_elements as $group_element)
                                    <div class="card mb-2">
                                        <div class="card-body">
                                            <h6 class="card-subtitle mb-2">
                                                <input type="checkbox" class="group-element-checkbox"
                                                    name="selected_group_elements[]" value="{{ $group_element->id }}"
                                                    {{ in_array($group_element->id, old('selected_group_elements', $position->group_elements->pluck('id')->toArray())) ? 'checked' : '' }}>
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
                </div>
                <button type="submit" id="update-button" class="btn btn-primary mt-3">Update Position</button>
                </form>
                <a href="{{ route('position.index', ['offert_id' => $position->offerts->first()->id]) }}"
                    class="btn btn-secondary mt-3">Back</a>
            </div>
            <div class="col-md-8 position">
                @foreach ($elements as $element)
                    @php
                        $isSelected = $position->elements->contains($element->id);
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
                                <th scope="col"><input style="width: 130px" value="1"></th>
                                <th  scope="col">{{ $element->name }}</th>
                                <th></th>
                                <th scope="col" class="total-materials-header"
                                    data-element-id="{{ $element->id }}">
                                    CHF <span class="total-materials-value-header">0</span> X 1
                                </th>
                                <th scope="col" class="total-materials-header"
                                    data-element-id="{{ $element->id }}">
                                    <span class="total-materials-value-header">0</span>
                                </th>
                            </tr>

                            @foreach ($element->materials as $material)
                                <tr style="text-align: left">
                                    <td>
                                        mit <input style="width: 100px" min="1" type="number"
                                            class="quantity-input" value="{{ $material->pivot->quantity }}"
                                            data-element-id="{{ $element->id }}"
                                            data-material-id="{{ $material->id }}"> {{$material->unit}}
                                    </td>
                                    <td>
                                        {{ $material->name }}
                                    </td>
                                    <td style="text-align: right" class="price-details" data-material-id="{{ $material->id }}"
                                        data-material-price="{{ $material->price_in }}">
                                        CHF <span class="price-in">{{ $material->price_in }}</span> X <span
                                            class="quantity">{{ $material->pivot->quantity }}</span>
                                        {{ $material->unit }}
                                    </td>
                                    <td class="total" data-material-id="{{ $material->id }}"
                                        data-element-id="{{ $element->id }}">
                                        {{ $material->price_in * $material->pivot->quantity }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endforeach
            </div>
        </div>
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

                    totalElement.text(totalPrice);

                    // Update the currentQuantity data attribute
                    quantityInput.data('currentQuantity', currentQuantity);
                    
                    updateTotalProTypPrice();
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
                    const totalMaterialsPrice = calculateTotalMaterialsPrice(elementId);

                    // Update the total in the table header
                    $(this).find('.total-materials-value-header').text(totalMaterialsPrice);
                });
            }

            // Event listener for the percentage input field
            const percentageInput = document.getElementById('percentage-input');
            percentageInput.addEventListener('input', function() {
                const inputValue = this.value.trim(); // Remove leading/trailing white spaces
                percentage = inputValue ? parseFloat(inputValue) : 0; // Use 0% if input is empty

                // Update the hidden input field with the calculated percentage value
                document.getElementById('percentageInput').value = percentage;

                updateTotalProTypPrice();
            });

            // Function to update the Total Pro Typ Price and Discounted Total based on the running total and percentage
            function updateTotalProTypPrice() {
                const totalProTypPriceCell = document.getElementById('total-pro-typ-price');
                const discountedTotalCell = document.getElementById('discounted-total');
                const percentageInput = document.getElementById('percentage-input');
                const totalProTypPriceInput = document.getElementById('totalProTypPriceInput');
                const discountedTotalInput = document.getElementById('discountedTotalInput');

                if (totalProTypPriceCell && discountedTotalCell && percentageInput) {
                    let totalProTypPrice = 0;

                    // Loop through all element checkboxes
                    elementCheckboxes.forEach(checkbox => {
                        const elementId = checkbox.getAttribute('data-element-id');
                        const elementMaterialsTable = document.querySelector(
                            `#element-materials-${elementId}`);

                        if (elementMaterialsTable && checkbox.checked) {
                            // Calculate the total materials price for the selected element
                            totalProTypPrice += calculateTotalMaterialsPrice(elementId);
                        }
                    });

                    const discountedTotal = totalProTypPrice * (1 - (percentage / 100));

                    totalProTypPriceCell.textContent = totalProTypPrice.toFixed(2); // Format as desired
                    discountedTotalCell.textContent = discountedTotal.toFixed(2); // Format as desired

                    // Update the hidden input fields with the calculated values
                    totalProTypPriceInput.value = totalProTypPrice.toFixed(2);
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
    </script>
</body>

</html>
