<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('public.create_offer')</title>
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
                        <h6>@lang('public.project_information')</h6>

                        <form action="{{ route('offert.store') }}" method="POST">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="id">@lang('public.offer_number')</label>
                                    <input type="text" class="form-control" value="{{ $newOffertId }}" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="type">@lang('public.offer_type')</label>
                                    <select class="form-control" name="type" required>
                                        <option value="client">Client</option>
                                        <option value="company">Company</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="user_sign">@lang('public.our_reference')</label>
                                    <input type="text" class="form-control" id="user_sign" name="user_sign" value="Blerant Kqiku" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="status">@lang('public.status')</label>
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
                                    <label for="create_date">@lang('public.offer_date')</label>
                                    <input type="date" class="form-control" id="create_date" name="create_date"
                                        required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="validity">@lang('public.offer_validity')</label>
                                    @foreach ($coefficients as $coefficient)
                                        <input type="text" class="form-control" id="validity" name="validity"
                                            value="{{ $coefficient->validity }}" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="client_sign">@lang('public.your_reference')</label>
                                    <input type="text" class="form-control" id="client_sign" name="client_sign"
                                        required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="finish_date">@lang('public.from')</label>
                                    <input type="date" class="form-control" id="finish_date" name="finish_date"
                                        value="{{ old('finish_date', date('Y-m-d')) }}">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="object">@lang('public.object')</label>
                                    <input type="text" class="form-control" id="object" name="object" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="city">@lang('public.city')</label>
                                    <input type="text" class="form-control" id="city" name="city" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="service">@lang('public.delivery')</label>
                                    <input type="text" class="form-control" id="service" name="service"
                                        value="{{ $coefficient->service }}" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="payment_conditions">@lang('public.payment_terms')</label>
                                    <input type="text" class="form-control" id="payment_conditions"
                                        name="payment_conditions" value="{{ $coefficient->payment_conditions }}"
                                        required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="clients">@lang('public.client')</label>
                                    <select style="width: 100%" class="select-users form-control" id="client_id" name="client_id" required>
                                        @foreach ($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->name ? $client->name : $client->email }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="client_address">@lang('public.client_address')</label>
                                    <input type="text" class="form-control" id="client_address" name="client_address"
                                        value="{{ old('client_address') }}">
                                </div>
                            </div>

                            <h6>@lang('public.coefficients_project')</h6>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="difficulty">@lang('public.difficulty_coeff')</label>
                                    <input type="text" class="form-control" id="difficulty" name="difficulty"
                                        value="{{ $coefficient->difficulty }}" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="material">@lang('public.material_coeff')</label>
                                    <input type="text" class="form-control" id="material" name="material"
                                        value="{{ $coefficient->material }}" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="labor_price">@lang('public.hourly_rate')</label>
                                    <input type="text" class="form-control" id="labor_price" name="labor_price"
                                        value="{{ $coefficient->labor_price }}" required>
                                </div>
                            </div>
                            <h6>@lang('public.default_discount')</h6>
                            <div class="rabatt-section-card">
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="default_rabatt">@lang('public.discount_percent_label')</label>
                                        <input type="text" class="form-control rabatt-input" id="default_rabatt"
                                            name="default_rabatt"
                                            value="{{ old('default_rabatt', $coefficient->default_rabatt ?? 20) }}"
                                            inputmode="decimal" placeholder="0.00">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            <button type="submit" class="btn btn-primary mt-3">@lang('public.create_offer')</button>
                            <a href="{{ route('offert.index') }}" class="btn btn-secondary mt-3">@lang('public.back')</a>
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

        const clientAddressById = @json($clients->mapWithKeys(fn($client) => [$client->id => $client->address ?? ''])->toArray());

        function syncClientAddressFromSelection() {
            const clientSelect = document.getElementById('client_id');
            const addressInput = document.getElementById('client_address');
            if (!clientSelect || !addressInput) return;
            const selectedId = clientSelect.value;
            addressInput.value = selectedId ? (clientAddressById[selectedId] || '') : '';
        }

        $(document).on('change', '#client_id', function() {
            syncClientAddressFromSelection();
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
        syncClientAddressFromSelection();
    });
    </script>
</body>
</html>
