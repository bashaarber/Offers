<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Offert - {{ $offert->id }}</title>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
            font-size: 15px;
        }

        .sub-table {
            width: 100%;
            border-collapse: collapse;
        }
    </style>
</head>

<body>
    <img src="{{ public_path('images/sanivor.jpg') }}" style="width: 350px; height: 70px"><br>
    <div style="float: left;">
        <br>
        <span style="font-size:18px"><strong>Sanivor AG</strong></span><br>
        <span>Buckstrasse 1</span><br>
        <span>8317 Tagelswangen</span><br>
        <span>Tel +41 (0)52 213 20 90</span><br>
        <a style="color:blue">info@sanivor.ch</a><br>
        <a style="color:blue">www.sanivor.ch</a>
    </div>
    <div style="float: right;"><br>
        <span><strong>Br&uuml;hwiler Sanit&auml;r &amp; Heizung AG </strong></span><br>
        <span>Nordstrasse 205</span><br>
        <span>8037 Z&uuml;rich</span>
    </div>
    <div style="clear: both;"></div>
    <hr>

    <div>
        <div style="float: left;">
            <p><strong>Angebot NR: </strong> {{ $offert->id }}</p>
            <p><strong>Datum: </strong> {{ \Carbon\Carbon::parse($offert->create_date)->format('d/m/Y') }}</p>
        </div>
        <div style="margin-left:40%">
            <p><strong>Objekt: {{ $offert->object }}</strong></p>
            <p><strong>{{ $offert->city }}</strong></p>
        </div>
        <div style="clear: both;"></div>
        <hr>
    </div>

    <div>
        <div style="float: left;">
            <p><strong>Ihr Auftrag: </strong> Email vom
                {{ $offert->finish_date ? \Carbon\Carbon::parse($offert->finish_date)->format('d/m/Y') : '' }}</p>
            <p><strong>Ihre Referenz: </strong> {{ $offert->client_sign }}</p>
            <p><strong>Unsere Referenz: </strong> {{ $offert->user_sign }}</p>
        </div>

        <div style="float: right;">
            <p><strong>Angebot G&uuml;ltigkeit: </strong> {{ $offert->validity }}</p>
            <p><strong>Zahlungskonditionen: </strong> {{ $offert->payment_conditions }}</p>
            <p><strong>Lieferung: </strong> {{ $offert->service }}</p>
        </div>
        <div style="clear: both;"></div>
        <hr>
    </div>
    <div>
        <div style="margin-left:65%">
            @php
                $totalBrutto = 0;
                $totalDiscount = 0;
                $totalNetto = 0;
                $totalElements = 0;
                $optionalPositions = 0;
            @endphp

            @foreach ($offert->positions as $position)
                @if (!$position->is_optional)
                    @php
                        $totalElements += $position->quantity;
                        $totalBrutto += $position->price_brutto;
                        $totalNetto += $position->price_discount;
                        $totalDiscount += max($position->price_brutto - $position->price_discount, 0);
                    @endphp
                @else
                    @php
                        $optionalPositions++;
                    @endphp
                @endif
            @endforeach

            <p style="margin-bottom:30px;"><strong>Total Elemente: Stk. {{ $totalElements }} </strong></p>
            <p>Total Brutto: CHF {{ number_format($totalBrutto, 2) }} </p>
            <p style="margin-bottom:30px">Rabbat: CHF -{{ number_format($totalDiscount, 2) }}</p>
            <p><strong>Total Netto: CHF {{ number_format($totalNetto, 2) }}</strong></p>
            @php
                $difference = $totalNetto;
                $discountedAmount = $difference * 0.081;
            @endphp

            <p>MwSt 8.1% : CHF {{ number_format($discountedAmount, 2) }}</p>
            <p><strong>Gesamt: CHF {{ number_format($difference + $discountedAmount, 2) }}</strong></p>
            @if ($optionalPositions > 0)
                <p style="font-size: 12px; margin-top: 8px;">
                    * {{ $optionalPositions }} optionale Position(en) sind im Gesamtpreis nicht enthalten.
                </p>
            @endif
        </div>
        <div style="clear: both;"></div>
    </div>
    <div>
        <hr>
        Folgende Leistungen sind enthalten:<br>
        - Transport<br>
        - Ausmass<br>
        - Holz (Sperrholz) 24mm: f&uuml;r Gleitstange, Glasstrennwand und Waschtisch
        <br>
        <p>F&uuml;r weitere Fragen stehen wir Ihnen gerne zur Verf&uuml;gung. Es w&uuml;rde uns freuen, diesen Auftrag f&uuml;r Sie
            ausf&uuml;hren zu
            d&uuml;rfen. Es gelten unsere Allgemeinen Gesch&auml;ftsbedingungen (AGB), die unter www.sanivor.ch zu finden sind.
        </p>
        <p>Freundliche Gr&uuml;sse</p>
        <p>{{ $offert->user_sign }}</p>
        <hr>
    </div>
    <div style="page-break-after: always"></div>
    @foreach ($offert->positions as $key => $position)
        <table style="border:0.25px solid black;">
            <thead style="border:0.25px solid black;">
                <tr>
                    <th>Pos {{ $position->position_number }}{{ $position->is_optional ? ' (Option)' : '' }}</th>
                    <th>{{ $position->description }}</th>
                    <th>Brutto</th>
                    <th>Rabatt</th>
                    <th>Netto</th>
                    <th>Stk.</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr style="text-align: center;font-weight:bold">
                    <td>
                        {{ $position->blocktype }}<br>
                        B:{{ $position->b }}
                        H:{{ $position->h }}
                        T:{{ $position->t }} (in cm)
                    </td>
                    <td></td>
                    <td>{{ number_format($position->price_brutto, 2) }}</td>
                    <td>{{ $position->discount }}%</td>
                    <td>{{ number_format($position->price_brutto * ((100 - $position->discount) / 100), 2) }}</td>
                    </td>
                    <td> {{ $position->quantity }} </td>
                    <td>{{ number_format($position->price_brutto * ((100 - $position->discount) / 100) * $position->quantity, 2) }}
                    </td>

                    </td>
                </tr>
            </tbody>
        </table>

        @php
            $groupedGroupElements = [];
        @endphp

        @foreach ($position->elements as $element)
            @foreach ($element->group_elements as $group_element)
                @foreach ($group_element->organigrams as $organigram)
                    @php
                        $groupedGroupElements[$organigram->name][$group_element->name][] = [
                            'quantity' => $element->pivot->quantity,
                            'element_name' => $element->name,
                        ];
                    @endphp
                @endforeach
            @endforeach
        @endforeach

        @php
            // Define the desired order: Rahme first, then Installationsmodule, then Verrohrung
            $desiredOrder = ['Rahme', 'Installationsmodule', 'Verrohrung'];
            $orderedGroupElements = [];
            foreach ($desiredOrder as $key_name) {
                if (isset($groupedGroupElements[$key_name])) {
                    $orderedGroupElements[$key_name] = $groupedGroupElements[$key_name];
                }
            }
            // Add any remaining groups not in the desired order
            foreach ($groupedGroupElements as $key_name => $value) {
                if (!isset($orderedGroupElements[$key_name])) {
                    $orderedGroupElements[$key_name] = $value;
                }
            }
        @endphp

        <table style="width:100%">
            @foreach ($orderedGroupElements as $organigramName => $groupedElements)
                <tr style="border:0.25px solid black;">
                    <td style="font-weight:bold;width:25%;vertical-align: top;padding: 5px;">Enthalten
                        {{ $organigramName }}:</td>
                    <td colspan="2">
                        @foreach ($groupedElements as $groupName => $groupElements)
                            <table class="sub-table">
                                <tr>
                                    <td
                                        style="font-weight:bold;width:25%;vertical-align: top;text-align:right;padding: 3px;">
                                        {{ $groupName }}</td>
                                    <td style="width:50%;padding: 3px;">
                                        @foreach ($groupElements as $groupElement)
                                            {{ $groupElement['quantity'] }} x {{ $groupElement['element_name'] }}<br>
                                        @endforeach
                                    </td>
                                </tr>
                            </table>
                        @endforeach
                    </td>
                </tr>
            @endforeach
            <tr style="border:0.25px solid black;">
                <td colspan="3" style="padding: 10px;">
                    @if ($position->description2)
                        {{ $position->description2 }}<br>
                        <hr>
                    @endif
                    Rahmenprofile, Metallteile und Befestigungen grundiert, Wand-Boden und Decke schallentkoppelt nach
                    SIA 181. (Fraunhofer Institut Stuttgart)<br>
                    MPA gepr&uuml;ft, Brandschutzpr&uuml;fung und El 120 MPA erf&uuml;llt (VKF) Nr. 22523
                </td>
            </tr>
        </table>
        @if ($key < count($offert->positions) - 1)
            <div style="page-break-after: always"></div>
        @endif
    @endforeach
</body>

</html>
