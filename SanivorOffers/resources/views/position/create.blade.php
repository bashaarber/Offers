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
        .position{
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
                            <th scope="col"> Blocktyp <input> cm  </th>
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
                                    <td>% <input></td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                </tr>
                                <tr class="table-active">
                                    <td><strong>Zeit Pro Typ</strong></td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>% <input></td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                </tr>
                                <tr class="table-secondary">
                                    <td><strong>Total Pro Typ</strong></td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>% <input></td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                </tr>
                                <tr class="table-dark">
                                    <td>Menge <input></td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>% <input></td>
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
                    @foreach ($element->materials as $material)
                        <table class="table element-materials" id="element-materials-{{ $element->id }}"
                            style="display: none">
                            <thead>
                                <tr>
                                    <th scope="col">Ans.</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">PStk.</th>
                                    <th scope="col">Total CHF</th>
                                </tr>
                                <thead>
                                    <tr class="table-dark">
                                        <th scope="col"><input style="width: 100px"></th>
                                        <th scope="col">{{ $element->name }}</th>
                                        <th scope="col">CHF 166.31 X 1</th>
                                        <th scope="col">166.31</th>
                                    </tr>
                                </thead>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>mit <input style="width: 60px"> {{ $material->unit }}</td>
                                    <td>{{ $element->name }}</td>
                                    <td>CHF 128.75 X 1 {{ $material->unit }}</td>
                                </tr>
                                <tr>
                                    <td>mit <input style="width: 60px"> {{ $material->unit }}</td>
                                    <td>{{ $material->name }} X {{$material->pivot->quantity}} </td>
                                    <td>CHF {{ $material->price_in }} X {{$material->pivot->quantity}} {{ $material->unit }}</td>
                                    <td>{{ $material->price_in * $material->pivot->quantity}}</td>

                                </tr>

                            </tbody>
                        </table>
                    @endforeach
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
