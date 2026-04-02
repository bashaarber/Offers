<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    h6 {
        background-color: skyblue;
        padding: 10px;
    }

    .select-users+.select2-container .select2-selection--single {
        height: 35px;
    }
</style>

<body>
    @include('layouts.sidebar')
    <div class="content">
        <div class="container mt-4">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                        <h6>Projektinformationen</h6>
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
                                        <label for="type">Offerte Typ</label>
                                        <select class="form-control" name="type" required>
                                            <option value="client" @if ($offert->type == 'client') selected @endif>
                                                Client
                                            </option>
                                            <option value="company" @if ($offert->type == 'company') selected @endif>
                                                Company</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="user_sign">Unser Zeichen</label>
                                        <input type="text" class="form-control" id="user_sign" name="user_sign"
                                            value="{{ $offert->user_sign }}" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="status">Status</label>
                                        <select class="form-control" name="status" required>
                                            <option value="Neu" @if ($offert->status == 'Neu - In progress') selected @endif>Neu
                                                -
                                                In progress</option>
                                                <option value="Zusage" @if ($offert->status == 'Zusage') selected @endif>
                                                    Zusage</option>
                                                    <option value="Abszage" @if ($offert->status == 'Abszage') selected @endif>
                                                        Abszage</option>
                                            <option value="Finished" @if ($offert->status == 'Finished') selected @endif>
                                                Finished</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label for="create_date">Angebot Datum</label>
                                        <input type="date" class="form-control" id="create_date" name="create_date"
                                            value="{{ $offert->create_date }}" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="validity">Angebot Gültigkeit</label>
                                        <input type="text" class="form-control" id="validity" name="validity"
                                            value="{{ $offert->validity }}" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="client_sign">Ihr Zeichen</label>
                                        <input type="text" class="form-control" id="client_sign" name="client_sign"
                                            value="{{ $offert->client_sign }}"required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="finish_date">vom</label>
                                        <input type="date" class="form-control" id="finish_date" name="finish_date"
                                            value="{{ $offert->finish_date }}">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label for="object">Objekt</label>
                                        <input type="text" class="form-control" id="object" name="object"
                                            value="{{ $offert->object }}" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="city">Ort</label>
                                        <input type="text" class="form-control" id="city" name="city"
                                            value="{{ $offert->city }}" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="service">Lieferung</label>
                                        <input type="text" class="form-control" id="service" name="service"
                                            value="{{ $offert->service }}" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="payment_conditions">Zahlungskonditionen</label>
                                        <input type="text" class="form-control" id="payment_conditions"
                                            name="payment_conditions" value="{{ $offert->payment_conditions }}"
                                            required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="clients">Kunde</label>
                                        <select style="width: 100%" class="select-users form-control"
                                            name="client_id" required>
                                            @foreach ($clients as $client)
                                                <option value="{{ $client->id }}"
                                                    {{ $client->id == $offert->client_id ? 'selected' : '' }}>
                                                    {{ $client->name ? $client->name : $client->email }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <h6>Koeffizienten für dieses Project</h6>

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="difficulty">Schwierigkeits-Koeff</label>
                                        <input type="text" class="form-control" id="difficulty" name="difficulty"
                                            value="{{ $offert->difficulty }}" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="material">Material-Koeff.</label>
                                        <input type="text" class="form-control" id="material" name="material"
                                            value="{{ $offert->material }}" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="labor_price">Stundenansatz</label>
                                        <input type="text" class="form-control" id="labor_price"
                                            name="labor_price" value="{{ $offert->labor_price }}" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Update Offert</button>
                                <a href="{{ route('offert.index') }}" class="btn btn-secondary mt-3">Back</a>
                                <a href="{{ route('offert.show', $offert->id) }}"
                                    class="btn btn-info mt-3 float-right">View Offert</a>
                            </form>
                        </div>
                        {{-- @foreach ($offert->positions as $position)
                            <br>
                            <h4>Position {{ $position->position_number }}: Price {{ $position->price_discount }}</h4>
                            <div class="card">
                                <div class="card-body">
                                    @foreach ($position->elements as $element)
                                        <strong>- {{ $element->name }}</strong><br>
                                        @foreach ($element->materials as $material)
                                            {{ $material->pivot->quantity }}{{ $material->unit }}
                                            {{ $material->name }}<br>
                                        @endforeach
                                        <hr>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach --}}
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
            $('.select-users').select2();
        });
    </script>
</body>

</html>
