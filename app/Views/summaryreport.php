<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Summary Reports</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #DAD2D8;
            margin: 20px;
        }
        /* Additional styles can be added here to match the admin panel */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border: 2px solid;
        }
        th, td {
            border: 2px solid black;
            padding: 10px;
            text-align: center;
        }
        tr:hover {background-color: #F2AF29;}
        th {
            background-color: black;
            color: white;
        }
        .button-container {
            margin-bottom: 3px;
            display: flex;
            justify-content: flex-end;
            gap: 10px; 
        }
        .back {
            background-color: red;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            border-radius: 0.5rem;
        }
        .filter-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .filter-container select {
            padding: 10px;
            margin-left: 10px;
            background-color: #F0F6F6;
            color: black;
            border: none;
            cursor: pointer;
        }
        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            list-style: none;
            gap:10px;
        }
        .pagination button {
            padding: 10px;
            border: none;
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }
        .report-buttons {
            display: flex;
            justify-content: center;
            margin-top: 10px;
            gap: 10px;
        }
        .report-buttons button {
            padding: 10px;
            border: none;
            background-color:rgba(65, 67, 97, 0.87);
            color: white;
            cursor: pointer;
            border-radius: 0.5rem;  
        }
    </style>
</head>
<body>
<div>
    <div class="filter-container">
        <button class="back" onclick="window.location.href='/'">Back</button>
        <select onchange="location = this.value;">
            <option value="">Select Filter</option>
            <option value="/processSummary">Process Report</option>
            <option value="/campaignSummary">Campaign Report</option>
            <option value="/agentSummary">Agent Report</option>
        </select>
    </div>
    <h2 style="text-align: center;">Summary Report</h2>
    
    <div class="report-buttons">
        <button onclick="window.location.href='/summarysql'">SQL Summary Report</button>
        <button onclick="window.location.href='/summarymongo'">Mongo Summary Reports</button>
        <button onclick="window.location.href='/summaryelastic'">Elastic Summary Reports</button>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Total Calls</th>
                <th>Call Hours</th>
                <th>Missed Calls</th>
                <th>Call Answered</th>
                <th>Call Auto Drop</th>
                <th>Call Auto Failed</th>
                <th>Talk Time</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($summaryreport as $report): ?>
            <tr>
                <td><?php echo $report['TotalCalls']; ?></td>
                <td><?php echo $report['CallHours']; ?></td>
                <td><?php echo $report['MissedCalls']; ?></td>
                <td><?php echo $report['CallAnswered']; ?></td>
                <td><?php echo $report['CallAutofailed']; ?></td>
                <td><?php echo $report['CallAutodrop']; ?></td>
                <td><?php echo $report['Talktime']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div class="report-buttons">
        <button onclick="window.location.href='/summarysql/downloads'">Download SQL Summary</button>
        <button onclick="window.location.href='/summarymongo/downloads'">Download Mongo Summary</button>
        <button onclick="window.location.href='/summaryelastic/downloads'">Download Elastic Summary</button>
    </div>

    <div class="pagination">
        <?php echo $pager; ?>
    </div>
</div>
</body>
</html>
