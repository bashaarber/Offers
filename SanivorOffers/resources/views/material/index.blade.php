<div class="panel panel-default">
    <div class="panel-body">
        <strong>Material List</strong>
        <a href="{{ route('material.create') }}" class="btn btn-primary btn-xs pull-right py-0">Create Material</a>
        <table class="table table-responsive table-bordered table-stripped" style="margin-top:10px;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Unit</th>
                    <th>Price_In</th>
                    <th>Price_Out</th>
                    <th>z_schlosserei</th>
                    <th>z_pe</th>
                    <th>z_montage</th>
                    <th>z_fermacell</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($materials as $material)
                <tr>
                    <td>{{ $material->id }}</td>
                    <td>{{ $material->name }}</td>
                    <td>{{ $material->unit }}</td>
                    <td>{{ $material->price_in }}</td>
                    <td>{{ $material->price_out }}</td>
                    <td>{{ $material->z_schlosserei }}</td>
                    <td>{{ $material->z_pe }}</td>
                    <td>{{ $material->z_montage }}</td>
                    <td>{{ $material->z_fermacell }}</td>
                    <td>{{ $material->total }}</td>
                    <td>
                        <a href="{{ route('material.show',$material->id) }}" class="btn btn-primary btn-xs py-0">Show</a>
                        <a href="{{ route('material.edit',$material->id) }}" class="btn btn-warning btn-xs py-0">Edit</a>
                        <form action="{{ route('material.destroy', $material->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>