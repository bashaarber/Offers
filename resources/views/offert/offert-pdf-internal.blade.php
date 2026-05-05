<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Offert Intern - {{ $offert->display_number }}</title>
    <style>
        @page { size: A4; margin: 12mm 15mm; }
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10pt;
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
        .totals-value { width:33%; text-align:right; padding:4pt 6pt; }

        /* ── Position block ── */
        .pos-header-row td, .pos-header-row th {
            font-weight: bold;
            font-size: 9.5pt;
            padding: 4pt 6pt;
            vertical-align: middle;
            border-bottom: 0.5pt solid #808080;
        }
        .pos-value-row td {
            font-weight: bold;
            font-size: 9.5pt;
            padding: 4pt 6pt;
            vertical-align: top;
            border-bottom: 0.5pt solid #808080;
        }
        .pos-right { text-align: right; }

        /* ── Detail rows ── */
        .detail-label { font-weight:bold; vertical-align:top; padding:3pt 5pt; width:38%; font-size:9pt; }
        .detail-group { font-weight:bold; vertical-align:top; padding:3pt 5pt; width:30%; text-align:right; font-size:9pt; white-space:nowrap; }
        .detail-items { vertical-align:top; padding:3pt 5pt; font-size:9pt; }
        .qty-table { width:100%; border-collapse:collapse; margin-bottom:1pt; }
        .qty-table td { padding:0; vertical-align:top; font-size:9pt; }
        .qty-table td:first-child { white-space:nowrap; padding-right:5pt; width:1%; }
        .material-list { font-size:8.5pt; color:#333; margin-top:1pt; padding-left:0; }

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
    $logoPath = public_path('images/sanivor.jpg');
    $logoSrc  = null;
    if (file_exists($logoPath)) {
        $logoSrc = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoPath));
    }

    $coefficientInternal = \App\Models\Coefficient::query()->first();

    $defaultUnsereRefInternal = '';
    if (\Illuminate\Support\Facades\Schema::hasColumn('coefficients', 'default_unsere_referenz') && $coefficientInternal) {
        $defaultUnsereRefInternal = (string) ($coefficientInternal->default_unsere_referenz ?? '');
    }

    $unsereReferenzLineInternal = trim((string) ($offert->user_sign ?? '')) !== ''
        ? trim((string) $offert->user_sign)
        : $defaultUnsereRefInternal;

    $defaultSignatureInternal = 'Arber Basha';
    if (\Illuminate\Support\Facades\Schema::hasColumn('coefficients', 'default_signature') && $coefficientInternal) {
        $defaultSignatureInternal = $coefficientInternal->default_signature ?? 'Arber Basha';
    }

    $pdfFooterNameInternal = trim((string) $unsereReferenzLineInternal) !== ''
        ? $unsereReferenzLineInternal
        : $defaultSignatureInternal;

    $pdfExternalClosingFallback = "Folgende Leistungen sind enthalten:\n- Transport\n- Ausmass\n- Holz (Sperrholz) 24mm: für Gleitstange, Glasstrennwand und Waschtisch\n\nFür weitere Fragen stehen wir Ihnen gerne zur Verfügung. Es würde uns freuen, diesen Auftrag für Sie ausführen zu dürfen. Es gelten unsere Allgemeinen Geschäftsbedingungen (AGB), die unter www.sanivor.ch zu finden sind.\n\nFreundliche Grüsse";

    $pdfClosingTextInternal = $pdfExternalClosingFallback;
    if (\Illuminate\Support\Facades\Schema::hasColumn('coefficients', 'pdf_external_closing_text') && $coefficientInternal) {
        $customClosing = $coefficientInternal->pdf_external_closing_text;
        if ($customClosing !== null && trim((string) $customClosing) !== '') {
            $pdfClosingTextInternal = (string) $customClosing;
        }
    }

    $chf = function ($value, int $decimals = 2) {
        return number_format((float) $value, $decimals, '.', "'");
    };
    $chInt = function ($value) {
        return number_format((float) $value, 0, '.', "'");
    };

    $clientAddressLines = preg_split('/\r\n|\r|\n/', (string) ($offert->client->address ?? ''));
    $vomDateInternal    = $offert->finish_date ?? $offert->create_date;
