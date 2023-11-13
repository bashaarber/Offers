<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Material Piece</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    @include('layouts.sidebar')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="font-weight-bold">Create New Material Piece</h3>
                        <form action="{{ route('material_piece.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="text-center">Prise (CHF)</h5>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="price_in">In</label>
                                            <input type="text" class="form-control" name="price_in" id="price_in" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="price_out">Out</label>
                                            <input type="text" class="form-control" name="price_out" id="price_out" required>
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
                                            <input type="text" class="form-control" name="z_schlosserei" id="z_schlosserei" required>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="z_pe">Pe</label>
                                            <input type="text" class="form-control" name="z_pe" id="z_pe" required>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="z_montage">Montage</label>
                                            <input type="text" class="form-control" name="z_montage" id="z_montage" required>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="z_fermacell">Fermacell</label>
                                            <input type="text" class="form-control" name="z_fermacell" id="z_fermacell" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Create Material Piece</button>
                            <a href="{{ route('material_piece.index') }}" class="btn btn-secondary mt-3">Back</a>
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