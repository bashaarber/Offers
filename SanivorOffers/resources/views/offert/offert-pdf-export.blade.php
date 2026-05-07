<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Offert - {{ $offert->display_number }}</title>
    <style>
        @page { size: A4; margin: 12mm 15mm; }
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
        .sep-black { width:100%; border-top:0.75pt solid #000000; margin:8pt 0; }
        .sep-gray  { width:100%; border-top:0.75pt solid #808080; margin:0; }

        /* ── Blue-gray band (top of each section) ── */
        .band-top  { border-top:1pt solid #CFD8DC; }

        /* ── Totals box ── */
        .totals-label { width:55%; text-align:left; padding:4pt 6pt; }
        .totals-unit  { width:12%; text-align:left; padding:4pt 3pt; }
        .totals-value { width:33%; text-align:left; padding:4pt 6pt; }

        /* ── Position block ── */
        .pos-header-row td, .pos-header-row th {
            font-weight: bold;
            font-size: 8pt;
            padding: 3pt 4pt;
            vertical-align: top;
            border-bottom: 0.5pt solid #808080;
        }
        .pos-value-row td {
            font-weight: bold;
            font-size: 8pt;
            padding: 3pt 4pt;
            vertical-align: top;
            border-bottom: 0.5pt solid #808080;
        }
        .pos-right { text-align: right; }
        .pos-table { table-layout: fixed; }
        .pos-description {
            padding-left: 2pt !important;
            padding-right: 1pt !important;
            white-space: normal;
            overflow-wrap: anywhere;
        }
        .pos-metric {
            padding-left: 5pt !important;
            padding-right: 5pt !important;
            white-space: nowrap;
            text-align: left;
        }
        .pos-metric-header { text-align: left !important; }
        .pos-total { text-align: left !important; }

        /* ── Detail rows ── */
        .detail-label { font-weight:bold; vertical-align:top; padding:3pt 5pt; width:38%; font-size:8.5pt; }
        .detail-group { font-weight:bold; vertical-align:top; padding:0 5pt; width:21%; text-align:right; font-size:8.5pt; white-space:nowrap; }
        .detail-items { vertical-align:top; padding:0 4pt; font-size:8.5pt; }
        .detail-row-table { width:100%; border-collapse:collapse; table-layout:fixed; }
        .qty-table { width:100%; border-collapse:collapse; margin-bottom:1pt; }
        .qty-table td { padding:0; vertical-align:top; font-size:8.5pt; }
        .qty-table td:first-child { white-space:nowrap; padding-right:5pt; width:1%; }
        .pos-nowrap { white-space: nowrap; }

        /* ── Footnote / closing ── */
        .footnote { font-size:9.5pt; padding:6pt 5pt; }

        /* ── Teal accent row ── */
        .teal-row td {
            height:13pt;
            border-bottom:0.75pt solid #63C2DE;
            padding:0;
            font-size:0.1pt;
        }

        .cover-page {
            position: relative;
            min-height: 248mm;
        }

        .cover-closing {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
        }
    </style>
</head>
<body>
@php
    $logoPath = public_path('images/sanivor-logo.png');
    $logoSrc  = null;
    if (file_exists($logoPath)) {
        $logoSrc = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
    } else {
        $logoFallbackPath = public_path('images/sanivor.jpg');
        if (file_exists($logoFallbackPath)) {
            $logoSrc = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoFallbackPath));
        }
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
<div class="cover-page">

{{-- HEADER: Logo + Company (left) | Client (right) --}}
<table class="w100" style="margin-bottom:18pt;">
    <tr>
        <td style="width:63%; vertical-align:top; padding-right:10pt; padding-bottom:10pt;">
            @if($logoSrc)
                <img src="{{ $logoSrc }}" style="width:240pt; height:52pt; margin-bottom:12pt;"><br>
            @endif
            <strong style="font-size:10pt;">Sanivor AG</strong><br>
            <span>Buckstrasse 1</span><br>
            <span>8317 Tagelswangen</span><br>
            <span>Tel +41 (0)52 213 20 90</span><br>
            <span style="color:#1155cc;">info@sanivor.ch</span><br>
            <span style="color:#1155cc;">www.sanivor.ch</span>
        </td>
        <td style="width:37%; vertical-align:top; padding-top:64pt; padding-left:0; text-align:left;">
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
<table class="w100 pos-table" style="margin-bottom:0;">
    <tr>
        <td style="width:20%; font-weight:bold; font-size:10pt; padding:0 6pt;"><strong>Angebot Nr.</strong></td>
        <td style="width:30%; font-weight:bold; font-size:10pt; padding:0 6pt;"><strong>{{ $offert->display_number }}</strong></td>
        <td style="width:13%; font-weight:bold; font-size:10pt; padding:0 6pt 0 0;"><strong>Objekt:</strong></td>
        <td style="width:37%; font-weight:bold; font-size:10pt; padding:0 6pt 0 0;"><strong>{{ $offert->object }}</strong></td>
    </tr>
    <tr>
        <td style="font-weight:bold; font-size:8.5pt; padding:0 6pt 16pt;"><strong>Datum</strong></td>
        <td style="font-weight:bold; font-size:8.5pt; padding:0 6pt 16pt;"><strong>{{ \Carbon\Carbon::parse($offert->create_date)->format('d/m/Y') }}</strong></td>
        <td style="padding:0 6pt 16pt 0;"></td>
        <td style="font-weight:bold; font-size:10pt; padding:0 6pt 16pt 0;"><strong>{{ $offert->city }}</strong></td>
    </tr>
</table>

{{-- Gray separator --}}
<div class="sep-gray"></div>

{{-- IHR AUFTRAG INFO BLOCK --}}
<div class="band-top" style="padding-top:6pt; margin-bottom:0;">
    <table class="w100" style="font-size:8pt;">
        <tr>
            <td style="width:20%; padding:5pt 6pt;"><strong>Ihr Auftrag</strong></td>
            <td style="width:30%; padding:5pt 6pt;">Email vom {{ $vomDate ? \Carbon\Carbon::parse($vomDate)->format('d/m/Y') : '' }}</td>
            <td style="width:23%; padding:5pt 6pt;"><strong>Angebot Gültigkeit</strong></td>
            <td style="width:27%; padding:5pt 6pt;">{{ $offert->validity }}</td>
        </tr>
        <tr>
            <td style="padding:5pt 6pt;"><strong>Ihre Referenz</strong></td>
            <td style="padding:5pt 6pt;">{{ $offert->client_sign }}</td>
            <td style="padding:5pt 6pt;"><strong>Zahlungskonditionen</strong></td>
            <td style="padding:5pt 6pt;">{{ $offert->payment_conditions }}</td>
        </tr>
        <tr>
            <td style="padding:5pt 6pt 8pt;"><strong>Unsere Referenz</strong></td>
            <td style="padding:5pt 6pt 8pt;">{{ $unsereReferenzLine }}</td>
            <td style="padding:5pt 6pt 8pt;"><strong>Lieferung</strong></td>
            <td style="padding:5pt 6pt 8pt;">{{ $offert->service }}</td>
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

<div class="band-top" style="padding-top:10pt;">
    <table style="width:50%; margin-left:50%;">
        <tr>
            <td class="totals-label" style="padding-bottom:8pt;"><strong>Total Elemente:</strong></td>
            <td class="totals-unit" style="padding-bottom:8pt;"><strong>Stk.</strong></td>
            <td class="totals-value" style="padding-bottom:8pt;"><strong>{{ $chInt($totalMengeStk) }}</strong></td>
        </tr>
        <tr>
            <td class="totals-label" style="padding-top:0; padding-bottom:0;">Total Brutto</td>
            <td class="totals-unit" style="padding-top:0; padding-bottom:0;">CHF</td>
            <td class="totals-value" style="padding-top:0; padding-bottom:0;">{{ $chf($totalBrutto) }}</td>
        </tr>
        <tr>
            <td class="totals-label" style="padding-top:0; padding-bottom:8pt;">Rabatt</td>
            <td class="totals-unit" style="padding-top:0; padding-bottom:8pt;">CHF</td>
            <td class="totals-value" style="padding-top:0; padding-bottom:8pt;">-{{ $chf($totalDiscount) }}</td>
        </tr>
        <tr>
            <td class="totals-label" style="padding-top:0; padding-bottom:0;"><strong>Total Netto</strong></td>
            <td class="totals-unit" style="padding-top:0; padding-bottom:0;"><strong>CHF</strong></td>
            <td class="totals-value" style="padding-top:0; padding-bottom:0;"><strong>{{ $chf($totalNetto) }}</strong></td>
        </tr>
        <tr>
            <td class="totals-label" style="padding-top:0; padding-bottom:0;">MwSt. 8.1%</td>
            <td class="totals-unit" style="padding-top:0; padding-bottom:0;">CHF</td>
            <td class="totals-value" style="padding-top:0; padding-bottom:0;">{{ $chf($mwst) }}</td>
        </tr>
        <tr>
            <td class="totals-label" style="padding-top:0; padding-bottom:0;"><strong>Gesamt</strong></td>
            <td class="totals-unit" style="padding-top:0; padding-bottom:0;"><strong>CHF</strong></td>
            <td class="totals-value" style="padding-top:0; padding-bottom:0;"><strong>{{ $chf($gesamt) }}</strong></td>
        </tr>
        @if($optionalPositions > 0)
        <tr>
            <td colspan="3" style="font-size:9pt; padding:5pt 6pt 0;">
                * {{ $optionalPositions }} optionale Position(en) sind im Gesamtpreis nicht enthalten.
            </td>
        </tr>
        @endif
    </table>
</div>

{{-- Gray separator --}}
<div class="sep-gray" style="margin-top:8pt;"></div>

{{-- CLOSING TEXT + SIGNATURE (pushed toward bottom) --}}
<div class="cover-closing">
    <div style="page-break-inside:avoid;">
    <div class="band-top" style="padding-top:6pt; padding-bottom:4pt;">
        <div style="padding:0 4pt;">
            {!! nl2br(e($pdfClosingText)) !!}
            <p style="margin-top:10pt;">{{ $pdfFooterName }}</p>
        </div>
    </div>

{{-- Gray separator --}}
    <div class="sep-gray" style="margin-top:4pt;"></div>
    </div>
</div>
</div>

{{-- ═══════════════════════════════════════════════════════ --}}
{{-- POSITION DETAIL PAGES                                  --}}
{{-- ═══════════════════════════════════════════════════════ --}}
@if($offert->positions->count() > 0)
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

<div style="page-break-inside:avoid; border:0.75pt solid #808080; margin-bottom:12pt;">

{{-- POSITION HEADER ROW --}}
<table class="pos-table" style="width:100%; margin-bottom:0; table-layout:fixed;">
    <tbody>
        @php
            $positionDescription = trim((string) $position->description) !== ''
                ? (string) $position->description
                : (
                    trim((string) ($position->blocktype ?? '')) !== ''
                        ? (string) $position->blocktype
                        : ''
                );
        @endphp
        <tr class="pos-header-row">
            <td class="pos-nowrap" style="width:9%;">Pos.&nbsp;{{ $position->position_number }}{{ $position->is_optional ? ' (Option)' : '' }}</td>
            <td class="pos-description" style="width:41%;">
                {!! $positionDescription !== '' ? e($positionDescription) : '&nbsp;' !!}
            </td>
            <td class="pos-metric pos-metric-header" style="width:10%;">Brutto</td>
            <td class="pos-metric pos-metric-header" style="width:10%;">Rabatt</td>
            <td class="pos-metric pos-metric-header" style="width:10%;">Netto</td>
            <td class="pos-metric pos-metric-header" style="width:10%;">Stk.</td>
            <td class="pos-metric pos-metric-header pos-total" style="width:10%;">Total</td>
        </tr>
        <tr class="pos-value-row">
            <td colspan="2" style="font-weight:bold; width:50%;">
                @if(trim((string) ($position->blocktype ?? '')) !== '')
                    {{ $position->blocktype }}<br>
                @endif
                <span style="font-weight:bold; font-size:8pt;">
                    B:{{ $position->b }}
                    H:{{ $position->h }}
                    T:{{ $position->t }} (in cm)
                </span>
            </td>
            <td class="pos-metric">{{ $chf($unitBrutto) }}</td>
            <td class="pos-metric">{{ $chf($position->discount) }}%</td>
            <td class="pos-metric">{{ $chf($unitNetto) }}</td>
            <td class="pos-metric">{{ $chInt($position->quantity) }}</td>
            <td class="pos-metric pos-total">{{ $chf($posTotalNetto) }}</td>
        </tr>
    </tbody>
</table>

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
<table class="w100">
    @foreach ($orderedGroupElements as $organigramName => $groupedElements)
    <tr>
        <td class="detail-label" style="border-bottom:0.5pt solid #808080;">Enthalten {{ $organigramName }}:</td>
        <td colspan="2" style="padding:2pt 0; border-bottom:0.5pt solid #808080;">
            @foreach ($groupedElements as $groupName => $groupElements)
            <table class="detail-row-table">
                <tr>
                    <td class="detail-group">{{ $groupName }}</td>
                    <td class="detail-items">
                        <table class="qty-table">
                            @foreach ($groupElements as $groupElement)
                            <tr>
                                <td>{{ $groupElement['quantity'] }}&nbsp;x</td>
                                <td>{{ $groupElement['element_name'] }}{{ $groupElement['is_optional'] ? ' (opt*)' : '' }}</td>
                            </tr>
                            @endforeach
                        </table>
                    </td>
                </tr>
            </table>
            @endforeach
        </td>
    </tr>
    @endforeach

    {{-- FOOTNOTE ROW --}}
    <tr>
        <td colspan="3" style="padding:5pt 5pt 4pt; border-top:0.5pt solid #808080;">
            @if($position->description2)
                {{ $position->description2 }}<br>
                <div style="border-top:0.5pt solid #ccc; margin:3pt 0;"></div>
            @endif
            <span style="font-size:7.5pt;">
                Rahmenprofile, Metallteile und Befestigungen grundiert, Wand-Boden und Decke schallentkoppelt nach
                SIA 181. (Fraunhofer Institut Stuttgart)<br>
                MPA gepr&uuml;ft, Brandschutzpr&uuml;fung und El 120 MPA erf&uuml;llt (VKF) Nr. 22523
            </span>
        </td>
    </tr>
</table>

</div>{{-- end page-break-inside:avoid --}}

@endforeach
@endif

<script type="text/php">
    if (isset($pdf)) {
        $font = $fontMetrics->getFont('DejaVu Sans', 'normal');
        $size = 9;
        $w = $pdf->get_width();
        $h = $pdf->get_height();
        $pdf->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) use ($font, $size, $w, $h) {
            if ($pageNumber === 1) {
                return;
            }
            $text = $pageNumber . '/' . $pageCount;
            $textWidth = $fontMetrics->getTextWidth($text, $font, $size);
            $x = ($w - $textWidth) / 2;
            $y = $h - 25;
            $canvas->text($x, $y, $text, $font, $size);
        });
    }
</script>
</body>
</html>
