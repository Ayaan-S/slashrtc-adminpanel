<?php 
@include_once('header.php')
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Logger Reports</title>
<style>
    /* body {
        font-family: Arial, sans-serif;
        background-color: #DAD2D8;
        margin: 20px;
    } */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(65, 67, 97, 0.87);
        padding-top: 60px;
    }
    .modal-content {
        background-color: #fefefe;
        margin: 5% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        border-radius: 10px;
    }
    /* .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }
    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    } */
    /* table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        border: 2px solid;
    } */
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
    }  */
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

<div class="header">
    <button class="back" onclick="window.location.href='/'">Back</button>
    <button onclick="openFilterModal()">Click To Filter </button>
    <h1 style="text-align: center; font-size:2.5rem; font-weight:500;">LOGGER REPORTS</h1>
</div>

<div id="filterModal" class="modal">
    <div class="modal-content">
            <div>
                <form action="/sqlreports/filter" method="getVar">
                <label for="filterDisposeType">Dispose Type</label>
                <select id="filterDisposeType" name="disposeType">
                    <option value="">Select Dispose Type</option>
                    <option value="dnc">DNC</option>
                    <option value="etx">ETX</option>
                    <option value="Callback">Callback</option>
                </select>
            </div>
            <div>
                <label for="filterReportType">Report Type</label>
                <select id="filterReportType" name="reportType">
                    <option value="">Select Report Type</option>
                    <option value="autodrop">Auto Drop</option>
                    <option value="missed">Missed</option>
                    <option value="autofailed">Auto Failed</option>
                    <option value="dispose">Dispose</option>
                </select>
            </div>
            <div>
                <label for="filterCampaigns">Campaign Name</label>
                <input type="text" id="filterCampaigns" name="campaignName">
            </div>
        </div>
        <button type="submit" class="apply-button">Apply Filters</button>
    </div>
</div>
</form>
<div class="report-buttons">
        <button class="btn btn-primary" onclick="window.location.href='/sqlreports'">MySQL Reports</button>
        <button class="btn btn-primary" onclick="window.location.href='/mongoreports'">Mongo Reports</button>
        <button class="btn btn-primary" onclick="window.location.href='/elasticreports'">Elastic Reports</button>
    </div>
    
    <table>
        <thead>
            <tr class="bg-gray-500 text-white">
                <th class="py-3 px-4 border-b border-gray-300">Date Time</th>
                <th class="py-3 px-4 border-b border-gray-300">Report Type</th>
                <th class="py-3 px-4 border-b border-gray-300">Dispose Type</th>
                <th class="py-3 px-4 border-b border-gray-300">Dispose Name</th>
                <th class="py-3 px-4 border-b border-gray-300">Agent Name</th>
                <th class="py-3 px-4 border-b border-gray-300">Campaign Name</th>
                <th class="py-3 px-4 border-b border-gray-300">Process Name</th>
                <th class="py-3 px-4 border-b border-gray-300">Leadset ID</th>
                <th class="py-3 px-4 border-b border-gray-300">Reference UUID</th>
                <th class="py-3 px-4 border-b border-gray-300">Customer UUID</th>
                <th class="py-3 px-4 border-b border-gray-300">Hold</th>
                <th class="py-3 px-4 border-b border-gray-300">Mute</th>
                <th class="py-3 px-4 border-b border-gray-300">Ringing</th>
                <th class="py-3 px-4 border-b border-gray-300">Transfer</th>
                <th class="py-3 px-4 border-b border-gray-300">Conference</th>
                <th class="py-3 px-4 border-b border-gray-300">Call Key</th>
                <th class="py-3 px-4 border-b border-gray-300">Duration</th>
                <th class="py-3 px-4 border-b border-gray-300">Dispose Time</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reports as $report): ?>
                <tr>
                    <td><?php echo $report['dateTime'] ?></td>
                    <td><?php echo $report['reportType'] ?></td>
                    <td><?php echo $report['disposeType'] ?></td>
                    <td><?php echo $report['disposeName'] ?></td>
                    <td><?php echo $report['agentName'] ?></td>
                    <td><?php echo $report['campaignName'] ?></td>
                    <td><?php echo $report['processName'] ?></td>
                    <td><?php echo $report['leadsetid'] ?></td>
                    <td><?php echo $report['referenceuuid'] ?></td>
                    <td><?php echo $report['customeruuid'] ?></td>
                    <td><?php echo gmdate('H:i:s', $report['hold']) ?></td>
                    <td><?php echo gmdate('H:i:s', $report['mute']) ?></td>
                    <td><?php echo gmdate('H:i:s', $report['ringing']) ?></td>
                    <td><?php echo gmdate('H:i:s', $report['transfer']) ?></td>
                    <td><?php echo gmdate('H:i:s', $report['conference']) ?></td>
                    <td><?php echo $report['callKey'] ?></td>
                    <td><?php echo gmdate('H:i:s', $report['duration']) ?></td>
                    <td><?php echo gmdate('H:i:s', $report['disposeTime']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="report-buttons">
        <button class="btn" onclick="window.location.href='<?= '/sqlreports/downloads' ?>'">Download SQL CSV</button>
        <button class="btn" onclick="window.location.href='<?= '/mongoreports/downloads' ?>'">Download MongoDB CSV</button>
        <button class="btn" onclick="window.location.href='<?= '/elasticreports/downloads' ?>'">Download Elastic CSV</button> 
    </div>
    <div class="pagination">
        <?= $pager ?>
    </div>

<script>
    function openFilterModal() {
        document.getElementById('filterModal').style.display = 'block';
    }

    function closeFilterModal() {
        document.getElementById('filterModal').style.display = 'none';
    }

    function applyFilters() {
        const disposeType = document.getElementById('filterDisposeType').value;
        const reportType = document.getElementById('filterReportType').value;
        
        const campaignName = document.getElementById('filterCampaigns').value;
        // Logic to filter the data based on selected values
        refreshReportTable(disposeType, reportType,campaignName);
        closeFilterModal();
    }

    function refreshReportTable( disposeType, reportType, campaignName) {
        // Assuming 'reports' is an array of report objects available in the scope
        const filteredReports = reports.filter(report => {
            const matchesDisposeType = disposeType ? report.disposeType === disposeType : true;
            const matchesReportType = reportType ? report.reportType === reportType : true;
            const matchesCampaign = campaignName ? report.campaignName.includes(campaignName) : true;

            return matchesCampaign && matchesDisposeType && matchesReportType;
        });

        // Update the table with filtered reports
        const tbody = document.querySelector('tbody');
        tbody.innerHTML = ''; // Clear existing rows

        filteredReports.forEach(report => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${report.dateTime}</td>
                <td>${report.reportType}</td>
                <td>${report.disposeType}</td>
                <td>${report.disposeName}</td>
                <td>${report.agentName}</td>
                <td>${report.campaignName}</td>
                <td>${report.processName}</td>
                <td>${report.leadsetid}</td>
                <td>${report.referenceuuid}</td>
                <td>${report.customeruuid}</td>
                <td>${gmdate('H:i:s', report.hold)}</td>
                <td>${gmdate('H:i:s', report.mute)}</td>
                <td>${gmdate('H:i:s', report.ringing)}</td>
                <td>${gmdate('H:i:s', report.transfer)}</td>
                <td>${gmdate('H:i:s', report.conference)}</td>
                <td>${report.callKey}</td>
                <td>${gmdate('H:i:s', report.duration)}</td>
                <td>${gmdate('H:i:s', report.disposeTime)}</td>
            `;
            tbody.appendChild(row);
        });
    }
</script>

</body>
</html>
