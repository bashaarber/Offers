<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Offert - {{ $offert->id }}</title>
    <style>
        @page { size: A4; margin: 10mm 13mm; }
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 9pt;
            color: #000;
            margin: 0;
            padding: 0;
        }
        table { border-collapse: collapse; }
        .w100 { width: 100%; }

        /* ── Separator lines ── */
        .sep-black { width:100%; border-top:0.75pt solid #000000; margin:6pt 0; }
        .sep-gray  { width:100%; border-top:0.75pt solid #808080; margin:0; }

        /* ── Blue-gray band (top of each section) ── */
        .band-top  { border-top:1pt solid #CFD8DC; }

        /* ── Totals box ── */
        .totals-label { width:55%; text-align:left; padding:2.5pt 4pt; }
        .totals-unit  { width:12%; text-align:left; padding:2.5pt 2pt; }
        .totals-value { width:33%; text-align:right; padding:2.5pt 4pt; }

        /* ── Position block ── */
        .pos-header-row td, .pos-header-row th {
            background-color: #CFD8DC;
            font-weight: bold;
            font-size: 9pt;
            padding: 3pt 5pt;
            vertical-align: middle;
        }
        .pos-value-row td {
            background-color: #fff;
            font-weight: bold;
            font-size: 9pt;
            padding: 3pt 5pt;
            vertical-align: top;
        }
        .pos-right { text-align: right; }

        /* ── Detail rows ── */
        .detail-label { font-weight:bold; vertical-align:top; padding:2pt 4pt; width:20%; }
        .detail-group { font-weight:bold; vertical-align:top; padding:2pt 4pt; width:22%; text-align:right; }
        .detail-items { vertical-align:top; padding:2pt 4pt; }
        .qty-table { width:100%; border-collapse:collapse; margin-bottom:1pt; }
        .qty-table td { padding:0; vertical-align:top; }
        .qty-table td:first-child { white-space:nowrap; padding-right:4pt; width:1%; }

        /* ── Footnote / closing ── */
        .footnote { font-size:9pt; padding:5pt 4pt; }

        /* ── Teal accent row ── */
        .teal-row td {
            height:13pt;
            border-bottom:0.75pt solid #63C2DE;
            padding:0;
            font-size:0.1pt;
        }
    </style>
</head>
<body>
@php
    $logoPath = public_path('images/sanivor.jpg');
    $logoSrc  = null;
    if (file_exists($logoPath)) {
        $logoSrc = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoPath));
    }

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

    $clientAddressLines = preg_split('/\r\n|\r|\n/', (string) ($offert->client->address ?? ''));
    $vomDate = $offert->finish_date ?? $offert->create_date;
@endphp

{{-- ═══════════════════════════════════════════════════════ --}}
{{-- PAGE 1 — COVER                                         --}}
{{-- ═══════════════════════════════════════════════════════ --}}

{{-- HEADER: Logo + Company (left) | Client (right) --}}
<table class="w100" style="margin-bottom:6pt;">
    <tr>
        <td style="width:58%; vertical-align:top; padding-right:10pt;">
            @if($logoSrc)
                <img src="{{ $logoSrc }}" style="width:210pt; height:42pt; margin-bottom:8pt;"><br>
            @endif
            <strong style="font-size:10pt;">Sanivor AG</strong><br>
            <span>Buckstrasse 1</span><br>
            <span>8317 Tagelswangen</span><br>
            <span>Tel +41 (0)52 213 20 90</span><br>
            <span>info@sanivor.ch</span><br>
            <span>www.sanivor.ch</span>
        </td>
        <td style="width:42%; vertical-align:top; padding-top:38pt;">
            <strong>{{ $offert->client->name ?? '' }}</strong><br>
            @foreach($clientAddressLines as $line)
                @if(trim($line) !== '')
                    {{ $line }}<br>
                @endif
            @endforeach
        </td>
    </tr>
</table>

{{-- Black separator --}}
<div class="sep-black"></div>

{{-- ANGEBOT INFO BLOCK --}}
<table class="w100" style="background-color:#CFD8DC; margin-bottom:0;">
    <tr>
        <td style="width:20%; font-weight:bold; font-size:12pt; padding:4pt 5pt;"><strong>Angebot Nr.</strong></td>
        <td style="width:30%; font-weight:bold; font-size:12pt; padding:4pt 5pt;"><strong>{{ $offert->id }}</strong></td>
        <td style="width:18%; font-weight:bold; font-size:12pt; padding:4pt 5pt;"><strong>Objekt:</strong></td>
        <td style="width:32%; font-weight:bold; font-size:12pt; padding:4pt 5pt;"><strong>{{ $offert->object }}</strong></td>
    </tr>
    <tr>
        <td style="font-weight:bold; font-size:12pt; padding:2pt 5pt 5pt;"><strong>Datum</strong></td>
        <td style="font-weight:bold; font-size:12pt; padding:2pt 5pt 5pt;"><strong>{{ \Carbon\Carbon::parse($offert->create_date)->format('d/m/Y') }}</strong></td>
        <td style="padding:2pt 5pt 5pt;"></td>
        <td style="font-weight:bold; font-size:12pt; padding:2pt 5pt 5pt;"><strong>{{ $offert->city }}</strong></td>
    </tr>
</table>

{{-- Gray separator --}}
<div class="sep-gray"></div>

{{-- IHR AUFTRAG INFO BLOCK --}}
<div class="band-top" style="padding-top:4pt; margin-bottom:0;">
    <table class="w100">
        <tr>
            <td style="width:20%; padding:2.5pt 5pt;"><strong>Ihr Auftrag</strong></td>
            <td style="width:30%; padding:2.5pt 5pt;">Email vom {{ $vomDate ? \Carbon\Carbon::parse($vomDate)->format('d/m/Y') : '' }}</td>
            <td style="width:23%; padding:2.5pt 5pt;"><strong>Angebot Gültigkeit</strong></td>
            <td style="width:27%; padding:2.5pt 5pt;">{{ $offert->validity }}</td>
        </tr>
        <tr>
            <td style="padding:2.5pt 5pt;"><strong>Ihre Referenz</strong></td>
            <td style="padding:2.5pt 5pt;">{{ $offert->client_sign }}</td>
            <td style="padding:2.5pt 5pt;"><strong>Zahlungskonditionen</strong></td>
            <td style="padding:2.5pt 5pt;">{{ $offert->payment_conditions }}</td>
        </tr>
        <tr>
            <td style="padding:2.5pt 5pt 5pt;"><strong>Unsere Referenz</strong></td>
            <td style="padding:2.5pt 5pt 5pt;">{{ $unsereReferenzLine }}</td>
            <td style="padding:2.5pt 5pt 5pt;"><strong>Lieferung</strong></td>
            <td style="padding:2.5pt 5pt 5pt;">{{ $offert->service }}</td>
        </tr>
    </table>
</div>

{{-- Gray separator --}}
<div class="sep-gray" style="margin-top:4pt;"></div>

{{-- TOTALS BOX (right half) --}}
@php
    $totalBrutto    = 0;
    $totalDiscount  = 0;
    $totalNetto     = 0;
    $optionalPositions = 0;
    $totalMengeStk  = (int) round(
        $offert->positions->sum(function ($p) { return (float) ($p->quantity ?? 0); })
    );
    foreach ($offert->positions as $position) {
        if (!$position->is_optional) {
            $posBrutto = isset($customPositionPrices[$position->id])
                ? $customPositionPrices[$position->id]['brutto']
                : $position->price_brutto;
            $posNetto  = isset($customPositionPrices[$position->id])
                ? $customPositionPrices[$position->id]['netto']
                : $position->price_discount;
            $totalBrutto   += $posBrutto;
            $totalNetto    += $posNetto;
            $totalDiscount += max($posBrutto - $posNetto, 0);
        } else {
            $optionalPositions++;
        }
    }
    $mwst   = $totalNetto * 0.081;
    $gesamt = $totalNetto + $mwst;
@endphp

<div class="band-top" style="padding-top:6pt;">
    <table style="width:55%; margin-left:45%;">
        <tr>
            <td class="totals-label"><strong>Total Elemente:</strong></td>
            <td class="totals-unit"><strong>Stk.</strong></td>
            <td class="totals-value"><strong>{{ $chInt($totalMengeStk) }}</strong></td>
        </tr>
        <tr>
            <td class="totals-label">Total Brutto</td>
            <td class="totals-unit">CHF</td>
            <td class="totals-value">{{ $chf($totalBrutto) }}</td>
        </tr>
        <tr>
            <td class="totals-label" style="padding-bottom:8pt;">Rabatt</td>
            <td class="totals-unit" style="padding-bottom:8pt;">CHF</td>
            <td class="totals-value" style="padding-bottom:8pt;">-{{ $chf($totalDiscount) }}</td>
        </tr>
        <tr>
            <td class="totals-label"><strong>Total Netto</strong></td>
            <td class="totals-unit"><strong>CHF</strong></td>
            <td class="totals-value"><strong>{{ $chf($totalNetto) }}</strong></td>
        </tr>
        <tr>
            <td class="totals-label">MwSt. 8.1%</td>
            <td class="totals-unit">CHF</td>
            <td class="totals-value">{{ $chf($mwst) }}</td>
        </tr>
        <tr>
            <td class="totals-label"><strong>Gesamt</strong></td>
            <td class="totals-unit"><strong>CHF</strong></td>
            <td class="totals-value"><strong>{{ $chf($gesamt) }}</strong></td>
        </tr>
        @if($optionalPositions > 0)
        <tr>
            <td colspan="3" style="font-size:8pt; padding:4pt 4pt 0;">
                * {{ $optionalPositions }} optionale Position(en) sind im Gesamtpreis nicht enthalten.
            </td>
        </tr>
        @endif
    </table>
</div>

{{-- Gray separator --}}
<div class="sep-gray" style="margin-top:8pt;"></div>

{{-- CLOSING TEXT + SIGNATURE --}}
<div class="band-top" style="padding-top:6pt; padding-bottom:4pt;">
    <div style="padding:0 4pt;">
        {!! nl2br(e($pdfClosingText)) !!}
        <p style="margin-top:10pt;">{{ $pdfFooterName }}</p>
    </div>
</div>

{{-- Gray separator --}}
<div class="sep-gray" style="margin-top:4pt;"></div>

{{-- ═══════════════════════════════════════════════════════ --}}
{{-- POSITION DETAIL PAGES                                  --}}
{{-- ═══════════════════════════════════════════════════════ --}}
<div style="page-break-after:always;"></div>

@foreach ($offert->positions as $key => $position)
@php
    $quantity       = max((float) ($position->quantity ?? 1), 1);
    $posTotalBrutto = isset($customPositionPrices[$position->id])
        ? $customPositionPrices[$position->id]['brutto']
        : (float) ($position->price_brutto ?? 0);
    $posTotalNetto  = isset($customPositionPrices[$position->id])
        ? $customPositionPrices[$position->id]['netto']
        : (float) ($position->price_discount ?? 0);
    $unitBrutto = $quantity > 0 ? $posTotalBrutto / $quantity : 0;
    $unitNetto  = $quantity > 0 ? $posTotalNetto  / $quantity : 0;
@endphp

{{-- Black top line on each position page --}}
<div style="border-top:0.75pt solid #000; margin-bottom:0;"></div>

{{-- POSITION HEADER ROW --}}
<table class="w100" style="margin-bottom:0;">
    <colgroup>
        <col style="width:8%">
        <col style="width:44%">
        <col style="width:10%">
        <col style="width:10%">
        <col style="width:10%">
        <col style="width:6%">
        <col style="width:12%">
    </colgroup>
    <tbody>
        <tr class="pos-header-row">
            <td>Pos.&nbsp;{{ $position->position_number }}{{ $position->is_optional ? ' (Option)' : '' }}</td>
            <td>{{ $position->description }}</td>
            <td class="pos-right">Brutto</td>
            <td class="pos-right">Rabatt</td>
            <td class="pos-right">Netto</td>
            <td style="text-align:center;">Stk.</td>
            <td class="pos-right">Total</td>
        </tr>
        <tr class="pos-value-row">
            <td colspan="2" style="font-weight:bold;">
                {{ $position->blocktype }}<br>
                <span style="font-weight:normal; font-size:8.5pt;">
                    B:{{ $position->b }}
                    H:{{ $position->h }}
                    T:{{ $position->t }} (in cm)
                </span>
            </td>
            <td class="pos-right">{{ $chf($unitBrutto) }}</td>
            <td class="pos-right">{{ $position->discount }}%</td>
            <td class="pos-right">{{ $chf($unitNetto) }}</td>
            <td style="text-align:center;">{{ $chInt($position->quantity) }}</td>
            <td class="pos-right">{{ $chf($posTotalNetto) }}</td>
        </tr>
    </tbody>
</table>

{{-- Gray separator --}}
<div style="border-top:0.75pt solid #808080; margin:0;"></div>

@php
    $groupedGroupElements = [];
    foreach ($position->elementsForPdf as $element) {
        foreach ($element->group_elements as $group_element) {
            foreach ($group_element->organigrams as $organigram) {
                if (empty($selectedOrganigramIds ?? []) || in_array($organigram->id, $selectedOrganigramIds ?? [])) {
                    $groupedGroupElements[$organigram->name][$group_element->name][] = [
                        'quantity'     => $element->pivot->quantity,
                        'element_name' => $element->name,
                        'is_optional'  => \App\Models\Position::truthyElementOptionalPivot($element->pivot->is_optional ?? null),
                    ];
                }
            }
        }
    }
    $desiredOrder = ['Rahme', 'Installationsmodule', 'Verrohrung'];
    $orderedGroupElements = [];
    foreach ($desiredOrder as $key_name) {
        if (isset($groupedGroupElements[$key_name])) {
            $orderedGroupElements[$key_name] = $groupedGroupElements[$key_name];
        }
    }
    foreach ($groupedGroupElements as $key_name => $value) {
        if (!isset($orderedGroupElements[$key_name])) {
            $orderedGroupElements[$key_name] = $value;
        }
    }
@endphp

{{-- DETAIL ROWS --}}
<table class="w100" style="background-color:#CFD8DC;">
    @foreach ($orderedGroupElements as $organigramName => $groupedElements)
    <tr>
        <td class="detail-label" style="background:#fff;">Enthalten {{ $organigramName }}:</td>
        <td colspan="2" style="background:#fff; padding:2pt 0;">
            @foreach ($groupedElements as $groupName => $groupElements)
            <table class="w100" style="border-collapse:collapse;">
                <tr>
                    <td class="detail-group">{{ $groupName }}</td>
                    <td class="detail-items">
                        @foreach ($groupElements as $groupElement)
                        <table class="qty-table">
                            <tr>
                                <td>{{ $groupElement['quantity'] }}&nbsp;x</td>
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

    {{-- FOOTNOTE ROW --}}
    <tr>
        <td colspan="3" style="background:#fff; padding:5pt 5pt 4pt;">
            @if($position->description2)
                {{ $position->description2 }}<br>
                <div style="border-top:0.5pt solid #ccc; margin:3pt 0;"></div>
            @endif
            <span style="font-size:8.5pt;">
                Rahmenprofile, Metallteile und Befestigungen grundiert, Wand-Boden und Decke schallentkoppelt nach
                SIA 181. (Fraunhofer Institut Stuttgart)<br>
                MPA gepr&uuml;ft, Brandschutzpr&uuml;fung und El 120 MPA erf&uuml;llt (VKF) Nr. 22523
            </span>
        </td>
    </tr>
</table>

{{-- TEAL ACCENT LINES --}}
<table class="w100" style="border-collapse:collapse; margin-top:0;">
    <tr class="teal-row"><td>&nbsp;</td></tr>
    <tr class="teal-row"><td>&nbsp;</td></tr>
    <tr class="teal-row"><td>&nbsp;</td></tr>
</table>

@if(!$loop->last)
<div style="page-break-after:always;"></div>
@endif

@endforeach
</body>
</html>
