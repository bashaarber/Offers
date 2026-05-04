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
    <div class="content">
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="font-weight-bold">Create New Organigram</h3>
                        <form action="{{ route('organigram.store') }}" method="POST" id="element-form">
                            @csrf
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-row">
                                    <label for="materials">Group Elements:</label>
                                    <select class="select-groupElements form-control"
                                        name="materials[]">
                                        @foreach ($group_elements as $group_element)
                                            <option value="{{ $group_element->id }}">{{ $group_element->name }}
                                            </option>
                                        @endforeach
                                    </select>
                            </div>
                          

                            <button type="submit" class="btn btn-primary mt-3">@lang('public.create_organigram')</button>
                            <a href="{{ route('organigram.index') }}" class="btn btn-secondary mt-3">@lang('public.back')</a>
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
            $('.select-groupElements').select2({
                multiple: true
            });
            // Clear the selection after initialization
            $('.select-groupElements').val(null).trigger('change');
        });
    </script>
    
</body>

</html>
