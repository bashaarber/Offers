    <p style="font-size:20px; font-weight:bold;">Create New Material</p>
    <form action="{{ route('material.store') }}" method="POST">
        @csrf
        <div>
            <label for="name">Name</label>
            <input type="text" name="name" id="name" required>
        </div>
        <div>
            <label for="unit">Unit</label>
            <input type="radio" name="unit" value="cm">CM
            <input type="radio" name="unit" value="m">M
        </div>
        <div>
            <label for="price_in">Price_In</label>
            <input type="text" name="price_in" id="price_in" required>
        </div>
        <div>
            <label for="price_out">Price_Out</label>
            <input type="text" name="price_out" id="price_out" required>
        </div>
        <div>
            <label for="z_schlosserei">Schlosserei</label>
            <input type="text" name="z_schlosserei" id="z_schlosserei" required>
        </div>
        <div>
            <label for="z_pe">Pe</label>
            <input type="text" name="z_pe" id="z_pe" required>
        </div>
        <div>
            <label for="z_montage">Montage</label>
            <input type="text" name="z_montage" id="z_montage" required>
        </div>
        <div>
            <label for="z_fermacell">Fermacell</label>
            <input type="text" name="z_fermacell" id="z_fermacell" required>
        </div>
        <div>
            <label for="total">Total</label>
            <input type="text" name="total" id="total" required>
        </div>
        <button type="submit" class="btn btn-primary">Create Material</button>
        <a href="{{ route('material.index') }}" class="btn btn-primary">Back</button>
    </form>