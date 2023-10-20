<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    @include('layouts.sidebar')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="font-weight-bold">Update Offert</h3>
                        <form action="{{ route('offert.update', $offert->id) }}" method="post">
                            @csrf
                            @method('put')
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="id">Offerte NR.</label>
                                    <input type="text" class="form-control" id="id" name="id"
                                        value="{{ $offert->id }}" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="type">Type</label>
                                    <select class="form-control" name="type" required>
                                        <option value="client" @if($offert->type == 'client') selected @endif>Client</option>
                                        <option value="company" @if($offert->type == 'company') selected @endif>Company</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="user_sign">User Sign</label>
                                    <input type="text" class="form-control" id="user_sign" name="user_sign"
                                        value="{{ $offert->user_sign }}" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="status">Status</label>
                                    <select class="form-control" name="status" required>
                                        <option value="new" @if ($offert->status == 'new') selected @endif>Neu -
                                            In progress</option>
                                        <option value="finished" @if ($offert->status == 'finished') selected @endif>
                                            Finished</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="create_date">created_date</label>
                                    <input type="date" class="form-control" id="create_date" name="create_date" value="{{ $offert->create_date }}" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="validity">Validity</label>
                                    <input type="text" class="form-control" id="validity" name="validity"
                                        value="{{ $offert->validity }}" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="client_sign">client_sign</label>
                                    <input type="text" class="form-control" id="client_sign" name="client_sign"
                                        value="{{ $offert->client_sign }}"required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="finish_date">finish_date</label>
                                    <input type="date" class="form-control" id="finish_date" name="finish_date" value="{{ $offert->finish_date }}">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="object">object</label>
                                    <input type="text" class="form-control" id="object" name="object"
                                        value="{{ $offert->object }}" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="city">city</label>
                                    <input type="text" class="form-control" id="city" name="city"
                                        value="{{ $offert->city }}" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="service">service</label>
                                    <input type="text" class="form-control" id="service" name="service"
                                        value="{{ $offert->service }}" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="payment_conditions">payment_conditions</label>
                                    <input type="text" class="form-control" id="payment_conditions"
                                        name="payment_conditions" value="{{ $offert->payment_conditions }}" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="client_id">Clients</label>
                                    <select class="form-control" id="client_id" name="client_id" required>
                                        @foreach ($clients as $client)
                                            <option value="{{ $client->id }}" {{ $client->id == $offert->client_id ? 'selected' : '' }}>
                                                {{ $client->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="difficulty">difficulty</label>
                                    <input type="text" class="form-control" id="difficulty" name="difficulty"
                                        value="{{ $offert->difficulty }}" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="material">material</label>
                                    <input type="text" class="form-control" id="material" name="material"
                                        value="{{ $offert->material }}" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="labor_price">labor_price</label>
                                    <input type="text" class="form-control" id="labor_price" name="labor_price"
                                        value="{{ $offert->labor_price }}" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Update Client</button>
                            <a href="{{ route('offert.index') }}" class="btn btn-secondary mt-3">Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>
