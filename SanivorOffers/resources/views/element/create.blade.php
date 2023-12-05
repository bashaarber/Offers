<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Element</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<style>
    
</style>
<body>
    @include('layouts.sidebar')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
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
                                <div class="input-group mb-2">
                                    <select class="form-control" name="materials[]">
                                        @foreach ($materials as $material)
                                            <option value="{{ $material->id }}">{{ $material->name }}</option>
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
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#add-material').click(function() {
                const newMaterialField = $('#materials-list .input-group:first').clone();
                newMaterialField.find('select').val('material_id_1'); // Set the default material
                newMaterialField.find('input').val(''); // Clear the quantity input
                newMaterialField.appendTo('#materials-list');
            });

            $('#materials-list').on('click', '.remove-material', function() {
                $(this).closest('.input-group').remove();
            });
        });
    </script>


</body>

</html>
