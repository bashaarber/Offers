<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>

<body>
    @include('layouts.sidebar')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="font-weight-bold">Create New GroupElement</h3>
                        <form action="{{ route('group_element.store') }}" method="POST"  id="element-form">
                            @csrf

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>


                            <div class="form-group">
                                <label for="materials">Elements:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="material-search" placeholder="Search Materials">
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary" type="button" id="clear-search">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <select class="form-control" id="materials" name="materials[]" multiple required>
                                    <!-- Options will be dynamically filtered here -->
                                    @foreach($elements as $element)
                                    <option value="{{ $element->id }}">{{ $element->name }}</option>
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
                                <label for="materials">Added Elements List:</label>
                                <select class="form-control" id="added-materials" name="added-materials[]" multiple>
                                    <!-- Added materials will be dynamically added as options here -->
                                </select>
                                <button type="button" class="btn btn-danger" id="remove-selected">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-dash-circle-fill">
                                        <path
                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM4.5 7.5a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7z" />
                                    </svg>
                                </button>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Create GroupElement</button>
                            <a href="{{ route('group_element.index') }}" class="btn btn-secondary mt-3">Back</a>
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
            const addedMaterials = [];

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
            $('#add-material').click(function() {
                const selectedMaterials = $('#materials').val();
                if (selectedMaterials) {
                    selectedMaterials.forEach(materialId => {
                        if (!addedMaterials.includes(materialId)) {
                            addedMaterials.push(materialId);
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
                    const index = addedMaterials.indexOf(materialId);
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
                    const materialId = selectedMaterial.val();
                    if (!addedMaterials.includes(materialId)) {
                        addedMaterials.push(materialId);
                        updateAddedMaterialsList();
                    }
                }
            });

            // Submit the form with the Added Material List
            $('#element-form').submit(function(event) {
                $('#materials').val(addedMaterials);
            });
        });
    </script>
</body>

</html>
