<?php
// Simple admin view to see all RSVPs
$csvFile = 'rsvp_data.csv';

if (!file_exists($csvFile)) {
    echo "<h2>No RSVP data found.</h2>";
    exit;
}

$data = [];
$file = fopen($csvFile, 'r');
if ($file) {
    $header = fgetcsv($file, 0, ',', '"', '\\');
    while (($row = fgetcsv($file, 0, ',', '"', '\\')) !== FALSE) {
        // Skip empty rows
        if (empty(array_filter($row))) {
            continue;
        }
        // Handle column count mismatch
        if (count($row) == count($header)) {
            // Perfect match
            $data[] = array_combine($header, $row);
        } elseif (count($row) > count($header)) {
            // More data columns than header - truncate data
            $truncatedRow = array_slice($row, 0, count($header));
            $data[] = array_combine($header, $truncatedRow);
        } else {
            // More header columns than data - pad data with empty strings
            $paddedRow = array_pad($row, count($header), '');
            $data[] = array_combine($header, $paddedRow);
        }
    }
    fclose($file);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RSVP Admin - View Responses</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin: 40px;
            background: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
        }
        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>RSVP Responses</h1>
        
        
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo count($data); ?></div>
                <div class="stat-label">Total Responses</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php 
                    $eventATotal = 0;
                    foreach ($data as $row) {
                        if ($row['Event A'] === 'Yes') {
                            $eventATotal += 1 + (int)$row['Event A Guests']; // Main attendee + guests
                        }
                    }
                    echo $eventATotal;
                ?></div>
                <div class="stat-label">Event A Attendees</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php 
                    $banquetTotal = 0;
                    foreach ($data as $row) {
                        if ($row['Banquet'] === 'Yes') {
                            $banquetTotal += 1 + (int)$row['Banquet Guests']; // Main attendee + guests
                        }
                    }
                    echo $banquetTotal;
                ?></div>
                <div class="stat-label">Banquet Attendees</div>
            </div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Event A</th>
                    <th>Event A Guests</th>
                    <th>Banquet</th>
                    <th>Banquet Guests</th>
                    <th>Banquet Guest Names</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['Timestamp']); ?></td>
                    <td><?php echo htmlspecialchars($row['Name']); ?></td>
                    <td><?php echo htmlspecialchars($row['Email']); ?></td>
                    <td><?php echo htmlspecialchars($row['Event A']); ?></td>
                    <td><?php echo htmlspecialchars($row['Event A Guests']); ?></td>
                    <td><?php echo htmlspecialchars($row['Banquet']); ?></td>
                    <td><?php echo htmlspecialchars($row['Banquet Guests']); ?></td>
                    <td><?php echo htmlspecialchars(isset($row['Banquet Guest Names']) ? $row['Banquet Guest Names'] : ''); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>