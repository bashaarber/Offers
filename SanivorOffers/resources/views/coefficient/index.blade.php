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
                                        value="{{ $coefficient->default_rabatt ?? 0 }}"
                                        inputmode="decimal" placeholder="0.00">
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
