<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Request Form – {{ $ticket->ticket_number }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            background: #e0e0e0;
            color: #000;
        }

        /* Half short bond: 5.5in x 8.5in */
        .page {
            width: 5.5in;
            min-height: 8.5in;
            background: #fff;
            margin: 20px auto;
            padding: 0.4in 0.45in 0.35in;
            border: 1px solid #ccc;
            position: relative;
        }

        /* ── Header ── */
        .header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 6px;
            border-bottom: 1.5px solid #000;
            margin-bottom: 6px;
        }
        .header img {
            height: 50px;
            width: 50px;
            object-fit: contain;
        }
        .header .school-info {
            flex: 1;
            text-align: left;
        }
        .header .school-info .school-name {
            font-size: 11pt;
            font-weight: bold;
            line-height: 1.2;
        }
        .header .school-info .school-sub {
            font-size: 8.5pt;
            line-height: 1.3;
        }
        .header .ticket-ref-box {
            text-align: right;
            font-size: 8pt;
            border: 1px solid #000;
            padding: 4px 7px;
            border-radius: 3px;
        }
        .header .ticket-ref-box .ref-label {
            font-size: 7pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #555;
        }
        .header .ticket-ref-box .ref-value {
            font-size: 9.5pt;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        /* ── Form Title ── */
        .form-title {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 8px 0 10px;
            text-decoration: underline;
        }

        /* ── Field rows ── */
        .field-row {
            display: flex;
            gap: 12px;
            margin-bottom: 7px;
            align-items: flex-end;
        }
        .field-group {
            display: flex;
            align-items: flex-end;
            gap: 4px;
            flex: 1;
        }
        .field-label {
            font-size: 9.5pt;
            white-space: nowrap;
            font-style: italic;
        }
        .field-value {
            flex: 1;
            border-bottom: 1px solid #000;
            min-width: 60px;
            font-size: 10pt;
            font-weight: bold;
            padding-bottom: 1px;
            padding-left: 3px;
            min-height: 16px;
        }

        /* ── Section A ── */
        .section-title {
            font-size: 10pt;
            font-weight: bold;
            margin: 10px 0 5px;
        }

        /* ── Job Type checkboxes ── */
        .job-type-label {
            font-size: 9pt;
            font-style: italic;
            margin-bottom: 4px;
        }
        .checkbox-item {
            display: flex;
            align-items: flex-start;
            gap: 6px;
            margin-bottom: 4px;
            font-size: 9pt;
        }
        .checkbox-item .box {
            width: 11px;
            height: 11px;
            border: 1.2px solid #000;
            flex-shrink: 0;
            margin-top: 1px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9pt;
            font-weight: bold;
        }
        .checkbox-item .box.checked::after {
            content: '✓';
            font-size: 8pt;
        }
        .checkbox-item .item-text strong { font-weight: bold; }

        /* ── Signature section ── */
        .sig-section {
            margin-top: 18px;
        }
        .sig-labels {
            display: flex;
            justify-content: space-between;
            margin-bottom: 22px;
            font-size: 9.5pt;
            font-style: italic;
        }
        .sig-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .sig-left {
            width: 45%;
        }
        .sig-right {
            width: 45%;
            text-align: center;
        }
        .sig-line {
            border-top: 1px solid #000;
            padding-top: 3px;
            font-size: 9pt;
            text-align: center;
        }
        .sig-right-name {
            font-size: 10pt;
            font-weight: bold;
            text-align: center;
        }
        .sig-right-role {
            font-size: 9pt;
            text-align: center;
        }

        /* ── Note ── */
        .note {
            margin-top: 12px;
            font-size: 8.5pt;
            font-style: italic;
            border-top: 1px solid #000;
            padding-top: 5px;
        }

        /* ── Ticket reference ── */
        .ticket-ref {
            font-size: 7.5pt;
            color: #555;
            text-align: right;
            margin-top: 6px;
        }

        /* ── Print/Close buttons ── */
        .no-print {
            text-align: center;
            padding: 14px;
        }
        .no-print button {
            padding: 8px 20px;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            margin: 0 4px;
            font-family: Arial, sans-serif;
        }
        .btn-print { background: #4f46e5; color: #fff; border: none; }
        .btn-close-btn { background: #fff; color: #555; border: 1px solid #ccc; }

        /* ── Print media ── */
        @media print {
            @page {
                size: 5.5in 8.5in portrait;
                margin: 0;
            }
            html, body { background: #fff !important; }
            .no-print { display: none !important; }
            .page {
                width: 5.5in;
                min-height: 8.5in;
                margin: 0;
                border: none;
                padding: 0.4in 0.45in 0.35in;
            }
        }
    </style>
</head>
<body>

<div class="no-print">
    <button class="btn-print" onclick="window.print()">🖨️ Print Form</button>
    <button class="btn-close-btn" onclick="window.close()">Close</button>
</div>

<div class="page">

    {{-- Header --}}
    <div class="header">
        <img src="{{ asset('images/scc-logo.png') }}" alt="SCC Logo">
        <div class="school-info">
            <div class="school-name">Southern Christian College</div>
            <div class="school-sub">United Church of Christ in the Philippines</div>
            <div class="school-sub">Midsayap, 9410 Cotabato</div>
            <div class="school-sub">Tel No. (064) 577-0184</div>
        </div>
        <div class="ticket-ref-box">
            <div class="ref-label">Ticket No.</div>
            <div class="ref-value">{{ $ticket->ticket_number }}</div>
        </div>
    </div>

    {{-- Title --}}
    <div class="form-title">Job Request Form</div>

    {{-- Requesting Office & Date --}}
    <div class="field-row">
        <div class="field-group" style="flex:2;">
            <span class="field-label">Requesting Office:</span>
            <span class="field-value">{{ $ticket->user->department }}</span>
        </div>
        <div class="field-group" style="flex:1;">
            <span class="field-label">Date Filed:</span>
            <span class="field-value">{{ $ticket->created_at->format('m/d/Y') }}</span>
        </div>
    </div>

    {{-- Requesting Personnel & Contact --}}
    <div class="field-row">
        <div class="field-group" style="flex:2;">
            <span class="field-label">Requesting Personnel:</span>
            <span class="field-value">{{ $ticket->user->full_name }}</span>
        </div>
        <div class="field-group" style="flex:1;">
            <span class="field-label">Contact No.:</span>
            <span class="field-value">{{ $ticket->contact_number ?? '—' }}</span>
        </div>
    </div>

    {{-- Section A --}}
    <div class="section-title">A.&nbsp; Job Details</div>

    {{-- Job Type checkboxes --}}
    <div class="job-type-label">Job Type:</div>

    @php
        $category = $ticket->issue_category;
        $categoryMap = [
            'electrical' => 'Electrical Works',
            'plumbing'   => 'Mechanical & Utility Works',
            'carpentry'  => 'General Repair & Maintenance',
            'masonry'    => 'General Repair & Maintenance',
            'welding'    => 'Installation & Fabrication',
            'others'     => 'General Repair & Maintenance',
        ];
        $matched = $categoryMap[$category] ?? '';
    @endphp

    <div class="checkbox-item">
        <div class="box {{ $matched === 'General Repair & Maintenance' ? 'checked' : '' }}"></div>
        <div class="item-text"><strong>General Repair &amp; Maintenance</strong> (repairs, servicing, cleaning, housekeeping, groundskeeping)</div>
    </div>
    <div class="checkbox-item">
        <div class="box {{ $matched === 'Logistics & Facility Support' ? 'checked' : '' }}"></div>
        <div class="item-text"><strong>Logistics &amp; Facility Support</strong> (painting, moving items, venue setup, other support services)</div>
    </div>
    <div class="checkbox-item">
        <div class="box {{ $matched === 'Electrical Works' ? 'checked' : '' }}"></div>
        <div class="item-text"><strong>Electrical Works</strong> (wiring, lighting, outlets, power supply)</div>
    </div>
    <div class="checkbox-item">
        <div class="box {{ $matched === 'Mechanical & Utility Works' ? 'checked' : '' }}"></div>
        <div class="item-text"><strong>Mechanical &amp; Utility Works</strong> (plumbing, ventilation and air-conditioning, refrigeration)</div>
    </div>
    <div class="checkbox-item">
        <div class="box {{ $matched === 'Installation & Fabrication' ? 'checked' : '' }}"></div>
        <div class="item-text"><strong>Installation &amp; Fabrication</strong> (installation, assembly, carpentry, welding/metal works, construction)</div>
    </div>

    {{-- Location --}}
    <div class="field-row" style="margin-top:8px;">
        <div class="field-group">
            <span class="field-label">Location/Area <em>(specify)</em>:</span>
            <span class="field-value">{{ $ticket->facility?->full_location ?? 'Not specified' }}</span>
        </div>
    </div>

    {{-- Facility/Fixture --}}
    <div class="field-row">
        <div class="field-group">
            <span class="field-label">Facility/Fixture/Equipment <em>(for repair/servicing)</em>:</span>
            <span class="field-value">{{ $ticket->title }}</span>
        </div>
    </div>

    {{-- Description --}}
    <div class="field-row">
        <div class="field-group">
            <span class="field-label">Details/Description:</span>
            <span class="field-value" style="white-space:normal; line-height:1.4;">{{ $ticket->description }}</span>
        </div>
    </div>

    {{-- Signatures --}}
    <div class="sig-section">
        <div class="sig-labels">
            <span>Endorsed by:</span>
            <span>Approved:</span>
        </div>
        <div class="sig-row">
            <div class="sig-left">
                <div class="sig-line">Head of Unit/Activity In-charge</div>
            </div>
            <div class="sig-right">
                <div class="sig-right-name">EDWIN T. BALAKI, Ph.D.</div>
                <div class="sig-right-role">College President</div>
            </div>
        </div>
    </div>

    {{-- Note --}}
    <div class="note">
        <em>Note: Attach request form (if any) and submit this job request form to the Building &amp; Grounds Supervisor</em>
    </div>

    {{-- Ticket reference --}}
    <div class="ticket-ref">
        Ref: {{ $ticket->ticket_number }} &nbsp;|&nbsp; Generated via SCC ReportHub &nbsp;|&nbsp; {{ now()->format('F d, Y') }}
    </div>

</div>

<div class="no-print">
    <button class="btn-print" onclick="window.print()">🖨️ Print Form</button>
    <button class="btn-close-btn" onclick="window.close()">Close</button>
</div>

</body>
</html>
