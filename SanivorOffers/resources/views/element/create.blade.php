<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Element</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<style>
      .select-material + .select2-container .select2-selection--single {
        height: 38px;
    }
</style>
<body>
    @include('layouts.sidebar')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h3 class="font-weight-bold">Create New Element</h3>
                        <form method="POST" action="{{ route('element.store') }}">
                            @csrf
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-group" id="materials-list">
                                <label for="materials">Materials:</label>
                                <div class="input-group mb-2">
                                    <select class="select-material" name="materials[]" required>
                                        @foreach ($materials as $material)
                                            <option value="{{ $material->id }}">{{ $material->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="text" min="0" class="form-control" name="quantities[]" placeholder="Quantity">
                                    <button type="button" class="btn btn-danger remove-material"><i class="fa-solid fa-minus"></i></button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary" id="add-material"><i class="fa-solid fa-plus"></i></button>
                            <button type="submit" class="btn btn-primary">Create Element</button>
                               <a href="{{ route('element.index') }}" class="btn btn-secondary">Back</a>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#add-material').click(function() {
                const newMaterialField = $('#materials-list .input-group:first').clone();
                newMaterialField.find('select').val('material_id_1'); // Set the default material
                newMaterialField.find('input').val(''); // Clear the quantity input
    
                // Remove Select2 from the cloned select element
                newMaterialField.find('.select-material').removeClass('select2-hidden-accessible').next().remove();
                newMaterialField.appendTo('#materials-list');
    
                // Reinitialize Select2 for all select elements
                $('.select-material').select2();
            });
    
            $('#materials-list').on('click', '.remove-material', function() {
                $(this).closest('.input-group').remove();
            });
    
            // Initialize Select2 for the first material select element
            $('.select-material').select2();
    
            $('.select-material').val(null).trigger('change');
        });
    </script>
</body>
</html>
