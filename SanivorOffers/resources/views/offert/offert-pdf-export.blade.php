<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Offert - {{ $offert->id }}</title>
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> --}}
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
            font-size: 14px;
        }

        td {
            text-align: center;
            padding: 8px;
        }

        th {
            text-align: center;
            padding: 8px;
            background-color: rgb(201, 214, 221);
        }

        tr:nth-child(even) {
            background-color: white;
        }
    </style>
</head>

<body>
    <img src="{{ public_path('images/sanivor.jpg') }}" style="width: 350px; height: 70px">
    <div class="company-info">
        <br>
        <span style="font-size:18px"><strong>Sanivor AG</strong></span><br>
        <span>Buckstrasse 1</span><br>
        <span>8317 Tagelswangen</span><br>
        <span>Tel +41 (0)52 213 20 90</span><br>
        <a style="color:blue">info@sanivor.ch</a><br>
        <a style="color:blue">www.sanivor.ch</a>
    </div>
    <hr>

    <div>
        <div style="float: left;">
            <p><strong>Angebot NR: </strong> {{ $offert->id }}</p>
            <p><strong>Datum: </strong> {{ \Carbon\Carbon::parse($offert->create_date)->format('d/m/Y') }}</p>
        </div>
        <div style="margin-left:40%">
            <p><strong>Object: </strong> {{ $offert->object }}</p>
            <p><strong>City: </strong>{{ $offert->city }}</p>
        </div>
        <div style="clear: both;"></div>
        <hr>
    </div>

    <div style="background-color:rgb(229, 236, 238);">
        <div style="float: left;">
            <p><strong>Ihr Auftrag: </strong> vom</p>
            <p><strong>Ihre Referenz: </strong></p>
            <p><strong>Unsere Referenz: </strong> {{ $offert->user_sign }}</p>
        </div>

        <div style="float: right;">
            <p><strong>Angebot Gültigkeit: </strong> {{ $offert->validity }}</p>
            <p><strong>Zahlungskonditionen: </strong> {{ $offert->payment_conditions }}</p>
            <p><strong>Lieferung: </strong> {{ $offert->service }}</p>
        </div>
        <div style="clear: both;"></div>
        <hr>
    </div>
    <div style="background-color:rgb(229, 236, 238)">
        <div style="margin-left:65%">
            <p><strong>Total Elemente: Stk. 1 </strong></p>
            @php
                $totalBrutto = 0;
                $totalDiscount = 0;
            @endphp

            @foreach ($offert->positions as $position)
                @php
                    $totalBrutto += $position->price_brutto;

                    if ($position->price_discount !== $position->price_brutto) {
                        $totalDiscount += $position->price_discount;
                    }
                @endphp
            @endforeach

            <p><strong>Total Brutto: </strong>{{ $totalBrutto }} </p>
            <p><strong>Rabbat: </strong> {{ $totalDiscount, 2 }}</p>
            <p><strong>Total Netto: CHF {{ $totalBrutto - $totalDiscount }}</p></strong>
            @php
                $difference = $totalBrutto - $totalDiscount;
                // Apply a 7.7% discount
                $discountedAmount = $difference * 0.077;
            @endphp

            <p><strong>MwSt 7.7% : </strong> {{ number_format($discountedAmount, 2) }}</p>
            <p><strong>Gesamt: {{ number_format($difference + $discountedAmount, 2) }}</p></strong>
        </div>
        <div style="clear: both;"></div>
    </div>
    <div style="background-color:rgb(229, 236, 238);font-size:14px;">
        <hr>
        Folgende Leistungen sind enthalten:<br>
        - Transport<br>
        - Ausmass<br>
        - Holz (Sperrholz) 24mm: fur Gleitstange, Glasstrennwand und Waschtisch
        <br>
        <p>Fur weitere Fragen stehen wir Ihnen gerne zur Vergungung. Es wurde uns freuen, diesen Aufstrag fur
            Sie
            ausfuhren zu durfen. Es gelten unsere Allgemeinen Geschaftsbedingungen (AGB), die unter
            www.sanivor.ch
            zu finden sind.</p>
        <p>Freundliche Gruse</p>
        <p>Kqiku Nysret</p>
        <hr>
    </div>
    <div style="page-break-after: always"></div>
    @foreach ($offert->positions as $position)
        <table>
            <thead>
                <tr>
                    <th colspan="4">Pos. {{ $position->position_number }}</th>
                    <th>Brutto</th>
                    <th>Rabatt</th>
                    <th>Netto</th>
                    <th>Stk.</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="font-weight:bold" colspan="4">
                        @if ($position->blocktype && $position->b && $position->h && $position->t)
                            {{ $position->blocktype }}<br>
                            B:{{ $position->b }}
                            H:{{ $position->h }}
                            T:{{ $position->t }} (in cm)
                        @else
                            0 <br>
                            B: x H: x T: (in cm)
                        @endif
                    </td>
                    <td>{{ $position->price_brutto }}</td>
                    <td>{{ $position->discount }}</td>
                    <td>{{ $position->price_discount }}</td>
                    <td> {{ $position->quantity }} </td>
                    <td>{{ $position->quantity * $position->price_discount }}</td>
                </tr>
            </tbody>
        </table>
        <hr>

        @foreach ($position->elements as $element)
            <div>
                <p style="font-size:14px;"><strong>Enthalten: </strong> {{ $element->pivot->quantity }} x {{ $element->name }}</p>
                {{-- @foreach ($element->materials as $material)
                <p>Installationsmodule: {{ $material->name }}</p>
            @endforeach --}}
            </div>
        @endforeach
        <hr style="margin-top: 15px">
        <div style="font-size:12px; margin-top: 10px;">
            Rahmenprofile, Metallteile und Befestigungen grundiert, Wand-Boden und Decke
            schallentkoppelt nach SIA 181 (Fraunhofer Institut Stuttgart). MPA geprüft, Brandschutzprüfung und El 120
            MPA
            erfüllt (VKF) Nr. 22523
        </div>
        <hr>
    @endforeach
</body>

</html>
