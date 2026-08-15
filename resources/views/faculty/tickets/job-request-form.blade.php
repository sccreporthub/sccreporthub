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
            font-size: 10pt;
            background: #ccc;
            color: #000;
        }

        /* ── Page: exactly half short bond 5.5in x 8.5in ── */
        .page {
            width: 5.5in;
            height: 8.5in;
            background: #fff;
            margin: 16px auto;
            padding: 0.3in 0.35in 0.25in;
            border: 1px solid #999;
            overflow: hidden;
            position: relative;
        }

        /* ── Header ── */
        .header {
            display: flex;
            align-items: center;
            gap: 8px;
            padding-bottom: 5px;
            border-bottom: 1.5px solid #000;
            margin-bottom: 5px;
        }
        .header img { height: 44px; width: 44px; object-fit: contain; flex-shrink: 0; }
        .school-info { flex: 1; }
        .school-info .sname { font-size: 10.5pt; font-weight: bold; line-height: 1.2; }
        .school-info .ssub  { font-size: 7.5pt; line-height: 1.25; }
        .ticket-box {
            border: 1px solid #000;
            padding: 3px 6px;
            text-align: center;
            flex-shrink: 0;
        }
        .ticket-box .tlabel { font-size: 6.5pt; text-transform: uppercase; letter-spacing: 0.4px; color: #444; }
        .ticket-box .tvalue { font-size: 9pt; font-weight: bold; letter-spacing: 0.3px; }

        /* ── Title ── */
        .form-title {
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-decoration: underline;
            margin: 5px 0 6px;
        }

        /* ── Field rows ── */
        .field-row {
            display: flex;
            gap: 8px;
            margin-bottom: 5px;
            align-items: flex-end;
        }
        .field-group {
            display: flex;
            align-items: flex-end;
            gap: 3px;
            flex: 1;
        }
        .flabel {
            font-size: 8.5pt;
            white-space: nowrap;
            font-style: italic;
            flex-shrink: 0;
        }
        .fvalue {
            flex: 1;
            border-bottom: 0.8px solid #000;
            font-size: 9pt;
            font-weight: bold;
            padding-bottom: 1px;
            padding-left: 2px;
            min-height: 14px;
            line-height: 1.3;
        }

        /* ── Section A ── */
        .section-title {
            font-size: 9.5pt;
            font-weight: bold;
            margin: 6px 0 3px;
        }

        /* ── Checkboxes ── */
        .jtype-label {
            font-size: 8.5pt;
            font-style: italic;
            margin-bottom: 3px;
        }
        .cb-item {
            display: flex;
            align-items: flex-start;
            gap: 5px;
            margin-bottom: 3px;
            font-size: 8.5pt;
            line-height: 1.3;
        }
        .cb-box {
            width: 10px;
            height: 10px;
            border: 1px solid #000;
            flex-shrink: 0;
            margin-top: 1px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .cb-box.checked::after { content: '✓'; font-size: 8pt; line-height: 1; }
        .cb-text strong { font-weight: bold; }

        /* ── Signatures ── */
        .sig-section { margin-top: 10px; }
        .sig-intro {
            display: flex;
            justify-content: space-between;
            font-size: 8.5pt;
            font-style: italic;
            margin-bottom: 18px;
        }
        .sig-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .sig-left  { width: 44%; }
        .sig-right { width: 44%; text-align: center; }
        .sig-line {
            border-top: 0.8px solid #000;
            padding-top: 2px;
            font-size: 8.5pt;
            text-align: center;
        }
        .sig-rname { font-size: 9.5pt; font-weight: bold; text-align: center; }
        .sig-rrole { font-size: 8.5pt; text-align: center; }

        /* ── Note ── */
        .note {
            margin-top: 8px;
            font-size: 8pt;
            font-style: italic;
            border-top: 0.8px solid #000;
            padding-top: 4px;
            line-height: 1.3;
        }

        /* ── Footer ref ── */
        .foot-ref {
            font-size: 7pt;
            color: #666;
            text-align: right;
            margin-top: 4px;
        }

        /* ── Screen buttons ── */
        .no-print {
            text-align: center;
            padding: 12px;
        }
        .no-print button {
            padding: 7px 18px;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            margin: 0 4px;
            font-family: Arial, sans-serif;
        }
        .btn-print { background: #4f46e5; color: #fff; border: none; }
        .btn-close-btn { background: #fff; color: #555; border: 1px solid #ccc; }

        /* ── Print ── */
        @media print {
            @page {
                size: 5.5in 8.5in portrait;
                margin: 0;
            }
            html, body { background: #fff !important; }
            .no-print { display: none !important; }
            .page {
                width: 5.5in;
                height: 8.5in;
                margin: 0;
                border: none;
                padding: 0.3in 0.35in 0.25in;
                overflow: hidden;
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
        <img src="{{ asset('images/scc-logo.png') }}" alt="SCC">
        <div class="school-info">
            <div class="sname">Southern Christian College</div>
            <div class="ssub">United Church of Christ in the Philippines</div>
            <div class="ssub">Midsayap, 9410 Cotabato &nbsp;|&nbsp; Tel No. (064) 577-0184</div>
        </div>
        <div class="ticket-box">
            <div class="tlabel">Ticket No.</div>
            <div class="tvalue">{{ $ticket->ticket_number }}</div>
        </div>
    </div>

    {{-- Title --}}
    <div class="form-title">Job Request Form</div>

    {{-- Row 1: Office + Date --}}
    <div class="field-row">
        <div class="field-group" style="flex:2;">
            <span class="flabel">Requesting Office:</span>
            <span class="fvalue">{{ $ticket->user->department }}</span>
        </div>
        <div class="field-group" style="flex:1;">
            <span class="flabel">Date Filed:</span>
            <span class="fvalue">{{ $ticket->created_at->format('m/d/Y') }}</span>
        </div>
    </div>

    {{-- Row 2: Personnel + Contact --}}
    <div class="field-row">
        <div class="field-group" style="flex:2;">
            <span class="flabel">Requesting Personnel:</span>
            <span class="fvalue">{{ $ticket->user->full_name }}</span>
        </div>
        <div class="field-group" style="flex:1;">
            <span class="flabel">Contact No.:</span>
            <span class="fvalue">{{ $ticket->contact_number ?? '—' }}</span>
        </div>
    </div>

    {{-- Section A --}}
    <div class="section-title">A.&nbsp; Job Details</div>

    <div class="jtype-label">Job Type:</div>

    @php
        $cat = $ticket->issue_category;
        $matched = match($cat) {
            'electrical'        => 'Electrical Works',
            'plumbing'          => 'Mechanical & Utility Works',
            'welding'           => 'Installation & Fabrication',
            'carpentry','masonry','others' => 'General Repair & Maintenance',
            default             => 'General Repair & Maintenance',
        };
    @endphp

    <div class="cb-item">
        <div class="cb-box {{ $matched === 'General Repair & Maintenance' ? 'checked' : '' }}"></div>
        <div class="cb-text"><strong>General Repair &amp; Maintenance</strong> (repairs, servicing, cleaning, housekeeping, groundskeeping)</div>
    </div>
    <div class="cb-item">
        <div class="cb-box"></div>
        <div class="cb-text"><strong>Logistics &amp; Facility Support</strong> (painting, moving items, venue setup, other support services)</div>
    </div>
    <div class="cb-item">
        <div class="cb-box {{ $matched === 'Electrical Works' ? 'checked' : '' }}"></div>
        <div class="cb-text"><strong>Electrical Works</strong> (wiring, lighting, outlets, power supply)</div>
    </div>
    <div class="cb-item">
        <div class="cb-box {{ $matched === 'Mechanical & Utility Works' ? 'checked' : '' }}"></div>
        <div class="cb-text"><strong>Mechanical &amp; Utility Works</strong> (plumbing, ventilation and air-conditioning, refrigeration)</div>
    </div>
    <div class="cb-item">
        <div class="cb-box {{ $matched === 'Installation & Fabrication' ? 'checked' : '' }}"></div>
        <div class="cb-text"><strong>Installation &amp; Fabrication</strong> (installation, assembly, carpentry, welding/metal works, construction)</div>
    </div>

    {{-- Location --}}
    <div class="field-row" style="margin-top:5px;">
        <div class="field-group">
            <span class="flabel">Location/Area <em>(specify)</em>:</span>
            <span class="fvalue">{{ $ticket->facility?->full_location ?? 'Not specified' }}</span>
        </div>
    </div>

    {{-- Facility/Fixture --}}
    <div class="field-row">
        <div class="field-group">
            <span class="flabel">Facility/Fixture/Equipment <em>(for repair/servicing)</em>:</span>
            <span class="fvalue">{{ $ticket->title }}</span>
        </div>
    </div>

    {{-- Signatures --}}
    <div class="sig-section">
        <div class="sig-intro">
            <span>Endorsed by:</span>
            <span>Approved:</span>
        </div>
        <div class="sig-row">
            <div class="sig-left">
                <div class="sig-line">Head of Unit/Activity In-charge</div>
            </div>
            <div class="sig-right">
                <div class="sig-rname">EDWIN T. BALAKI, Ph.D.</div>
                <div class="sig-rrole">College President</div>
            </div>
        </div>
    </div>

    {{-- Note --}}
    <div class="note">
        <em>Note: Attach request form (if any) and submit this job request form to the Building &amp; Grounds Supervisor</em>
    </div>

    {{-- Footer --}}
    <div class="foot-ref">
        {{ $ticket->ticket_number }} &nbsp;|&nbsp; SCC ReportHub &nbsp;|&nbsp; {{ now()->format('F d, Y') }}
    </div>

</div>

<div class="no-print">
    <button class="btn-print" onclick="window.print()">🖨️ Print Form</button>
    <button class="btn-close-btn" onclick="window.close()">Close</button>
</div>

</body>
</html>
