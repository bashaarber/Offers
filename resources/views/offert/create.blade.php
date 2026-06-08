<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('public.create_offer')</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

    .gross-toggle-card {
        border: 1px solid #c7d2fe;
        border-radius: 8px;
        background: #eef2ff;
        padding: 12px 16px;
        margin-bottom: 16px;
    }

    .gross-toggle-card .form-check-label {
        font-weight: 600;
        color: #3730a3;
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

                        @php $isChild = isset($parent) && $parent; @endphp

                        @if ($isChild)
                            {{-- ============================ CHILD OFFER ============================ --}}
                            <div class="alert alert-info" style="border-radius:8px;">
                                <i class="fa-solid fa-sitemap"></i>
                                @lang('public.creating_child_for') <strong>{{ $parent->display_number }}</strong>
                                @if($parent->object) — {{ $parent->object }} @endif
                            </div>

                            <form action="{{ route('offert.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="parent_id" value="{{ $parent->id }}">

                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label>@lang('public.offer_number')</label>
                                        <input type="text" class="form-control" value="{{ $newOffertNumber }}" disabled>
                                    </div>
                                    <div class="form-group col-md-9">
                                        <label>@lang('public.object')</label>
                                        <input type="text" class="form-control" value="{{ $parent->object }}" disabled>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="teil_objekt">@lang('public.teil_objekt')</label>
                                        <input type="text" class="form-control" id="teil_objekt" name="teil_objekt"
                                            value="{{ old('teil_objekt') }}" placeholder="@lang('public.teil_objekt_placeholder')" autofocus>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>@lang('public.city')</label>
                                        <input type="text" class="form-control" value="{{ $parent->city }}" disabled>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>@lang('public.client')</label>
                                        <input type="text" class="form-control" value="{{ $parent->client->name ?? '' }}" disabled>
                                    </div>
                                </div>

                                <p class="text-muted small mb-3">
                                    <i class="fa-solid fa-circle-info"></i>
                                    @lang('public.child_inherits_note')
                                </p>

                                <button type="submit" class="btn btn-primary mt-1">@lang('public.create_offer')</button>
                                <a href="{{ route('offert.index') }}" class="btn btn-secondary mt-1">@lang('public.back')</a>
                            </form>
                        @else
                            {{-- ======================= NORMAL / GROSS OFFER ======================= --}}
                            <form action="{{ route('offert.store') }}" method="POST">
                                @csrf

                                <div class="gross-toggle-card">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="is_gross" name="is_gross">
                                        <label class="form-check-label" for="is_gross">
                                            <i class="fa-solid fa-sitemap"></i> @lang('public.gross_offer')
                                        </label>
                                    </div>
                                    <small class="text-muted">@lang('public.gross_offer_hint')</small>
                                </div>

                            @foreach ($coefficients as $coefficient)
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="id">@lang('public.offer_number')</label>
                                    <input type="text" class="form-control" value="{{ $newOffertNumber }}" disabled>
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
                                    <label for="client_address">@lang('public.address_1')</label>
                                    <input type="text" class="form-control" id="client_address" name="client_address"
                                        value="{{ old('client_address') }}">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="client_address_2">@lang('public.address_2')</label>
                                    <input type="text" class="form-control" id="client_address_2" name="client_address_2"
                                        value="{{ old('client_address_2') }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="client_address_3">@lang('public.address_3')</label>
                                    <input type="text" class="form-control" id="client_address_3" name="client_address_3"
                                        value="{{ old('client_address_3') }}">
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
                        @endif
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
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
            var yyyy = today.getFullYear();
            today = yyyy + '-' + mm + '-' + dd;

            // Set input value to today's date (only present on the normal/Gross form)
            var createDateEl = document.getElementById('create_date');
            if (createDateEl) createDateEl.value = today;

            syncClientAddressFromSelection();
        });
    </script>
</body>
</html>