@endphp

{{-- ═══════════════════════════════════════════════════════ --}}
{{-- PAGE 1 — COVER                                         --}}
{{-- ═══════════════════════════════════════════════════════ --}}
<div class="cover-page">

{{-- HEADER: Logo + Company (left) | Client (right) --}}
<table class="w100" style="margin-bottom:18pt;">
    <tr>
        <td style="width:68%; vertical-align:top; padding-right:10pt; padding-bottom:10pt;">
            @if($logoSrc)
                <img src="{{ $logoSrc }}" style="width:240pt; height:52pt; margin-bottom:12pt;"><br>
            @endif
            <strong style="font-size:11pt;">Sanivor AG</strong><br>
            <span>Buckstrasse 1</span><br>
            <span>8317 Tagelswangen</span><br>
            <span>Tel +41 (0)52 213 20 90</span><br>
            <span>info@sanivor.ch</span><br>
            <span>www.sanivor.ch</span>
        </td>
        <td style="width:32%; vertical-align:top; padding-top:64pt; padding-left:0;">
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
<table class="w100" style="margin-bottom:0;">
    <tr>
        <td style="width:20%; font-weight:bold; font-size:12pt; padding:6pt 6pt;"><strong>Angebot Nr.</strong></td>
        <td style="width:30%; font-weight:bold; font-size:12pt; padding:6pt 6pt;"><strong>{{ $offert->display_number }}</strong></td>
        <td style="width:18%; font-weight:bold; font-size:12pt; padding:6pt 6pt;"><strong>Objekt:</strong></td>
        <td style="width:32%; font-weight:bold; font-size:12pt; padding:6pt 6pt;"><strong>{{ $offert->object }}</strong></td>
    </tr>
    <tr>
        <td style="font-weight:bold; font-size:12pt; padding:2pt 6pt 16pt;"><strong>Datum</strong></td>
        <td style="font-weight:bold; font-size:12pt; padding:2pt 6pt 16pt;"><strong>{{ \Carbon\Carbon::parse($offert->create_date)->format('d/m/Y') }}</strong></td>
        <td style="padding:2pt 6pt 16pt;"></td>
        <td style="font-weight:bold; font-size:12pt; padding:2pt 6pt 16pt;"><strong>{{ $offert->city }}</strong></td>
    </tr>
</table>

{{-- Gray separator --}}
<div class="sep-gray"></div>

{{-- IHR AUFTRAG INFO BLOCK --}}
<div class="band-top" style="padding-top:6pt; margin-bottom:0;">
    <table class="w100">
        <tr>
            <td style="width:20%; padding:5pt 6pt;"><strong>Ihr Auftrag</strong></td>
            <td style="width:30%; padding:5pt 6pt;">Email vom {{ $vomDateInternal ? \Carbon\Carbon::parse($vomDateInternal)->format('d/m/Y') : '' }}</td>
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
            <td style="padding:5pt 6pt 8pt;">{{ $unsereReferenzLineInternal }}</td>
            <td style="padding:5pt 6pt 8pt;"><strong>Lieferung</strong></td>
            <td style="padding:5pt 6pt 8pt;">{{ $offert->service }}</td>
        </tr>
    </table>
</div>

{{-- Gray separator --}}
<div class="sep-gray" style="margin-top:4pt;"></div>

