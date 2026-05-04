<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>

<body>
    @include('layouts.sidebar')
    <div class="content">
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="font-weight-bold">Create New Material</h3>
                        <form action="{{ route('material.store') }}" method="POST" id="element-form">
                            @csrf
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-row">
                                <label for="materials">Material Pieces:</label>
                                <select class="select-material-pieces form-control" name="materials[]">
                                    @foreach ($materialPieces as $materialPiece)
                                        <option value="{{ $materialPiece->id }}">{{ $materialPiece->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6 mt-3">
                                    <label for="unit">Unit</label>
                                    <select class="form-control" name="unit" required>
                                        <option value="St.">St.</option>
                                        <option value="m">m</option>
                                        <option value="m²">m²</option>
                                        <option value="Std">Std</option>
                                    </select>
                                </div>
                            </div>
                            <div class="card mt-3">
                                <div class="card-body">
                                    <h5 class="text-center">Zeit (uhr)</h5>
                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label for="z_schlosserei">Schlosserei</label>
                                            <input type="text" class="form-control" name="z_schlosserei"
                                                id="z_schlosserei" required>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="z_pe">Pe</label>
                                            <input type="text" class="form-control" name="z_pe" id="z_pe"
                                                required>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="z_montage">Montage</label>
                                            <input type="text" class="form-control" name="z_montage" id="z_montage"
                                                required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">@lang('public.create_material')</button>
                            <a href="{{ route('material.index') }}" class="btn btn-secondary mt-3">@lang('public.back')</a>
                        </form>
                    </div>
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
            $('.select-material-pieces').select2({
                multiple: true
            });
            // Clear the selection after initialization
            $('.select-material-pieces').val(null).trigger('change');
        });
    </script>
</body>

</html>
