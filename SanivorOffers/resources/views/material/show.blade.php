<p style="font-size:20px; font-weight:bold;">Material Details</p>
        <div>
            <label for="name">Name</label>
            <input type="text" name="name" id="name" value="{{ $material->name }}" disabled>
        </div>
        <div>
        <label for="unit">Unit</label>
        <input type="radio" name="unit" value="cm" {{ $material->unit == 'cm' ? 'checked' : '' }}>CM
        <input type="radio" name="unit" value="m" {{ $material->unit == 'm' ? 'checked' : '' }}> M
    </div>
        <div>
            <label for="price_in">Price_In</label>
            <input type="text" name="price_in" id="price_in" value="{{ $material->price_in }}"disabled>
        </div>
        <div>
            <label for="price_out">Price_Out</label>
            <input type="text" name="price_out" id="price_out" value="{{ $material->price_out }}" disabled>
        </div>
        <div>
            <label for="z_schlosserei">Schlosserei</label>
            <input type="text" name="z_schlosserei" id="z_schlosserei" value="{{ $material->z_schlosserei }}" disabled>
        </div>
        <div>
            <label for="z_pe">Pe</label>
            <input type="text" name="z_pe" id="z_pe" value="{{ $material->z_pe }}" disabled>
        </div>
        <div>
            <label for="z_montage">Montage</label>
            <input type="text" name="z_montage" id="z_montage" value="{{ $material->z_montage }}" disabled>
        </div>
        <div>
            <label for="z_fermacell">Fermacell</label>
            <input type="text" name="z_fermacell" id="z_fermacell" value="{{ $material->z_fermacell }}" disabled>
        </div>
        <div>
            <label for="total">Total</label>
            <input type="text" name="total" id="total" value="{{ $material->total }}" disabled>
        </div>
        <a href="{{ route('material.index') }}" class="btn btn-primary">Back</button>