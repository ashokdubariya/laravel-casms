<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Approval Proof - {{ $approval->title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'SN Pro', 'sans-serif';
            font-size: 11pt;
            line-height: 1.6;
            color: #0F172A;
        }
        
        .header {
            background-color: #1a425f;
            color: white;
            padding: 30px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            font-size: 24pt;
            margin-bottom: 10px;
        }
        
        .header .subtitle {
            font-size: 12pt;
            opacity: 0.9;
        }
        
        .content {
            padding: 0 30px;
        }
        
        .section {
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 14pt;
            font-weight: bold;
            color: #1a425f;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #1a425f;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            width: 30%;
            padding: 8px 0;
            font-weight: bold;
            color: #64748b;
        }
        
        .info-value {
            display: table-cell;
            padding: 8px 0;
            color: #0F172A;
        }
        
        .status-badge {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: bold;
            font-size: 12pt;
        }
        
        .status-approved {
            background-color: #85c34e;
            color: white;
        }
        
        .status-rejected {
            background-color: #ef4444;
            color: white;
        }
        
        .status-pending {
            background-color: #f59e0b;
            color: white;
        }
        
        .attachments-list {
            list-style: none;
            margin: 10px 0;
        }
        
        .attachments-list li {
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #cbd5e1;
            text-align: center;
            color: #64748b;
            font-size: 9pt;
        }
        
        .signature-box {
            border: 2px solid #cbd5e1;
            padding: 20px;
            margin: 20px 0;
            background-color: #f8fafc;
        }
        
        .timestamp {
            font-size: 9pt;
            color: #64748b;
            font-style: italic;
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100pt;
            opacity: 0.05;
            color: #1a425f;
            z-index: -1;
        }
    </style>
</head>
<body>
    <!-- Watermark -->
    <div class="watermark">{{ strtoupper($approval->status) }}</div>

    <!-- Header -->
    <div class="header">
        <h1>APPROVAL PROOF</h1>
        <div class="subtitle">{{ $company }}</div>
    </div>

    <!-- Content -->
    <div class="content">
        <!-- Status Section -->
        <div class="section">
            <div class="section-title">Status</div>
            <span class="status-badge status-{{ $approval->status }}">
                {{ strtoupper($approval->status) }}
            </span>
        </div>

        <!-- Approval Details -->
        <div class="section">
            <div class="section-title">Approval Details</div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Title:</div>
                    <div class="info-value">{{ $approval->title }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Version:</div>
                    <div class="info-value">{{ $approval->version }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Request ID:</div>
                    <div class="info-value">#{{ $approval->id }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Created:</div>
                    <div class="info-value">{{ $approval->created_at->format('F j, Y \a\t g:i A') }}</div>
                </div>
            </div>

            @if($approval->description)
                <div style="margin-top: 15px;">
                    <strong>Description:</strong><br>
                    {{ $approval->description }}
                </div>
            @endif
        </div>

        <!-- Client Information -->
        <div class="section">
            <div class="section-title">Client Information</div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Name:</div>
                    <div class="info-value">{{ $approval->client_name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email:</div>
                    <div class="info-value">{{ $approval->client_email }}</div>
                </div>
            </div>
        </div>

        <!-- Decision Details -->
        @if($approval->isApproved())
            <div class="section">
                <div class="section-title">Approval Confirmation</div>
                <div class="signature-box">
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">Decision:</div>
                            <div class="info-value" style="color: #85c34e; font-weight: bold;">APPROVED</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Approved On:</div>
                            <div class="info-value">{{ $approval->approved_at->format('F j, Y \a\t g:i A') }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">IP Address:</div>
                            <div class="info-value">{{ $approval->history()->action('approved')->first()?->ip_address ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($approval->isRejected())
            <div class="section">
                <div class="section-title">Rejection Details</div>
                <div class="signature-box">
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">Decision:</div>
                            <div class="info-value" style="color: #ef4444; font-weight: bold;">REJECTED</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Rejected On:</div>
                            <div class="info-value">{{ $approval->rejected_at->format('F j, Y \a\t g:i A') }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">IP Address:</div>
                            <div class="info-value">{{ $approval->history()->action('rejected')->first()?->ip_address ?? 'N/A' }}</div>
                        </div>
                    </div>
                    
                    @php
                        $rejectionEvent = $approval->history()->action('rejected')->first();
                    @endphp
                    
                    @if($rejectionEvent && $rejectionEvent->comment)
                        <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #cbd5e1;">
                            <strong>Client Feedback:</strong><br>
                            <div style="margin-top: 5px; font-style: italic;">
                                "{{ $rejectionEvent->comment }}"
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Attachments -->
        @if($approval->attachments->isNotEmpty())
            <div class="section">
                <div class="section-title">Attachments ({{ $approval->attachments->count() }})</div>
                <ul class="attachments-list">
                    @foreach($approval->attachments as $attachment)
                        <li>
                            @if($attachment->isUrl())
                                [LINK] {{ $attachment->url }}
                            @elseif($attachment->isImage())
                                [IMAGE] {{ $attachment->file_name }} ({{ $attachment->human_file_size }})
                            @else
                                [FILE] {{ $attachment->file_name }} ({{ $attachment->human_file_size }})
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Timeline -->
        <div class="section">
            <div class="section-title">Activity Timeline</div>
            @foreach($approval->history as $event)
                <div style="padding: 10px 0; border-bottom: 1px solid #e2e8f0;">
                    <div style="font-weight: bold;">{{ ucfirst(str_replace('_', ' ', $event->action)) }}</div>
                    <div class="timestamp">
                        {{ $event->created_at->format('F j, Y \a\t g:i A') }} 
                        by {{ $event->performed_by }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>This document was generated on {{ $generated_at->format('F j, Y \a\t g:i A') }}</p>
        <p style="margin-top: 5px;">{{ $company }} | Approval & Sign-Off Management System</p>
        <p style="margin-top: 10px; font-size: 8pt;">
            This is a legally binding record of client approval/rejection.
        </p>
    </div>
</body>
</html>
