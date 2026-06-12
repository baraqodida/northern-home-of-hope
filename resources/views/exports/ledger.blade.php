<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Member Structural Ledger</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; }
        h2 { text-align: center; color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #bdc3c7; padding: 10px; text-align: left; }
        th { background-color: #ecf0f1; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
    </style>
</head>
<body>
    <h2>Member Structural Ledger</h2>
    <p style="text-align: center;">Generated on: {{ now()->format('d M Y, H:i') }}</p>
    
    <table>
        <thead>
            <tr>
                <th>Member Name</th>
                <th>Group</th>
                <th>Sub-County</th>
                <th>Total Contribution (KES)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($members as $member)
            <tr>
                <td>{{ $member->name }}</td>
                <td>{{ $member->group->name ?? 'N/A' }}</td>
                <td>{{ $member->sub_county ?? 'N/A' }}</td>
                <td>{{ number_format($member->contribution->amount ?? 0, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>