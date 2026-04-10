<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Offert</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<style>
    h6 {
        background-color: skyblue;
        padding: 10px;
    }
    .select-users + .select2-container .select2-selection--single {
        height: 35px;
    }

    .rabatt-section-card {
        border: 1px solid #dbeafe;
        border-radius: 8px;
        background: #f8fbff;
        padding: 14px 16px 8px;
        margin-bottom: 12px;
    }

    .rabatt-input {
        -moz-appearance: textfield;
    }

    .rabatt-input::-webkit-outer-spin-button,
    .rabatt-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
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

                        <form action="{{ route('offert.store') }}" method="POST">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="id">Offerte NR.</label>
                                    <input type="text" class="form-control" value="{{ $newOffertId }}" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="type">Offerte Typ</label>
                                    <select class="form-control" name="type" required>
                                        <option value="client">Client</option>
                                        <option value="company">Company</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="user_sign">Unser Zeichen</label>
                                    <input type="text" class="form-control" id="user_sign" name="user_sign" value="Blerant Kqiku" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="status">Status</label>
                                    <select class="form-control" name="status" required>
                                        <option value="Neu">Neu - In progress</option>
                                        <option value="Zusage">Zusage</option>
                                        <option value="Abszage">Abszage</option>
                                        <option value="Finished">Finished</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="create_date">Angebot Datum</label>
                                    <input type="date" class="form-control" id="create_date" name="create_date"
                                        required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="validity">Angebot Gültigkeit</label>
                                    @foreach ($coefficients as $coefficient)
                                        <input type="text" class="form-control" id="validity" name="validity"
                                            value="{{ $coefficient->validity }}" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="client_sign">Ihr Zeichen</label>
                                    <input type="text" class="form-control" id="client_sign" name="client_sign"
                                        required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="finish_date">vom</label>
                                    <input type="date" class="form-control" id="finish_date" name="finish_date"
                                        value="{{ old('finish_date', date('Y-m-d')) }}">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="object">Objekt</label>
                                    <input type="text" class="form-control" id="object" name="object" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="city">Ort</label>
                                    <input type="text" class="form-control" id="city" name="city" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="service">Lieferung</label>
                                    <input type="text" class="form-control" id="service" name="service"
                                        value="{{ $coefficient->service }}" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="payment_conditions">Zahlungskonditionen</label>
                                    <input type="text" class="form-control" id="payment_conditions"
                                        name="payment_conditions" value="{{ $coefficient->payment_conditions }}"
                                        required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="clients">Kunde</label>
                                    <select style="width: 100%" class="select-users form-control" name="client_id" required>
                                        @foreach ($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->name ? $client->name : $client->email }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <h6>Koeffizienten für dieses Project</h6>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="difficulty">Schwierigkeits-Koeff.</label>
                                    <input type="text" class="form-control" id="difficulty" name="difficulty"
                                        value="{{ $coefficient->difficulty }}" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="material">Material-Koeff.</label>
                                    <input type="text" class="form-control" id="material" name="material"
                                        value="{{ $coefficient->material }}" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="labor_price">Stundenansatz</label>
                                    <input type="text" class="form-control" id="labor_price" name="labor_price"
                                        value="{{ $coefficient->labor_price }}" required>
                                </div>
                            </div>
                            <h6>Standard Rabatt</h6>
                            <div class="rabatt-section-card">
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="default_rabatt">Rabat % (Standard für alle Positionen)</label>
                                        <input type="text" class="form-control rabatt-input" id="default_rabatt"
                                            name="default_rabatt"
                                            value="{{ old('default_rabatt', $coefficient->default_rabatt ?? 0) }}"
                                            inputmode="decimal" placeholder="0.00">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            <button type="submit" class="btn btn-primary mt-3">Create Offert</button>
                            <a href="{{ route('offert.index') }}" class="btn btn-secondary mt-3">Back</a>
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
            $('.select-users').select2();
            // Clear the selection after initialization
            $('.select-users').val(null).trigger('change');
        });

        document.addEventListener("DOMContentLoaded", function() {
        // Get today's date
        var today = new Date();

        // Format date as YYYY-MM-DD
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
        var yyyy = today.getFullYear();

        today = yyyy + '-' + mm + '-' + dd;

        // Set input value to today's date
        document.getElementById('create_date').value = today;
    });
    </script>
</body>
</html>
