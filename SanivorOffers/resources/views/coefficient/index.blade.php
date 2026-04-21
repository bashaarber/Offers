<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Coefficient List</title>
</head>
<body>
    @include('layouts.sidebar')
    <div class="content">
        <div class="container">
            <h1 class="mb-3">Coefficient</h1>
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Label</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($coefficients as $coefficient)
                        <form action="{{ route('coefficient.update', $coefficient->id) }}" method="post">
                            @csrf
                            @method('put')
                            <tr>
                                <td>Validity</td>
                                <td><input type="text" class="form-control" name="validity"
                                        value="{{ $coefficient->validity }}"></td>
                            </tr>
                            <tr>
                                <td>LaborCost</td>
                                <td><input type="text" class="form-control" name="labor_cost"
                                        value="{{ $coefficient->labor_cost }}"></td>
                            </tr>
                            <tr>
                                <td>LaborPrice</td>
                                <td><input type="text" class="form-control" name="labor_price"
                                        value="{{ $coefficient->labor_price }}"></td>
                            </tr>
                            <tr>
                                <td>In Labor Price</td>
                                <td>
                                    @if (\Illuminate\Support\Facades\Schema::hasColumn('coefficients', 'in_labor_price'))
                                        <input type="text" class="form-control" name="in_labor_price"
                                            value="{{ old('in_labor_price', $coefficient->in_labor_price ?? '') }}"
                                            inputmode="decimal" placeholder="0.00">
                                        <small class="form-text text-muted">Used in Kosto CHF formula: Price In × Mat.Coeff + Hours × In Labor Price ÷ Difficulty</small>
                                    @else
                                        <span class="text-muted">Run migrations to enable.</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Service</td>
                                <td><input type="text" class="form-control" name="service"
                                        value="{{ $coefficient->service }}"></td>
                            </tr>
                            <tr>
                                <td>Material</td>
                                <td><input type="text" class="form-control" name="material"
                                        value="{{ $coefficient->material }}"></td>
                            </tr>
                            <tr>
                                <td>Difficulty</td>
                                <td><input type="text" class="form-control" name="difficulty"
                                        value="{{ $coefficient->difficulty }}"></td>
                            </tr>
                            <tr>
                                <td>PaymentConditions</td>
                                <td><input type="text" class="form-control" name="payment_conditions"
                                        value="{{ $coefficient->payment_conditions }}"></td>
                            </tr>
                            <tr>
                                <td>Default Rabatt (%)</td>
                                <td>
                                    <input type="text" class="form-control" name="default_rabatt"
                                        value="{{ $coefficient->default_rabatt ?? 20 }}"
                                        inputmode="decimal" placeholder="0.00">
                                </td>
                            </tr>
                            <tr>
                                <td>Default Unsere Referenz</td>
                                <td>
                                    @if (\Illuminate\Support\Facades\Schema::hasColumn('coefficients', 'default_unsere_referenz'))
                                        <input type="text" class="form-control" name="default_unsere_referenz"
                                            value="{{ old('default_unsere_referenz', $coefficient->default_unsere_referenz ?? '') }}"
                                            placeholder="e.g. Blerant Kqiku — used if the offer has no &quot;Unser Zeichen&quot;">
                                        <small class="form-text text-muted">Used for &quot;Unsere Referenz&quot; in the external PDF header when the offer &quot;Unser Zeichen&quot; is empty. The same text is printed after the closing paragraph when set; otherwise the built-in default name from the database is used there.</small>
                                    @else
                                        <span class="text-muted">Run migrations to enable.</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>External PDF closing text</td>
                                <td>
                                    @if (\Illuminate\Support\Facades\Schema::hasColumn('coefficients', 'pdf_external_closing_text'))
                                        <textarea class="form-control" name="pdf_external_closing_text" rows="12"
                                            placeholder="Folgende Leistungen sind enthalten: … through Freundliche Grüsse">{{ old('pdf_external_closing_text', $coefficient->pdf_external_closing_text ?? '') }}</textarea>
                                        <small class="form-text text-muted">Everything before the signature name at the end of the external PDF (after totals). Line breaks are kept. Leave empty to use the built-in default text.</small>
                                    @else
                                        <span class="text-muted">Run migrations to enable.</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="edit-delete-btns text-right" colspan="2">
                                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-pencil"></i> Update</button>
                                </td>
                        </tr>
                        </form>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
