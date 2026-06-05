<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Material</title>
    <style>
        .mp-modal-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,.45);
            display: none; align-items: center; justify-content: center; z-index: 1050;
        }
        .mp-modal {
            background: #fff; border-radius: 8px; width: 420px; max-width: 92vw;
            padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,.25);
        }
        .mp-modal h5 { margin: 0 0 16px; font-weight: 700; }
        .mp-modal label { display: block; margin: 10px 0 4px; font-size: 14px; }
        .mp-modal .mp-actions { margin-top: 18px; text-align: right; }
        .mp-error { color: #dc3545; font-size: 13px; margin-top: 8px; display: none; }
    </style>
</head>

<body>
    @include('layouts.sidebar')
    <div class="content">
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
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ $material->name }}" required>
                            </div>

                            <div class="form-row">
                                    <label for="materials">Material Pieces:</label>
                                    <select class="select-material-pieces form-control"
                                        name="materials[]" multiple required>
                                        @foreach ($material_pieces as $material_piece)
                                            <option value="{{ $material_piece->id }}"
                                                {{ in_array($material_piece->id, $selectedMaterials) ? 'selected' : '' }}>
                                                {{ $material_piece->name }}
                                            </option>
                                        @endforeach
                                    </select>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6 mt-3">
                                    <label for="unit">Unit</label>
                                    <select class="form-control" name="unit" aria-label="Default select example"
                                        required>
                                        <option value="St." @if ($material->unit == 'St.') selected @endif>St.
                                        </option>
                                        <option value="m" @if ($material->unit == 'm') selected @endif>m
                                        </option>
                                        <option value="m²" @if ($material->unit == 'm²') selected @endif>m²
                                        </option>
                                        <option value="Std" @if ($material->unit == 'Std') selected @endif>Std
                                        </option>
                                    </select>
                                    </select>
                                </div>
                            </div>
                            <div class="card mt-3">
                                <div class="card-body">
                                    <h5 class="text-center">Zeit (uhr)</h5>
                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label for="z_schlosserei">Schlosserei</label>
                                            <input type="text" class="form-control" name="z_schlosserei"
                                                id="z_schlosserei" value="{{ $material->z_schlosserei }}" required>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="z_pe">Pe</label>
                                            <input type="text" class="form-control" name="z_pe" id="z_pe"
                                                value="{{ $material->z_pe }}" required>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="z_montage">Montage</label>
                                            <input type="text" class="form-control" name="z_montage" id="z_montage"
                                                value="{{ $material->z_montage }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">@lang('public.save')</button>
                            <a href="{{ route('material.index') }}" class="btn btn-secondary mt-3">@lang('public.back')</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <div id="mpModal" class="mp-modal-overlay">
        <div class="mp-modal">
            <h5>Update Material Piece</h5>
            <form id="mpForm">
                <label for="mp_name">Name</label>
                <input type="text" class="form-control" id="mp_name" required>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="mp_price_in">@lang('public.price_in') (In)</label>
                        <input type="text" class="form-control" id="mp_price_in" inputmode="decimal" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="mp_price_out">@lang('public.price_out') (Out)</label>
                        <input type="text" class="form-control" id="mp_price_out" inputmode="decimal" required>
                    </div>
                </div>

                <div class="mp-error" id="mpError"></div>

                <div class="mp-actions">
                    <button type="button" class="btn btn-secondary" id="mpCancel">@lang('public.back')</button>
                    <button type="submit" class="btn btn-primary" id="mpSave">@lang('public.save')</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        var mpData = {!! json_encode($material_pieces->mapWithKeys(function ($p) {
            return [$p->id => ['name' => $p->name, 'price_in' => $p->price_in, 'price_out' => $p->price_out]];
        })) !!};

        $(document).ready(function() {
            $('.select-material-pieces').select2({
                multiple: true
            });

            var csrf = document.querySelector('meta[name="csrf-token"]').content;
            var overlay = document.getElementById('mpModal');
            var errorBox = document.getElementById('mpError');
            var currentId = null;

            // Double-click a selected tag to edit that material piece.
            $(document).on('dblclick', '.select2-selection__choice', function () {
                var $choice = $(this);
                var idx = $choice.parent().children('.select2-selection__choice').index($choice);
                var $opts = $('.select-material-pieces option:selected');
                var id = $opts.eq(idx).val();
                if (!id || !mpData[id]) return;

                currentId = id;
                errorBox.style.display = 'none';
                document.getElementById('mp_name').value = mpData[id].name;
                document.getElementById('mp_price_in').value = mpData[id].price_in;
                document.getElementById('mp_price_out').value = mpData[id].price_out;
                overlay.style.display = 'flex';
            });

            function closeModal() {
                overlay.style.display = 'none';
                currentId = null;
            }

            document.getElementById('mpCancel').addEventListener('click', closeModal);
            overlay.addEventListener('click', function (e) {
                if (e.target === overlay) closeModal();
            });

            document.getElementById('mpForm').addEventListener('submit', function (e) {
                e.preventDefault();
                if (!currentId) return;

                var saveBtn = document.getElementById('mpSave');
                saveBtn.disabled = true;
                errorBox.style.display = 'none';

                var body = new FormData();
                body.append('_method', 'PUT');
                body.append('name', document.getElementById('mp_name').value);
                body.append('price_in', document.getElementById('mp_price_in').value);
                body.append('price_out', document.getElementById('mp_price_out').value);

                fetch('/material_piece/' + currentId, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: body
                }).then(function (res) {
                    if (res.ok) return res.json();
                    return res.json().then(function (data) { throw data; });
                }).then(function (data) {
                    var m = data.material;
                    mpData[currentId] = { name: m.name, price_in: m.price_in, price_out: m.price_out };
                    // Update the tag label if the name changed.
                    var $opt = $('.select-material-pieces option[value="' + currentId + '"]');
                    $opt.text(m.name);
                    $('.select-material-pieces').trigger('change');
                    closeModal();
                }).catch(function (err) {
                    var msg = 'Speichern fehlgeschlagen.';
                    if (err && err.errors) {
                        msg = Object.keys(err.errors).map(function (k) { return err.errors[k][0]; }).join(' ');
                    } else if (err && err.message) {
                        msg = err.message;
                    }
                    errorBox.textContent = msg;
                    errorBox.style.display = 'block';
                }).finally(function () {
                    saveBtn.disabled = false;
                });
            });
        });
    </script>
</body>

</html>
