<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; color: #2d3748; margin: 0; font-size: 10pt; }
        .header { border-bottom: 3px solid #1a365d; padding-bottom: 10px; margin-bottom: 20px; }
        .title { font-size: 20pt; font-weight: bold; color: #1a365d; text-transform: uppercase; }
        .meta-table { width: 100%; margin-bottom: 20px; font-size: 9pt; }
        .ledger-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .ledger-table th { background-color: #1a365d; color: white; padding: 8px; text-transform: uppercase; font-size: 8pt; text-align: left; }
        .ledger-table td { padding: 8px; border-bottom: 1px solid #e2e8f0; font-size: 9pt; }
        .group-row { background-color: #edf2f7; font-weight: bold; }
        .text-right { text-align: right; }
        .total-box { margin-top: 20px; text-align: right; font-size: 14pt; font-weight: bold; color: #1a365d; border-top: 2px solid #1a365d; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Northern Home of Hope</div>
        <div>Official Financial Contribution Structural Ledger Statement</div>
    </div>

    <table class="meta-table">
        <tr>
            <td><strong>Export Date:</strong> {{ now()->format('F d, Y H:i A') }}</td>
            <td class="text-right"><strong>Status:</strong> Active Ledger Extract</td>
        </tr>
    </table>

    <table class="ledger-table">
        <thead>
            <tr>
                <th>Member Identity</th>
                <th>Group Pool</th>
                <th>Contact</th>
                <th>Regional Details</th>
                <th>Purpose</th>
                <th>Status</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $currentGroup = null; 
                $runningTotal = 0; 
            @endphp
            @foreach($contributions as $item)
                @php 
                    $memberGroupName = $item->user->group ? $item->user->group->name : 'Unassigned'; 
                    $runningTotal += $item->amount;
                @endphp
                
                @if($currentGroup !== $memberGroupName)
                    @php $currentGroup = $memberGroupName; @endphp
                    <tr class="group-row">
                        <td colspan="7" style="padding: 10px;">🚀 {{ strtoupper($currentGroup) }} MEMBERS</td>
                    </tr>
                @endif
                
                <tr>
                    <td>{{ $item->user->name }}</td>
                    <td>{{ $memberGroupName }}</td>
                    <td>{{ $item->user->phone_number ?? 'N/A' }}</td>
                    <td>{{ $item->user->county }} / {{ $item->user->sub_county }} / {{ $item->user->ward }}</td>
                    <td>{{ $item->purpose }}</td>
                    <td>{{ ucfirst($item->status) }}</td>
                    <td class="text-right">Ksh {{ number_format($item->amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        Grand Total: Ksh {{ number_format($runningTotal, 2) }}
    </div>
</body>
</html>