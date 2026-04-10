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

        /* Space between Pos 1, Pos 2, … so blocks are not flush */
        .pdf-position-block {
            margin-bottom: 28px;
            padding-bottom: 12px;
        }

        .pdf-position-block:last-child {
            margin-bottom: 0;
        }

        .position-head-row th {
            padding: 8px 6px;
            line-height: 1.25;
        }

        .position-head-row th.pos-col {
            text-align: left !important;
        }

        .position-value-row td {
            padding: 8px 6px;
        }

        .text-left {
            text-align: left !important;
        }

        /* Quantity in first cell so wrapped text + (opt*) align under the name, not under "1 x" */
        .pdf-qty-name {
            width: 100%;
            border-collapse: collapse;
            margin: 0 0 3px 0;
        }

        .pdf-qty-name td:first-child {
            vertical-align: top;
            white-space: nowrap;
            width: 1%;
            padding: 0 6px 0 0;
        }

        .pdf-qty-name td:last-child {
            vertical-align: top;
            padding: 0;
        }
    </style>
</head>

<body>
    @php
        $logoPath = public_path('images/sanivor.jpg');
        $logoSrc = null;

        $coefficientRow = \App\Models\Coefficient::query()->first();

        $defaultSignature = 'Arber Basha';
        if (\Illuminate\Support\Facades\Schema::hasColumn('coefficients', 'default_signature') && $coefficientRow) {
            $defaultSignature = $coefficientRow->default_signature ?? 'Arber Basha';
        }

        $defaultUnsereReferenz = '';
        if (\Illuminate\Support\Facades\Schema::hasColumn('coefficients', 'default_unsere_referenz') && $coefficientRow) {
            $defaultUnsereReferenz = (string) ($coefficientRow->default_unsere_referenz ?? '');
        }

        $unsereReferenzLine = trim((string) ($offert->user_sign ?? '')) !== ''
            ? trim((string) $offert->user_sign)
            : $defaultUnsereReferenz;

        $pdfExternalClosingFallback = "Folgende Leistungen sind enthalten:\n- Transport\n- Ausmass\n- Holz (Sperrholz) 24mm: für Gleitstange, Glasstrennwand und Waschtisch\n\nFür weitere Fragen stehen wir Ihnen gerne zur Verfügung. Es würde uns freuen, diesen Auftrag für Sie ausführen zu dürfen. Es gelten unsere Allgemeinen Geschäftsbedingungen (AGB), die unter www.sanivor.ch zu finden sind.\n\nFreundliche Grüsse";

        $pdfClosingText = $pdfExternalClosingFallback;
        if (\Illuminate\Support\Facades\Schema::hasColumn('coefficients', 'pdf_external_closing_text') && $coefficientRow) {
            $customClosing = $coefficientRow->pdf_external_closing_text;
            if ($customClosing !== null && trim((string) $customClosing) !== '') {
                $pdfClosingText = (string) $customClosing;
            }
        }

        $pdfFooterName = trim((string) $unsereReferenzLine) !== '' ? $unsereReferenzLine : $defaultSignature;

        $chf = function ($value, int $decimals = 2) {
            return number_format((float) $value, $decimals, '.', "'");
        };
        $chInt = function ($value) {
            return number_format((float) $value, 0, '.', "'");
        };
        if (file_exists($logoPath)) {
            $logoSrc = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoPath));
        }
    @endphp
    @if ($logoSrc)
        <img src="{{ $logoSrc }}" style="width: 350px; height: 70px"><br>
    @endif
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
        <span><strong>{{ $offert->client->name ?? '' }}</strong></span><br>
        @php
            $clientAddressLines = preg_split('/\r\n|\r|\n/', (string) ($offert->client->address ?? ''));
        @endphp
        @foreach ($clientAddressLines as $line)
            @if (trim($line) !== '')
                <span>{{ $line }}</span><br>
            @endif
        @endforeach
    </div>
    <div style="clear: both;"></div>
    <hr>

    <div>
        <div style="float: left;">
            <p><strong>Angebot Nr.: </strong> {{ $offert->id }}</p>
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
            @php
                $vomDate = $offert->finish_date ?? $offert->create_date;
            @endphp
            <p><strong>Ihr Auftrag: </strong> Email vom
                {{ $vomDate ? \Carbon\Carbon::parse($vomDate)->format('d/m/Y') : '' }}</p>
            <p><strong>Ihre Referenz: </strong> {{ $offert->client_sign }}</p>
            <p><strong>Unsere Referenz: </strong> {{ $unsereReferenzLine }}</p>
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
                $optionalPositions = 0;
                // Sum of Menge (position quantity) across all positions, e.g. Pos 1 + Pos 2 + …
                $totalMengeStk = (int) round(
                    $offert->positions->sum(function ($position) {
                        return (float) ($position->quantity ?? 0);
                    })
                );
            @endphp

            @foreach ($offert->positions as $position)
                @if (!$position->is_optional)
                    @php
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

            <p style="margin-bottom:30px;"><strong>Total Elemente: Stk. {{ $chInt($totalMengeStk) }} </strong></p>
            <p>Total Brutto: CHF {{ $chf($totalBrutto) }} </p>
            <p style="margin-bottom:30px">Rabatt: CHF -{{ $chf($totalDiscount) }}</p>
            <p><strong>Total Netto: CHF {{ $chf($totalNetto) }}</strong></p>
            @php
                $difference = $totalNetto;
                $discountedAmount = $difference * 0.081;
            @endphp

            <p>MwSt 8.1% : CHF {{ $chf($discountedAmount) }}</p>
            <p><strong>Gesamt: CHF {{ $chf($difference + $discountedAmount) }}</strong></p>
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
        {!! nl2br(e($pdfClosingText)) !!}
        <p style="margin-top: 10px;">{{ $pdfFooterName }}</p>
        <hr>
    </div>
    <div style="page-break-after: always"></div>
    @foreach ($offert->positions as $key => $position)
        <div class="pdf-position-block">
        <table style="border:0.25px solid black;">
            <thead style="border:0.25px solid black;">
                <tr class="position-head-row">
                    <th class="pos-col text-left">Pos {{ $position->position_number }}{{ $position->is_optional ? ' (Option)' : '' }}</th>
                    <th>{{ $position->description }}</th>
                    <th>Brutto</th>
                    <th>Rabatt</th>
                    <th>Netto</th>
                    <th>Stk.</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr class="position-value-row" style="text-align: center;font-weight:bold">
                    @php
                        $quantity = max((float) ($position->quantity ?? 1), 1);
                        $unitBrutto = (float) ($position->price_brutto ?? 0) / $quantity;
                        $unitNetto = $unitBrutto * ((100 - (float) ($position->discount ?? 0)) / 100);
                    @endphp
                    <td class="text-left">
                        {{ $position->blocktype }}<br>
                        B:{{ $position->b }}
                        H:{{ $position->h }}
                        T:{{ $position->t }} (in cm)
                    </td>
                    <td class="text-left"></td>
                    <td>{{ $chf($unitBrutto) }}</td>
                    <td>{{ $position->discount }}%</td>
                    <td>{{ $chf($unitNetto) }}</td>
                    <td> {{ $chInt($position->quantity) }} </td>
                    <td>{{ $chf((float) ($position->price_discount ?? 0)) }}
                    </td>
                </tr>
            </tbody>
        </table>

        @php
            $groupedGroupElements = [];
        @endphp

        @foreach ($position->elementsForPdf as $element)
            @foreach ($element->group_elements as $group_element)
                @foreach ($group_element->organigrams as $organigram)
                    @php
                        $groupedGroupElements[$organigram->name][$group_element->name][] = [
                            'quantity'    => $element->pivot->quantity,
                            'element_name' => $element->name,
                            'is_optional' => \App\Models\Position::truthyElementOptionalPivot($element->pivot->is_optional ?? null),
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
                                            <table class="pdf-qty-name">
                                                <tr>
                                                    <td>{{ $groupElement['quantity'] }} x</td>
                                                    <td>{{ $groupElement['element_name'] }}{{ $groupElement['is_optional'] ? ' (opt*)' : '' }}</td>
                                                </tr>
                                            </table>
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
        </div>
    @endforeach
</body>

</html>
