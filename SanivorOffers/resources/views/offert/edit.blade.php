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
                            <form id="offert-edit-form" action="{{ route('offert.update', $offert->id) }}" method="post">
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
                                <h6>Standard Rabatt</h6>
                                <div class="rabatt-section-card">
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="default_rabatt">Rabat % (Standard für alle Positionen)</label>
                                            <input type="text" class="form-control rabatt-input" id="default_rabatt"
                                                name="default_rabatt" value="{{ $offert->default_rabatt ?? 20 }}"
                                                inputmode="decimal" placeholder="0.00">
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('offert.show', $offert->id) }}"
                                    class="btn btn-info mt-3">Edit Offert</a>
                                <a href="{{ route('offert.index') }}" class="btn btn-secondary mt-3">Back</a>
                                <span id="autosave-status" class="ms-3 text-muted small" style="line-height:38px;"></span>
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

    <script>
        // Auto-save offert header fields on change/blur
        (function () {
            const autoSaveUrl = "{{ route('offert.auto-save', $offert->id) }}";
            const csrfToken   = "{{ csrf_token() }}";
            const statusEl    = document.getElementById('autosave-status');
            let saveTimer     = null;

            function showStatus(msg, color) {
                statusEl.textContent = msg;
                statusEl.style.color = color;
            }

            function collectFormData() {
                const form = document.getElementById('offert-edit-form');
                const data = new FormData(form);
                // Select2 client dropdown needs manual inclusion
                const clientId = document.getElementById('client_id');
                if (clientId && clientId.value) {
                    data.set('client_id', clientId.value);
                }
                return data;
            }

            function triggerSave() {
                clearTimeout(saveTimer);
                saveTimer = setTimeout(function () {
                    showStatus('Saving…', '#6b7280');
                    const body = new URLSearchParams();
                    body.append('_token', csrfToken);
                    body.append('_method', 'PUT');
                    const data = collectFormData();
                    data.forEach((v, k) => body.append(k, v));

                    fetch(autoSaveUrl, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                        body: body,
                    })
                    .then(r => r.json())
                    .then(json => {
                        if (json.success) {
                            showStatus('Saved ✓', '#16a34a');
                            setTimeout(() => { statusEl.textContent = ''; }, 2000);
                        } else {
                            showStatus('Save failed', '#dc2626');
                        }
                    })
                    .catch(() => showStatus('Save failed', '#dc2626'));
                }, 600); // 600 ms debounce
            }

            // Listen on all form inputs
            const form = document.getElementById('offert-edit-form');
            if (form) {
                form.addEventListener('input',  triggerSave);
                form.addEventListener('change', triggerSave);
            }
        })();
    </script>
</body>

</html>
