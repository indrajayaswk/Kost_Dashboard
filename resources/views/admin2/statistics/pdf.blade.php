<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics Report</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { text-align: center; color: #333; margin-bottom: 5px; }
        .subtitle { text-align: center; color: #666; font-size: 18px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ccc; text-align: left; }
        th { background-color: #f4f4f4; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        tr:hover { background-color: #f1f1f1; }
    </style>
</head>
<body>
    <h1>Statistics Report</h1>
    <div class="subtitle">{{ \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->translatedFormat('F Y') }}</div>

    <!-- Summary Statistics -->
    <div style="margin-bottom: 20px;">
        <p><strong style="display: inline-block; min-width: 200px;">Total Unpaid</strong>: Rp {{ number_format($totalUnpaid, 0, ',', '.') }}</p>
        <p><strong style="display: inline-block; min-width: 200px;">Total Unpaid Room Prices</strong>: Rp {{ number_format($totalRoomPrices, 0, ',', '.') }}</p>
        <p><strong style="display: inline-block; min-width: 200px;">Paid Percentage</strong>: {{ $paidPercentage }}%</p>
        <p><strong style="display: inline-block; min-width: 200px;">Unpaid Percentage</strong>: {{ $unpaidPercentage }}%</p>
        <p><strong style="display: inline-block; min-width: 200px;">Total kWh Used</strong>: {{ number_format($totalKwhCurrentMonth, 0, ',', '.') }} kWh</p>
        <p><strong style="display: inline-block; min-width: 200px;">Total Cost</strong>: Rp {{ number_format($totalCostCurrentMonth, 0, ',', '.') }}</p>
    </div>
    


    <!-- Table Data -->
    <h2>Room Details</h2>
    <table>
        <thead>
            <tr>
                <th>Room Number</th>
                <th>kWh Number</th>
                <th>Room Price</th>
                <th>Total Price</th>
                <th>Sum (Room Price + Total Price)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tableData as $row)
                <tr>
                    <td>{{ $row['room_number'] }}</td>
                    <td>{{ number_format($row['kwh_number'], 0, ',', '.') }} kWh</td>
                    <td>Rp {{ number_format($row['room_price'], 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($row['total_price'], 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($row['sum_total'], 0, ',', '.') }}</td>
                    <td>{{ ucfirst($row['status']) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>