<p style="font-size:20px; font-weight:bold;">Update Material</p>
<form action="{{ route('material.update', $material->id) }}" method="post">
    @csrf
    @method('put')
    <div>
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="{{ $material->name }}">
    </div>
    <div>
        <label for="unit">Unit</label>
        <input type="radio" name="unit" value="cm" {{ $material->unit == 'cm' ? 'checked' : '' }}>CM
        <input type="radio" name="unit" value="m" {{ $material->unit == 'm' ? 'checked' : '' }}> M
    </div>
    <div>
        <label for="price_in">Price_In</label>
        <input type="text" name="price_in" id="price_in" value="{{ $material->price_in }}">
    </div>
    <div>
        <label for="price_out">Price_Out</label>
        <input type="text" name="price_out" id="price_out" value="{{ $material->price_out }}">
    </div>
    <div>
        <label for="z_schlosserei">Schlosserei</label>
        <input type="text" name="z_schlosserei" id="z_schlosserei" value="{{ $material->z_schlosserei }}">
    </div>
    <div>
        <label for="z_pe">Pe</label>
        <input type="text" name="z_pe" id="z_pe" value="{{ $material->z_pe }}">
    </div>
    <div>
        <label for="z_montage">Montage</label>
        <input type="text" name="z_montage" id="z_montage" value="{{ $material->z_montage }}">
    </div>
    <div>
        <label for="z_fermacell">Fermacell</label>
        <input type="text" name="z_fermacell" id="z_fermacell" value="{{ $material->z_fermacell }}">
    </div>
    <div>
        <label for="total">Total</label>
        <input type="text" name="total" id="total" value="{{ $material->total }}">
    </div>
    <button type="submit" class="btn mt-3 btn-primary">Update Post</button>
    <a href="{{ route('material.index') }}" class="btn btn-primary">Back</button>
</form>