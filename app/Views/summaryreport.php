<!DOCTYPE html>
<?php 
@include_once('header.php')
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Summary Reports</title>
    <style>
        /* body {
            font-family: Arial, sans-serif;
            background-color: #DAD2D8;
            margin: 20px;
        } */
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
    tr:hover {
        background-color:rgba(187, 178, 178, 0.87);
    }
        /* th {
            background-color: black;
            color: white;
        } */
        .button-container {
        margin-bottom: 3px;
        display: flex;
        justify-content: flex-end;
        gap: 10px; 
    }
    .back {
       
        color: red;
        padding: 10px;
        border: none;
        cursor: pointer;
        border-radius: 0.5rem;
    } 
    .filter-container {
        display: flex;
        justify-content: center;
        margin-bottom: 10px;
        gap: 2px;
    }
    .filter-container select {
        padding: 10px;
        margin-left: 10px;
        background-color: rgba(65, 67, 97, 0.87);
        color: white; /* Adjusted text color */
        border: none;
        cursor: pointer;
        gap: 2px;
        border-radius: 0.5rem;
    } 
    .pagination {
        margin-top: 20px;
        display: flex;
        justify-content: center;
        list-style: none;
        gap: 10px;
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
        padding: 2px;
        border: none;
        /* background-color: rgba(65, 67, 97, 0.87); */
        color: black;
        cursor: pointer;
        font-size: 1.5rem;
        gap: 2rem;
        font-weight: 500;
    } 
    .filter-options {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .filter-options label {
        margin-bottom: 5px;
        font-weight: bold;
    }
    .filter-options select, .filter-options input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }
    .apply-button {
        background-color: #007bff;
        color: white;
        padding: 10px;
        border: none;
        cursor: pointer;
        border-radius: 0.5rem;
        text-align: center;
    }
    .apply-button:hover {
        background-color: #0056b3;
    }
    </style>
</head>
<body>
<div>
<div class="header">
    <button class="back" onclick="window.location.href='/'">Back</button>
    <h1 style="text-align: center; font-size:2.5rem; font-weight:500;">Summary Report</h1>
</div>
    <!-- <div class="filter-container">
        <button class="back" onclick="window.location.href='/'">Back</button>
        <select onchange="location = this.value;">
            <option value="">Select Filter</option>
            <option value="/processSummary">Process Report</option>
            <option value="/campaignSummary">Campaign Report</option>
            <option value="/agentSummary">Agent Report</option>
        </select>
    </div> -->
    
    <div class="report-buttons">
        <button onclick="window.location.href='/summarysql'">SQL Summary Report</button>
        <button onclick="window.location.href='/summarymongo'">Mongo Summary Reports</button>
        <button onclick="window.location.href='/summaryelastic'">Elastic Summary Reports</button>
    </div>
    
    <table>
        <thead>
            <tr class="bg-gray-500 text-white">
                <th class="py-3 px-4 border-b border-gray-300">Total Calls</th>
                <th class="py-3 px-4 border-b border-gray-300">Call Hours</th>
                <th class="py-3 px-4 border-b border-gray-300">Missed Calls</th>
                <th class="py-3 px-4 border-b border-gray-300">Call Answered</th>
                <th class="py-3 px-4 border-b border-gray-300">Call Auto Drop</th>
                <th class="py-3 px-4 border-b border-gray-300">Call Auto Failed</th>
                <th class="py-3 px-4 border-b border-gray-300">Talk Time</th>
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
