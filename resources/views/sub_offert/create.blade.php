<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('public.create_sub_offer')</title>
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

                        @isset($parent)
                            @if($parent)
                                <div class="alert alert-info" style="border-radius:8px;">
                                    <i class="fa-solid fa-code-branch"></i>
                                    @lang('public.creating_sub_offer_for') <strong>{{ $parent->display_number }}</strong>
                                    @if($parent->object) — {{ $parent->object }} @endif
                                </div>
                            @endif
                        @endisset

                        <form action="{{ route('sub-offert.store') }}" method="POST">
                            @csrf
                            @isset($parent)
                                @if($parent)
                                    <input type="hidden" name="parent_id" value="{{ $parent->id }}">
                                @endif
                            @endisset
                            @php
                                // Header fields are NOT copied from the parent — a nested Sub-Offerte
                                // starts with the normal defaults (same as a top-level one). Only the
                                // parent's POSITIONS are copied, on store (via the parent_id above).
                                $coeff0  = $coefficients->first();
                                $pfUserSign   = old('user_sign', 'Blerant Kqiku');
                                $pfType       = old('type', 'client');
                                $pfStatus     = old('status', 'Neu');
                                $pfValidity   = old('validity', $coeff0->validity ?? '');
                                $pfClientSign = old('client_sign', '');
                                $pfObject     = old('object', '');
                                $pfCity       = old('city', '');
                                $pfService    = old('service', $coeff0->service ?? '');
                                $pfPayment    = old('payment_conditions', $coeff0->payment_conditions ?? '');
                                $pfClientId   = old('client_id');
                                $pfAddr1      = old('client_address', '');
                                $pfAddr2      = old('client_address_2', '');
                                $pfAddr3      = old('client_address_3', '');
                                $pfDifficulty = old('difficulty', $coeff0->difficulty ?? '');
                                $pfMaterial   = old('material', $coeff0->material ?? '');
                                $pfLabor      = old('labor_price', $coeff0->labor_price ?? '');
                                $pfRabatt     = old('default_rabatt', $coeff0->default_rabatt ?? 20);
                            @endphp
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="id">@lang('public.offer_number')</label>
                                    <input type="text" class="form-control" value="{{ $newOffertNumber }}" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="type">@lang('public.offer_type')</label>
                                    <select class="form-control" name="type" required>
                                        <option value="client" @selected($pfType === 'client')>Client</option>
                                        <option value="company" @selected($pfType === 'company')>Company</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="user_sign">@lang('public.our_reference')</label>
                                    <input type="text" class="form-control" id="user_sign" name="user_sign" value="{{ $pfUserSign }}" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="status">@lang('public.status')</label>
                                    <select class="form-control" name="status" required>
                                        <option value="Neu" @selected($pfStatus === 'Neu')>Neu - In progress</option>
                                        <option value="Zusage" @selected($pfStatus === 'Zusage')>Zusage</option>
                                        <option value="Abszage" @selected($pfStatus === 'Abszage')>Abszage</option>
                                        <option value="Finished" @selected($pfStatus === 'Finished')>Finished</option>
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
                                            value="{{ $pfValidity }}" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="client_sign">@lang('public.your_reference')</label>
                                    <input type="text" class="form-control" id="client_sign" name="client_sign"
                                        value="{{ $pfClientSign }}" required>
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
                                    <input type="text" class="form-control" id="object" name="object" value="{{ $pfObject }}" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="city">@lang('public.city')</label>
                                    <input type="text" class="form-control" id="city" name="city" value="{{ $pfCity }}" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="service">@lang('public.delivery')</label>
                                    <input type="text" class="form-control" id="service" name="service"
                                        value="{{ $pfService }}" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="payment_conditions">@lang('public.payment_terms')</label>
                                    <input type="text" class="form-control" id="payment_conditions"
                                        name="payment_conditions" value="{{ $pfPayment }}"
                                        required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="clients">@lang('public.client')</label>
                                    <select style="width: 100%" class="select-users form-control" id="client_id" name="client_id" required>
                                        @foreach ($clients as $client)
                                            <option value="{{ $client->id }}" @selected((string) $pfClientId === (string) $client->id)>{{ $client->name ? $client->name : $client->email }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="client_address">@lang('public.address_1')</label>
                                    <input type="text" class="form-control" id="client_address" name="client_address"
                                        value="{{ $pfAddr1 }}">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="client_address_2">@lang('public.address_2')</label>
                                    <input type="text" class="form-control" id="client_address_2" name="client_address_2"
                                        value="{{ $pfAddr2 }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="client_address_3">@lang('public.address_3')</label>
                                    <input type="text" class="form-control" id="client_address_3" name="client_address_3"
                                        value="{{ $pfAddr3 }}">
                                </div>
                            </div>

                            <h6>@lang('public.coefficients_project')</h6>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="difficulty">@lang('public.difficulty_coeff')</label>
                                    <input type="text" class="form-control" id="difficulty" name="difficulty"
                                        value="{{ $pfDifficulty }}" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="material">@lang('public.material_coeff')</label>
                                    <input type="text" class="form-control" id="material" name="material"
                                        value="{{ $pfMaterial }}" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="labor_price">@lang('public.hourly_rate')</label>
                                    <input type="text" class="form-control" id="labor_price" name="labor_price"
                                        value="{{ $pfLabor }}" required>
                                </div>
                            </div>
                            <h6>@lang('public.default_discount')</h6>
                            <div class="rabatt-section-card">
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="default_rabatt">@lang('public.discount_percent_label')</label>
                                        <input type="text" class="form-control rabatt-input" id="default_rabatt"
                                            name="default_rabatt"
                                            value="{{ $pfRabatt }}"
                                            inputmode="decimal" placeholder="0.00">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            <button type="submit" class="btn btn-primary mt-3">@lang('public.create_sub_offer')</button>
                            <a href="{{ route('sub-offert.index') }}" class="btn btn-secondary mt-3">@lang('public.back')</a>
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
            // Client is never pre-selected — the user must choose or add one.
            $('.select-users').val(null).trigger('change');
        });

        @php
            $clientAddressById = $clients->mapWithKeys(fn ($client) => [$client->id => [
                'a1' => $client->address ?? '',
                'a2' => $client->address_2 ?? '',
                'a3' => $client->address_3 ?? '',
            ]])->toArray();
        @endphp
        const clientAddressById = @json($clientAddressById);

        function syncClientAddressFromSelection() {
            const clientSelect = document.getElementById('client_id');
            if (!clientSelect) return;
            const data = clientAddressById[clientSelect.value] || { a1: '', a2: '', a3: '' };
            const a1 = document.getElementById('client_address');
            const a2 = document.getElementById('client_address_2');
            const a3 = document.getElementById('client_address_3');
            if (a1) a1.value = data.a1 || '';
            if (a2) a2.value = data.a2 || '';
            if (a3) a3.value = data.a3 || '';
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
        // Addresses follow the chosen client (empty until one is selected).
        syncClientAddressFromSelection();
    });
    </script>
</body>
</html>