{{-- TOTALS BOX (right half) --}}
@php
    $totalBrutto   = 0;
    $totalDiscount = 0;
    $totalNetto    = 0;
    $optionalPositions = 0;
    $totalMengeStk = (int) round(
        $offert->positions->sum(function ($p) { return (float) ($p->quantity ?? 0); })
    );
    foreach ($offert->positions as $position) {
        if (!$position->is_optional) {
            $totalBrutto   += $position->price_brutto;
            $totalNetto    += $position->price_discount;
            $totalDiscount += max($position->price_brutto - $position->price_discount, 0);
        } else {
            $optionalPositions++;
        }
    }
    $mwst   = $totalNetto * 0.081;
    $gesamt = $totalNetto + $mwst;
@endphp

<div class="band-top" style="padding-top:10pt;">
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

{{-- CLOSING TEXT + SIGNATURE (pushed toward bottom) --}}
<div class="cover-closing">
    <div style="page-break-inside:avoid;">
    <div class="band-top" style="padding-top:6pt; padding-bottom:4pt;">
        <div style="padding:0 4pt;">
            {!! nl2br(e($pdfClosingTextInternal)) !!}
            <p style="margin-top:10pt;">{{ $pdfFooterNameInternal }}</p>
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
    $unitBrutto     = $quantity > 0 ? (float) ($position->price_brutto ?? 0) / $quantity : 0;
    $discountFactor = (100 - (float) ($position->discount ?? 0)) / 100;
    $unitNetto      = $unitBrutto * $discountFactor;
    $totalNettoPosVal = $unitNetto * $quantity;
@endphp

<div style="page-break-inside:avoid; border:0.75pt solid #808080; margin-bottom:12pt;">

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
                <span style="font-weight:normal; font-size:7.5pt;">
                    B:{{ $position->b }}
                    H:{{ $position->h }}
                    T:{{ $position->t }} (in cm)
                </span>
            </td>
            <td class="pos-right">{{ $chf($unitBrutto) }}</td>
            <td class="pos-right">{{ $position->discount }}%</td>
            <td class="pos-right">{{ $chf($unitNetto) }}</td>
            <td style="text-align:center;">{{ $chInt($position->quantity) }}</td>
            <td class="pos-right">
                @if($position->is_optional)
                    Optional
                @else
                    {{ $chf($totalNettoPosVal) }}
                @endif
            </td>
        </tr>
    </tbody>
</table>

@php
    $groupedGroupElements = [];
    foreach ($position->elements as $element) {
        foreach ($element->group_elements as $group_element) {
            foreach ($group_element->organigrams as $organigram) {
                $groupedGroupElements[$organigram->name][$group_element->name][] = [
                    'quantity'     => $element->pivot->quantity,
                    'element_name' => $element->name,
                    'is_optional'  => \App\Models\Position::truthyElementOptionalPivot($element->pivot->is_optional ?? null),
                    'materials'    => $element->materials->map(function ($material) use ($element, $position) {
                        $positionMaterial = DB::table('position_materials')
                            ->where('position_id', $position->id)
                            ->where('element_id', $element->id)
                            ->where('material_id', $material->id)
                            ->first();
                        return [
                            'quantity' => $positionMaterial ? $positionMaterial->quantity : null,
                            'unit'     => $material->unit,
                            'name'     => $material->name,
                        ];
                    }),
                ];
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
            <table class="w100" style="border-collapse:collapse;">
                <tr>
                    <td class="detail-group">{{ $groupName }}</td>
                    <td class="detail-items">
                        @foreach ($groupElements as $groupElement)
                        <table class="qty-table">
                            <tr>
                                <td><strong>{{ $groupElement['quantity'] }}&nbsp;x</strong></td>
                                <td>
                                    <strong>{{ $groupElement['element_name'] }}{{ $groupElement['is_optional'] ? ' (Optional)' : '' }}</strong><br>
                                    @foreach ($groupElement['materials'] as $material)
                                        <span class="material-list">{{ $material['quantity'] }}{{ $material['unit'] }} {{ $material['name'] }}</span><br>
                                    @endforeach
                                </td>
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
        <td colspan="3" style="padding:5pt 5pt 4pt; border-top:0.5pt solid #808080;">
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
</body>
</html>
