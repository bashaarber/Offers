<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Element</title>
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
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card">
                    <div class="card-body">
                        <h3 class="font-weight-bold">Edit Element</h3>
                        <form method="POST" action="{{ route('element.update', $element->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ $element->name }}" required>
                            </div>
                            <div class="form-group">
                                <label>Materials:</label>
                                <div id="materials-list">
                                    @forelse ($element->materials as $material)
                                        <div class="input-group mb-2">
                                            <select class="select-material" name="materials[]" required>
                                                <option value="">-- Select Material --</option>
                                                @foreach ($materials as $materialOption)
                                                    <option value="{{ $materialOption->id }}"
                                                        {{ $materialOption->id == $material->id ? 'selected' : '' }}>
                                                        {{ $materialOption->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="text" min="0" class="form-control quantity-input"
                                                name="quantities[]" value="{{ $material->pivot->quantity }}"
                                                placeholder="QTY">
                                            <button type="button" class="btn btn-danger remove-material"><i
                                                    class="fa-solid fa-minus"></i></button>
                                        </div>
                                    @empty
                                        <div class="input-group mb-2">
                                            <select class="select-material" name="materials[]" required>
                                                <option value="">-- Select Material --</option>
                                                @foreach ($materials as $materialOption)
                                                    <option value="{{ $materialOption->id }}">
                                                        {{ $materialOption->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="text" min="0" class="form-control quantity-input"
                                                name="quantities[]" value=""
                                                placeholder="QTY">
                                            <button type="button" class="btn btn-danger remove-material"><i
                                                    class="fa-solid fa-minus"></i></button>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary" id="add-material"><i
                                    class="fa-solid fa-plus"></i></button>
                            <button type="submit" class="btn btn-primary">Update Element</button>
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
                @foreach ($materials as $materialOption)
                    <option value="{{ $materialOption->id }}">
                        {{ $materialOption->name }}
                    </option>
                @endforeach
            </select>
            <input type="text" min="0" class="form-control quantity-input"
                name="quantities[]" value=""
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
        });
    </script>
</body>

</html>
