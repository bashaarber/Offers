<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Offert</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>

<body>
    @include('layouts.sidebar')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h3 class="font-weight-bold">Create New Offert</h3>
                <form action="{{ route('offert.store') }}" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="type">Type</label>
                            <select class="form-control" name="type" required>
                                <option value="client">Client</option>
                                <option value="company">Company</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="user_sign">User Sign</label>
                            <input type="text" class="form-control" id="user_sign" name="user_sign" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="status">Status</label>
                            <select class="form-control" name="status" required>
                                <option value="new">Neu - In progress</option>
                                <option value="finished">Finished</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="validity">Validity</label>
                            @foreach ($coefficients as $coefficient)
                                <input type="text" class="form-control" id="validity" name="validity"
                                    value="{{ $coefficient->validity }}" required>
                        </div>
                    </div>
                    <!-- Repeat the same structure for the remaining input fields -->
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="client_sign">client_sign</label>
                            <input type="text" class="form-control" id="client_sign" name="client_sign" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="object">object</label>
                            <input type="text" class="form-control" id="object" name="object" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="city">city</label>
                            <input type="text" class="form-control" id="city" name="city" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="service">service</label>
                            <input type="text" class="form-control" id="service" name="service"
                                value="{{ $coefficient->service }}" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="payment_conditions">payment_conditions</label>
                            <input type="text" class="form-control" id="payment_conditions" name="payment_conditions"
                                value="{{ $coefficient->payment_conditions }}" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="client_id">Clients</label>
                            <select class="form-control" id="client_id" name="client_id" required>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row">

                    <div class="form-group col-md-3">
                        <label for="difficulty">difficulty</label>
                        <input type="text" class="form-control" id="difficulty" name="difficulty"
                            value="{{ $coefficient->difficulty }}" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="material">material</label>
                        <input type="text" class="form-control" id="material" name="material"
                            value="{{ $coefficient->material }}" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="labor_price">labor_price</label>
                        <input type="text" class="form-control" id="labor_price" name="labor_price"
                            value="{{ $coefficient->labor_price }}" required>
                    </div>
                </div>

                    @endforeach

                    <button type="submit" class="btn btn-primary mt-3">Create Offert</button>
                    <a href="{{ route('offert.index') }}" class="btn btn-secondary mt-3">Back</a>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>
