<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Element</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<style>
    .select-material + .select2-container .select2-selection--single {
        height: 38px;
    }
</style>

<body>
    @include('layouts.sidebar')
    <div class="content">
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
                                <div class="form-group">
                                    <label for="materials">Materials:</label>
                                    <div id="materials-list">
                                        <div class="input-group mb-2">
                                            <select class="select-material" name="materials[]" required>
                                                <option value="">-- Select Material --</option>
                                                @foreach ($materials as $material)
                                                    <option value="{{ $material->id }}">{{ $material->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="text" min="0" class="form-control" name="quantities[]"
                                                placeholder="QTY">
                                            <button type="button" class="btn btn-danger remove-material"><i
                                                    class="fa-solid fa-minus"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary" id="add-material"><i
                                        class="fa-solid fa-plus"></i></button>
                                <button type="submit" class="btn btn-primary">Create Element</button>
                                <a href="{{ route('element.index') }}" class="btn btn-secondary">Back</a>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Hidden template for adding new material rows --}}
    <template id="material-row-template">
        <div class="input-group mb-2">
            <select class="select-material" name="materials[]" required>
                <option value="">-- Select Material --</option>
                @foreach ($materials as $material)
                    <option value="{{ $material->id }}">{{ $material->name }}
                    </option>
                @endforeach
            </select>
            <input type="text" min="0" class="form-control" name="quantities[]"
                placeholder="QTY">
            <button type="button" class="btn btn-danger remove-material"><i
                    class="fa-solid fa-minus"></i></button>
        </div>
    </template>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#add-material').click(function() {
                var template = document.getElementById('material-row-template');
                var clone = $(template.content.cloneNode(true));
                $('#materials-list').append(clone);
                $('#materials-list .select-material').last().select2();
            });

            $('#materials-list').on('click', '.remove-material', function() {
                if ($('#materials-list .input-group').length > 1) {
                    $(this).closest('.input-group').remove();
                }
            });

            $('.select-material').select2();
            $('.select-material').val(null).trigger('change');
        });
    </script>
</body>

</html>
