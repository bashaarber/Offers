<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create</title>
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
                <table class="table">
                    <thead>
                        <tr class="table-dark">

                            {{-- <th> Rahmen <input> mm | Desc. <input> Blocktyp <input> B <input> cm | H <input> cm | T <input> cm --}}
                            <th scope="col">Rahmen <input> mm </th>
                            <th scope="col"> Desc. <input> </th>
                            <th scope="col"> Blocktyp <input> cm </th>
                            <th scope="col"> H <input> cm </th>
                            <th scope="col"> T<input> cm </th>
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
                                    <td id="total-pro-typ-price">0.00</td>
                                    <td id="discounted-total">0.00</td>
                                    <td>% <input id="percentage-input" value="0"></td>
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
                                <input type="checkbox" class="organigram-checkbox">
                                {{ $organigram->name }}
                            </h5>
                            <div class="group-elements">
                                @foreach ($organigram->group_elements as $group_element)
                                    <div class="card mb-2">
                                        <div class="card-body">
                                            <h6 class="card-subtitle mb-2">
                                                <input type="checkbox" class="group-element-checkbox">
                                                {{ $group_element->name }}
                                            </h6>
                                            <div class="elements">
                                                @foreach ($group_element->elements as $element)
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h6 class="card-subtitle">
                                                                <input type="checkbox" class="element-checkbox"
                                                                    data-element-id="{{ $element->id }}">
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
            </div>
            <div class="col-md-8 position">
                @foreach ($elements as $element)
                    <table class="table element-materials" id="element-materials-{{ $element->id }}"
                        style="display: none">
                        <thead>
                            <tr>
                                <th scope="col">Ans.</th>
                                <th scope="col">Name</th>
                                <th></th>
                                <th scope="col">PStk.</th>
                                <th scope="col">Total CHF</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="table-dark">
                                <th scope="col"><input style="width: 100px"></th>
                                <th scope="col">{{ $element->name }}</th>
                                <th></th>
                                <th scope="col">
                                    @php
                                        $totalMaterialsPrice = 0;
                                    @endphp
                                    @foreach ($element->materials as $material)
                                        @php
                                            $totalMaterialsPrice += $material->price_in * $material->pivot->quantity;
                                        @endphp
                                    @endforeach
                                    CHF {{ $totalMaterialsPrice }} X
                                </th>
                                <th scope="col">
                                    {{ $totalMaterialsPrice }}
                                </th>
                            </tr>
                            @foreach ($element->materials as $material)
                                <tr>
                                    <td>mit <input style="width: 60px" value="{{ $material->pivot->quantity }}">
                                        {{ $material->unit }}</td>
                                    <td>{{ $material->name }}</td>
                                    <td>CHF {{ $material->price_in }} X {{ $material->pivot->quantity }}
                                        {{ $material->unit }}</td>
                                    <td>{{ $material->price_in * $material->pivot->quantity }}</td>
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

            elementCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const elementId = this.getAttribute('data-element-id');
                    const elementMaterialsTable = document.querySelector(
                        `#element-materials-${elementId}`);

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

            // Event listener for the percentage input field
            const percentageInput = document.getElementById('percentage-input');
            percentageInput.addEventListener('input', function() {
                const inputValue = this.value.trim(); // Remove leading/trailing white spaces
                percentage = inputValue ? parseFloat(inputValue) : 0; // Use 0% if input is empty
                updateTotalProTypPrice();
            });

            // Function to update the Total Pro Typ Price and Discounted Total based on the running total and percentage
            function updateTotalProTypPrice() {
                const totalProTypPriceCell = document.getElementById('total-pro-typ-price');
                const discountedTotalCell = document.getElementById('discounted-total');

                if (totalProTypPriceCell && discountedTotalCell) {
                    const totalProTypPrice = runningTotalMaterialsPrice;
                    const discountedTotal = totalProTypPrice * (1 - (percentage / 100));

                    totalProTypPriceCell.textContent = totalProTypPrice.toFixed(2); // Format as desired
                    discountedTotalCell.textContent = discountedTotal.toFixed(2); // Format as desired
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
            elementCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const elementId = this.getAttribute('data-element-id');
                    const elementMaterialsTable = document.querySelector(
                        `#element-materials-${elementId}`);

                    if (elementMaterialsTable) {
                        elementMaterialsTable.style.display = this.checked ? 'block' : 'none';
                    }
                });
            });
        });
    </script>
</body>

</html>
