<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Offert</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<style>
    #clientSearch {
        width: 100%;
        padding: 10px;
        font-size: 18px;
        border: 1px solid #ccc;
    }

    #searchResults {
        margin-left: -7.5%; 
        max-height: 200px;
        overflow-y: auto;
    }

    .list-group-item {
        cursor: pointer;
        border: none;
        background-color: #f9f9f9;
    }

    .list-group-item:hover {
        background-color: #e0e0e0;
    }
    h6{
        background-color: skyblue;
        padding: 10px;
    }
</style>

<body>
    @include('layouts.sidebar')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                            <h6>Projektinformationen</h6>

                        <form action="{{ route('offert.store') }}" method="POST">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="id">Offerte NR.</label>
                                    <input type="text" class="form-control" value="{{ $newOffertId }}" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="type">Offerte Typ</label>
                                    <select class="form-control" name="type" required>
                                        <option value="client">Client</option>
                                        <option value="company">Company</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="user_sign">Unser Zeichen</label>
                                    <input type="text" class="form-control" id="user_sign" name="user_sign" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="status">Status</label>
                                    <select class="form-control" name="status" required>
                                        <option value="new">Neu - In progress</option>
                                        <option value="finished">Finished</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="create_date">Angebot Datum</label>
                                    <input type="date" class="form-control" id="create_date" name="create_date"
                                        required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="validity">Angebot Gültigkeit</label>
                                    @foreach ($coefficients as $coefficient)
                                        <input type="text" class="form-control" id="validity" name="validity"
                                            value="{{ $coefficient->validity }}" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="client_sign">Ihr Zeichen</label>
                                    <input type="text" class="form-control" id="client_sign" name="client_sign"
                                        required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="finish_date">vom</label>
                                    <input type="date" class="form-control" id="finish_date" name="finish_date">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="object">Objekt</label>
                                    <input type="text" class="form-control" id="object" name="object" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="city">Ort</label>
                                    <input type="text" class="form-control" id="city" name="city" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="service">Lieferung</label>
                                    <input type="text" class="form-control" id="service" name="service"
                                        value="{{ $coefficient->service }}" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="payment_conditions">Zahlungskonditionen</label>
                                    <input type="text" class="form-control" id="payment_conditions"
                                        name="payment_conditions" value="{{ $coefficient->payment_conditions }}"
                                        required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="client_id">Kunde</label>
                                    <div class="autocomplete">
                                        <input type="text" id="clientSearch" placeholder="Search for a client" autocomplete="off"required>
                                        <input type="hidden" name="client_id" id="client_id">
                                    </div>
                                    <ul id="searchResults"></ul>
                                </div>
                            </div>
                            <h6>Koeffizienten für dieses Project</h6>
                            
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="difficulty">Schwierigkeits-Koeff.</label>
                                    <input type="text" class="form-control" id="difficulty" name="difficulty"
                                        value="{{ $coefficient->difficulty }}" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="material">Material-Koeff.</label>
                                    <input type="text" class="form-control" id="material" name="material"
                                        value="{{ $coefficient->material }}" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="labor_price">Stundenansatz</label>
                                    <input type="text" class="form-control" id="labor_price" name="labor_price"
                                        value="{{ $coefficient->labor_price }}" required>
                                </div>
                            </div>
                            @endforeach
                            <button type="submit" class="btn btn-primary mt-3">Create Offert</button>
                            <a href="{{ route('offert.index') }}" class="btn btn-secondary mt-3">Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>
        document.getElementById('clientSearch').addEventListener('input', function () {
            var searchValue = this.value.trim().toLowerCase();
            var searchResults = document.getElementById('searchResults');
            searchResults.innerHTML = '';
    
            if (searchValue.length === 0) {
                return; // No input, don't display any results
            }
    
            // Iterate through the clients and display matching results
            @foreach ($clients as $client)
                var clientName = "{{ $client->name }}".toLowerCase();
                if (clientName.includes(searchValue)) {
                    var listItem = document.createElement('a');
                    listItem.href = "javascript:void(0)";
                    listItem.className = "list-group-item list-group-item-action";
                    listItem.textContent = "{{ $client->name }}";
    
                    // Add data attribute to store client_id
                    listItem.setAttribute("data-client-id", "{{ $client->id }}");
    
                    // Add a click event listener to populate the input field and client_id
                    listItem.addEventListener('click', function () {
                        document.getElementById('clientSearch').value = "{{ $client->name }}";
                        // Set the client_id value
                        document.getElementById('client_id').value = this.getAttribute("data-client-id");
                        searchResults.innerHTML = ''; // Clear the results
                    });
    
                    searchResults.appendChild(listItem);
                }
            @endforeach
        });
    </script>
    
    

</body>

</html>
