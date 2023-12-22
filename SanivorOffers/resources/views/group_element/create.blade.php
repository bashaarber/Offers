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

                            <div class="form-row">
                                    <label for="materials">Elements:</label>
                                    <select class="select-elements form-control"
                                        name="materials[]">
                                        @foreach ($elements as $element)
                                            <option value="{{ $element->id }}">{{ $element->name }}
                                            </option>
                                        @endforeach
                                    </select>
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select-elements').select2({
                multiple: true
            });
            // Clear the selection after initialization
            $('.select-elements').val(null).trigger('change');
        });
    </script>
</body>

</html>
