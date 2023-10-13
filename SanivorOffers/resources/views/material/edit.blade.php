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
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="font-weight-bold">Update Material</h3>
                        <form action="{{ route('material.update', $material->id) }}" method="post">
                            @csrf
                            @method('put')
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $material->name }}" required>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="unit">Unit</label>
                                    <select class="form-control" name="unit" aria-label="Default select example" required>
                                        <option value="st" @if($material->unit == 'st') selected @endif>St</option>
                                        <option value="m" @if($material->unit == 'm') selected @endif>m</option>
                                        <option value="m2" @if($material->unit == 'm2') selected @endif>m2</option>
                                        <option value="std" @if($material->unit == 'std') selected @endif>Std</option>
                                    </select>
                                    </select>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="text-center">Prise (CHF)</h5>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="price_in">In</label>
                                            <input type="text" class="form-control" name="price_in" id="price_in" value="{{ $material->price_in }}" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="price_out">Out</label>
                                            <input type="text" class="form-control" name="price_out" id="price_out" value="{{ $material->price_out }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mt-3">
                                <div class="card-body">
                                    <h5 class="text-center">Zeit (uhr)</h5>
                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label for="z_schlosserei">Schlosserei</label>
                                            <input type="text" class="form-control" name="z_schlosserei" id="z_schlosserei" value="{{ $material->z_schlosserei }}" required>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="z_pe">Pe</label>
                                            <input type="text" class="form-control" name="z_pe" id="z_pe" value="{{ $material->z_pe }}" required>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="z_montage">Montage</label>
                                            <input type="text" class="form-control" name="z_montage" id="z_montage" value="{{ $material->z_montage }}" required>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="z_fermacell">Fermacell</label>
                                            <input type="text" class="form-control" name="z_fermacell" id="z_fermacell" value="{{ $material->z_fermacell }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="total">Total</label>
                                        <input type="text" class="form-control" name="total" id="total" value="{{ $material->total }}" required>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Update Material</button>
                            <a href="{{ route('material.index') }}" class="btn btn-secondary mt-3">Back</a>
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