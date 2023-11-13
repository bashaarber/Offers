<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    @include('layouts.sidebar')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="font-weight-bold">Update Material</h3>
                        <form action="{{ route('material.update', $material->id) }}" method="post">
                            @csrf
                            @method('put')
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $material->name }}" required>
                            </div>

                            <div class="form-group">
                                <label for="materials">Material Pieces:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="material-search"
                                        placeholder="Search Material Pieces">
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary" type="button" id="clear-search">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <select class="form-control" id="materials" name="materials[]" multiple>
                                    @foreach ($material_pieces as $material_piece)
                                        <option value="{{ $material_piece->id }}">{{ $material_piece->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-success" id="add-material">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-plus-circle-fill">
                                        <path
                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                            <div class="form-group">
                                <label for="materials">Added MaterialPieces List:</label>
                                <select class="form-control" id="added-materials" name="added-materials[]" multiple>
                                    @foreach ($material->material_pieces as $material_piece)
                                        <option value="{{ $material_piece->id }}">{{ $material_piece->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-danger" id="remove-selected">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-dash-circle-fill">
                                        <path
                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM4.5 7.5a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7z" />
                                    </svg>
                                </button>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="unit">Unit</label>
                                    <select class="form-control" name="unit" aria-label="Default select example" required>
                                        <option value="st" @if($material->unit == 'st') selected @endif>St</option>
                                        <option value="m" @if($material->unit == 'm') selected @endif>m</option>
                                        <option value="m2" @if($material->unit == 'm2') selected @endif>m2</option>
                                        <option value="std" @if($material->unit == 'std') selected @endif>Std</option>
                                    </select>
                                    </select>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="text-center">Prise (CHF)</h5>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="price_in">In</label>
                                            <input type="text" class="form-control" name="price_in" id="price_in" value="{{ $material->price_in }}" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="price_out">Out</label>
                                            <input type="text" class="form-control" name="price_out" id="price_out" value="{{ $material->price_out }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mt-3">
                                <div class="card-body">
                                    <h5 class="text-center">Zeit (uhr)</h5>
                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label for="z_schlosserei">Schlosserei</label>
                                            <input type="text" class="form-control" name="z_schlosserei" id="z_schlosserei" value="{{ $material->z_schlosserei }}" required>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="z_pe">Pe</label>
                                            <input type="text" class="form-control" name="z_pe" id="z_pe" value="{{ $material->z_pe }}" required>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="z_montage">Montage</label>
                                            <input type="text" class="form-control" name="z_montage" id="z_montage" value="{{ $material->z_montage }}" required>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="z_fermacell">Fermacell</label>
                                            <input type="text" class="form-control" name="z_fermacell" id="z_fermacell" value="{{ $material->z_fermacell }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Update Material</button>
                            <a href="{{ route('material.index') }}" class="btn btn-secondary mt-3">Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            const addedMaterials = {!! json_encode(
                $material->material_pieces()->pluck('id')->all(),
            ) !!};

            // Function to update the Material List options based on the search input
            function filterMaterialList(searchText) {
                const materialsSelect = $('#materials');
                materialsSelect.find('option').each(function() {
                    const materialName = $(this).text();
                    if (materialName.toLowerCase().includes(searchText.toLowerCase())) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }

            // Search for materials
            $('#material-search').on('input', function() {
                const searchText = $(this).val();
                filterMaterialList(searchText);
            });

            // Clear the search input
            $('#clear-search').click(function() {
                $('#material-search').val('');
                filterMaterialList('');
            });

            // Add material to the Added Material List
            // Add material to the Added Material List
            $('#add-material').click(function() {
                const selectedMaterials = $('#materials').val();
                if (selectedMaterials) {
                    selectedMaterials.forEach(materialId => {
                        if (!addedMaterials.includes(parseInt(materialId))) {
                            addedMaterials.push(parseInt(materialId));
                        }
                    });
                    updateAddedMaterialsList();
                }
            });


            // Function to update the Added Material List
            function updateAddedMaterialsList() {
                const addedMaterialsSelect = $('#added-materials');
                addedMaterialsSelect.empty();

                addedMaterials.forEach(materialId => {
                    const materialName = $(`#materials option[value='${materialId}']`).text();
                    addedMaterialsSelect.append(
                        new Option(materialName, materialId, false, false)
                    );
                });
            }

            // Remove selected materials from the Added Material List
            $('#remove-selected').click(function() {
                const selectedMaterialOptions = $('#added-materials option:selected');
                selectedMaterialOptions.each(function() {
                    const materialId = $(this).val();
                    const index = addedMaterials.indexOf(parseInt(materialId));
                    if (index !== -1) {
                        addedMaterials.splice(index, 1);
                    }
                });
                updateAddedMaterialsList();
            });
            // Handle double-click to add a material
            $('#materials').dblclick(function() {
                const selectedMaterial = $('#materials option:selected');
                if (selectedMaterial.length > 0) {
                    const materialId = parseInt(selectedMaterial.val());
                    if (!addedMaterials.includes(materialId)) {
                        addedMaterials.push(materialId);
                        updateAddedMaterialsList();
                    }
                }
            });

            // Submit the form with the Added Material List
            $('form').submit(function(event) {
                if (addedMaterials.length === 0) {
                    alert('Please add at least one material to the list.');
                    event.preventDefault(); // Prevent form submission
                } else {
                    $('#added-materials option').prop('selected', true);
                }
            });

        });
    </script>
</body>

</html>