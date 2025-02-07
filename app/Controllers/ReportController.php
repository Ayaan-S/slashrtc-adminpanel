<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class ReportController extends Controller
{
    public function showReport()
    {
        // API URL to fetch report data
        $apiUrl = 'http://192.168.0.139:3001/sql/getall';
        
        // Fetch data from the API
        $response = file_get_contents($apiUrl);
        
        // Check for errors in the response
        if ($response === false) {
            return redirect()->back()->with('error', 'Error fetching data from API.');
        }
    
        // Decode the JSON response
        $reportData = json_decode($response, true);
    
        // Check for JSON errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            return redirect()->back()->with('error', 'Error decoding JSON response.');
        }
    
        // Get filter values from the request
        $disposeType = $this->request->getVar('disposeType') ?? '';
        $reportType = $this->request->getVar('reportType') ?? '';
        $campaignName = $this->request->getVar('campaignName') ?? '';
    
        // Filter the reports
        $filteredReports = array_filter($reportData, function($report) use ($campaignName, $disposeType, $reportType) {
            $matchesDisposeType = $disposeType ? $report['disposeType'] === $disposeType : true;
            $matchesReportType = $reportType ? $report['reportType'] === $reportType : true;
            $matchesCampaign = $campaignName ? strpos($report['campaignName'], $campaignName) !== false : true;
            return $matchesCampaign && $matchesDisposeType && $matchesReportType;
        });
    
        // Pagination logic
        $pager = \Config\Services::pager();
        $page = $this->request->getVar('page') ?? 1;
        $perPage = 5; // Number of items per page
        $offset = ($page - 1) * $perPage;
        $pagedData = array_slice($filteredReports, $offset, $perPage);
    
        // Prepare data for the view
        $data = [
            'reports' => $pagedData,
            'pager' => $pager->makeLinks($page, $perPage, count($filteredReports), 'default_full'),
            'disposeType' => $disposeType,
            'reportType' => $reportType,
            'campaignName' => $campaignName
        ];
    
        // Load the view
        return view('reports', $data);
    }
    

    public function sqldownloadCSV()
    {
        // API URL to fetch report data
        $apiUrl = 'http://192.168.0.139:3001/sql/getall';

        // Initialize cURL
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute cURL request
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            return redirect()->back()->with('error', 'Error fetching data from API: ' . curl_error($ch));
        }

        // Close cURL session
        curl_close($ch);

        // Decode the JSON response
        $reportData= json_decode($response, true);

        // Check for JSON errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            return redirect()->back()->with('error', 'Error decoding JSON response.');
        }

        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="sqlreport.csv"');

        // Open output stream
        $output = fopen('php://output', 'w');

        // Write CSV headers
        fputcsv($output, [
            'Date Time', 'Report Type', 'Dispose Type', 'Dispose Name', 
            'Agent Name', 'Campaign Name', 'Process Name', 'Leadset ID', 
            'Reference UUID', 'Customer UUID', 'Hold', 'Mute', 'Ringing', 
            'Transfer', 'Conference', 'Call Key', 'Duration', 'Dispose Time'
        ]);

        // Write data rows
        foreach ($reportData as $report) {
            fputcsv($output, [
                $report['dateTime'], $report['reportType'], $report['disposeType'], 
                $report['disposeName'], $report['agentName'], $report['campaignName'], 
                $report['processName'], $report['leadsetid'], $report['referenceuuid'], 
                $report['customeruuid'], $report['hold'], $report['mute'], 
                $report['ringing'], $report['transfer'], $report['conference'], 
                $report['callKey'], $report['duration'], $report['disposeTime']
            ]);
        }

        // Close output stream
        fclose($output);
        exit;
    }
    public function mongoreport()
    {
        // API URL to fetch report data
        $apiUrl = 'http://192.168.0.139:3000/mongo/getall';

        // Initialize cURL
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute cURL request
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            return redirect()->back()->with('error', 'Error fetching data from API: ' . curl_error($ch));
        }

        // Close cURL session
        curl_close($ch);

        // Decode the JSON response
        $reportData = json_decode($response, true);
        

        // Check for JSON errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            return redirect()->back()->with('error', 'Error decoding JSON response.');
        }

        // Debugging: Log the structure of the report data
        log_message('debug', 'Report Data: ' . print_r($reportData['response'], true));

        // Ensure report data is an array
        if (!is_array($reportData)) {
            return redirect()->back()->with('error', 'Invalid data format received from API.');
        }

        // Check if the report data is an array of arrays
        if (isset($reportData[0]) && !is_array($reportData[0])) {
            return redirect()->back()->with('error', 'Expected an array of reports, but received a different format.');
        }

        // Pagination logic
        $pager = \Config\Services::pager();
        $page = $this->request->getVar('page') ?? 1;
        $perPage = 5; // Number of items per page
        $offset = ($page - 1) * $perPage;
        $pagedData = array_slice($reportData['response'], $offset, $perPage);

        // Prepare data for the view
        $data = [
            'reports' => $pagedData,
            'pager' => $pager->makeLinks($page, $perPage, count($reportData['response']), 'default_full')
        ];

        // Load the view
        return view('reports', $data);
    }

    public function mongodownloadCsv()
    {
        // API URL to fetch report data
        $apiUrl = 'http://192.168.0.139:3000/mongo/getall';

        // Initialize cURL
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute cURL request
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            return redirect()->back()->with('error', 'Error fetching data from API: ' . curl_error($ch));
        }

        // Close cURL session
        curl_close($ch);

        // Decode the JSON response
        $reportData= json_decode($response, true);

        // Check for JSON errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            return redirect()->back()->with('error', 'Error decoding JSON response.');
        }

        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="mongoreport.csv"');

        // Open output stream
        $output = fopen('php://output', 'w');

        // Write CSV headers
        fputcsv($output, [
            'Date Time', 'Report Type', 'Dispose Type', 'Dispose Name', 
            'Agent Name', 'Campaign Name', 'Process Name', 'Leadset ID', 
            'Reference UUID', 'Customer UUID', 'Hold', 'Mute', 'Ringing', 
            'Transfer', 'Conference', 'Call Key', 'Duration', 'Dispose Time'
        ]);

        // Write data rows
        foreach ($reportData['response'] as $report) {
            fputcsv($output, [
                $report['dateTime'], $report['reportType'], $report['disposeType'], 
                $report['disposeName'], $report['agentName'], $report['campaignName'], 
                $report['processName'], $report['leadsetid'], $report['referenceuuid'], 
                $report['customeruuid'], $report['hold'], $report['mute'], 
                $report['ringing'], $report['transfer'], $report['conference'], 
                $report['callKey'], $report['duration'], $report['disposeTime']
            ]);
        }

        // Close output stream
        fclose($output);
        exit;
    }

    public function elasticreport()
    {
        // API URL to fetch report data from Elasticsearch
        $apiUrl = 'http://192.168.0.139:3002/elastic/getall';

        // Initialize cURL
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Execute cURL request
        $response = curl_exec($ch);
        
        // Check for cURL errors
        if (curl_errno($ch)) {
            return redirect()->back()->with('error', 'Error fetching data from API: ' . curl_error($ch));
        }
        
        // Close cURL session
        curl_close($ch);
        $data = [];
        // Decode the JSON response
        $reportData = json_decode($response, true);
        
        // Check for JSON errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            return redirect()->back()->with('error', 'Error decoding JSON response.');
        }

        // Pagination logic
        $pager = \Config\Services::pager();
        $page = $this->request->getVar('page') ?? 1;
        $perPage = 5; // Number of items per page
        $offset = ($page - 1) * $perPage;
        $pagedData = array_slice($reportData, $offset, $perPage);

        // Prepare data for the view
        $data = [
            'reports' => $pagedData,
            'pager' => $pager->makeLinks($page, $perPage, count($reportData), 'default_full')
        ];

        // Load the view
        return view('reports', $data);
    }

    public function elasticdownloadCsv()
    {
        // API URL to fetch report data from Elasticsearch
        $apiUrl = 'http://192.168.0.139:3002/elastic/getall';

        // Initialize cURL
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute cURL request
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            return redirect()->back()->with('error', 'Error fetching data from API: ' . curl_error($ch));
        }

        // Close cURL session
        curl_close($ch);

        // Decode the JSON response
        $reportData = json_decode($response, true);

        // Check for JSON errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            return redirect()->back()->with('error', 'Error decoding JSON response.');
        }

        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="elasticreport.csv"');

        // Open output stream
        $output = fopen('php://output', 'w');

        // Write CSV headers
        fputcsv($output, [
            'Date Time', 'Report Type', 'Dispose Type', 'Dispose Name', 
            'Agent Name', 'Campaign Name', 'Process Name', 'Leadset ID', 
            'Reference UUID', 'Customer UUID', 'Hold', 'Mute', 'Ringing', 
            'Transfer', 'Conference', 'Call Key', 'Duration', 'Dispose Time'
        ]);

        // Write data rows
        foreach ($reportData as $report) {
            fputcsv($output, [
                $report['dateTime'], $report['reportType'], $report['disposeType'], 
                $report['disposeName'], $report['agentName'], $report['campaignName'], 
                $report['processName'], $report['leadsetid'], $report['referenceuuid'], 
                $report['customeruuid'], $report['hold'], $report['mute'], 
                $report['ringing'], $report['transfer'], $report['conference'], 
                $report['callKey'], $report['duration'], $report['disposeTime']
            ]);
        }

        // Close output stream
        fclose($output);
        exit;
    } 

}