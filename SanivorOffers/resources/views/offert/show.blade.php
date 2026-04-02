<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<style>
    #clientSearch {
        width: 100%;
        padding: 10px;
        font-size: 18px;
        border: 1px solid #ccc;
    }

    #searchResults {
        margin-left: -7.5%;
        max-height: 200px;
        overflow-y: auto;
    }

    .list-group-item {
        cursor: pointer;
        border: none;
        background-color: #f9f9f9;
    }

    .list-group-item:hover {
        background-color: #e0e0e0;
    }
</style>

<body>
    @include('layouts.sidebar')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="font-weight-bold">Offert</h3>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="id">Offerte NR.</label>
                                <input type="text" class="form-control" id="id" name="id"
                                    value="{{ $offert->id }}" disabled>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="type">Offerte Typ</label>
                                <select class="form-control" name="type" disabled>
                                    <option value="client" @if ($offert->type == 'client') selected @endif>Client
                                    </option>
                                    <option value="company" @if ($offert->type == 'company') selected @endif>Company
                                    </option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="user_sign">Unser Zeichen</label>
                                <input type="text" class="form-control" id="user_sign" name="user_sign"
                                    value="{{ $offert->user_sign }}" disabled>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="status">Status</label>
                                <select class="form-control" name="status" disabled>
                                    <option value="new" @if ($offert->status == 'new') selected @endif>Neu -
                                        In progress</option>
                                    <option value="finished" @if ($offert->status == 'finished') selected @endif>
                                        Finished</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="create_date">Angebot Datum</label>
                                <input type="date" class="form-control" id="create_date" name="create_date"
                                    value="{{ $offert->create_date }}" disabled>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="validity">Angebot Gültigkeit</label>
                                <input type="text" class="form-control" id="validity" name="validity"
                                    value="{{ $offert->validity }}" disabled>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="client_sign">Ihr Zeichen</label>
                                <input type="text" class="form-control" id="client_sign" name="client_sign"
                                    value="{{ $offert->client_sign }}"disabled>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="finish_date">vom</label>
                                <input type="date" class="form-control" id="finish_date" name="finish_date"
                                    value="{{ $offert->finish_date }}" disabled>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="object">Object</label>
                                <input type="text" class="form-control" id="object" name="object"
                                    value="{{ $offert->object }}" disabled>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="city">Ort</label>
                                <input type="text" class="form-control" id="city" name="city"
                                    value="{{ $offert->city }}" disabled>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="service">Lieferung</label>
                                <input type="text" class="form-control" id="service" name="service"
                                    value="{{ $offert->service }}" disabled>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="payment_conditions">Zahlungskonditionens</label>
                                <input type="text" class="form-control" id="payment_conditions"
                                    name="payment_conditions" value="{{ $offert->payment_conditions }}" disabled>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="client_id">Kunde</label>
                                <div class="autocomplete">
                                    <input type="text" id="clientSearch" value="{{ $offert->client->name }}"
                                        disabled>
                                    <input type="hidden" name="client_id" id="client_id">
                                </div>
                                <ul id="searchResults"></ul>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="difficulty">Schwierigkeits-Koeff</label>
                                <input type="text" class="form-control" id="difficulty" name="difficulty"
                                    value="{{ $offert->difficulty }}" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="material">Material-Koeff.</label>
                                <input type="text" class="form-control" id="material" name="material"
                                    value="{{ $offert->material }}" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="labor_price">Stundenansatz</label>
                                <input type="text" class="form-control" id="labor_price" name="labor_price"
                                    value="{{ $offert->labor_price }}" disabled>
                            </div>
                        </div>
                        <a href="{{ route('offert.index') }}" class="btn btn-secondary mt-3">Back</a>
                        <a href="{{ route('offert.edit', $offert->id) }}" class="btn btn-info mt-3 float-right">Edit Offert</a>
                    </div>
                    @foreach ($offert->positions as $position)<br>
                        <h4>Position {{ $position->position_number }}:  Price {{ $position->price_discount }}</h4>
                            <div class="card">
                                <div class="card-body">
                                    @foreach ($position->elements as $element)
                                        {{ $element->name }}<br>
                                        @foreach ($element->materials as $material)
                                            <br>
                                            {{ $material->pivot->quantity }}{{ $material->unit }} {{ $material->name }}
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</body>

</html>
