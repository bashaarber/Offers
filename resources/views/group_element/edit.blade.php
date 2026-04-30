<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GroupElement</title>
</head>

<body>
    @include('layouts.sidebar')
    <div class="content">
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="font-weight-bold">Update Group Element</h3>
                        <form action="{{ route('group_element.update', $group_element->id) }}" method="post">
                            @csrf
                            @method('put')

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ $group_element->name }}" required>
                            </div>

                            <div class="form-row">
                                    <label for="materials">Elements:</label>
                                    <select class="select-elements form-control"name="materials[]" multiple required>
                                        @foreach ($elements as $element)
                                                <option value="{{ $element->id }}"
                                                    {{ in_array($element->id, $selectedElements) ? 'selected' : '' }}>
                                                    {{ $element->name }}
                                            </option>
                                        @endforeach
                                    </select>
                            </div>
                            
                            <button type="submit" class="btn btn-primary mt-3">@lang('public.save')</button>
                            <a href="{{ route('group_element.index') }}" class="btn btn-secondary mt-3">@lang('public.back')</a>
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
            $('.select-elements').select2({
                multiple: true
            });
        });
    </script>
</body>

</html>
