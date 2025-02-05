<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class SummaryController extends BaseController
{
    public function sqlsummaryreport()
    {
        // Fetch data from MySQL without pagination
        $apiUrl = 'http://192.168.0.229:3001/sql/summary'; // Replace with the actual API URL
        
        // Fetch data from the API
        $response = file_get_contents($apiUrl);
        
        // Check for errors in the response
        if ($response === false) {
            return "Error fetching data from API.";
        }

        // Decode the JSON response
        $reportData = json_decode($response, true);
        
        // Check for JSON errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            return "Error decoding JSON response.";
        }

        // Apply filters if any
        $filters = $this->request->getPost();
        if (!empty($filters)) {
            $reportData = array_filter($reportData, function($report) use ($filters) {
                $matches = true;
                if (!empty($filters['filterCampaigns']) && stripos($report['Campaign'], $filters['filterCampaigns']) === false) {
                    $matches = false;
                }
                if (!empty($filters['filterProcess']) && $report['Role'] !== $filters['filterProcess']) {
                    $matches = false;
                }
                if (!empty($filters['filterDate']) && strtotime($report['Date']) < strtotime($filters['filterDate'])) {
                    $matches = false;
                }
                return $matches;
            });
        }

        // Pagination logic
        $pager = \Config\Services::pager(); 
        $page = $this->request->getVar('page') ? (int)$this->request->getVar('page') : 1; 
        $perPage = 10; 
        $offset = ($page - 1) * $perPage; 
        $totalRows = count($reportData); 
        $pagedData = array_slice($reportData, $offset, $perPage); 

        // Prepare data for the view
        $data = [
            'summaryreport' => $pagedData,
            'pager' => $pager->makeLinks($page, $perPage, $totalRows, 'default_full')
        ];

        // Load the view
        return view('summaryreport', $data);
    } 

    public function sqlsummarydownloadCsv()
    {
        $apiUrl = 'http://192.168.0.229:3001/sql/summary'; // Replace with the actual API URL
        $response = file_get_contents($apiUrl);
        $reportData = json_decode($response, true);

        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="summary_report.csv"');

        // Open output stream
        $output = fopen('php://output', 'w');

        // Add CSV header
        fputcsv($output, ['Campaign', 'Role', 'Date']); // Adjust headers as needed

        // Add data to CSV
        foreach ($reportData as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit();
    }

    public function mongosummaryreport()
    {
        // Fetch data from MongoDB API
        $apiUrl = 'http://192.168.0.229:3000/mongo/summary'; // Replace with your MongoDB API URL
        $response = file_get_contents($apiUrl);
        $reportData = json_decode($response, true);

        $pager = \Config\Services::pager(); 
        $page = $this->request->getVar('page') ? (int)$this->request->getVar('page') : 1; 
        $perPage = 10; 
        $offset = ($page - 1) * $perPage; 
        $totalRows = count($reportData); 
        $pagedData = array_slice($reportData['response'], $offset, $perPage); 

        // Prepare data for the view
        $data = [
            'summaryreport' => $pagedData,
            'pager' => $pager->makeLinks($page, $perPage, $totalRows, 'default_full')
        ];

        // Load the view
        return view('summaryreport', $data);
    }

    public function mongosummarydownloadCsv()
    {
        $apiUrl = 'http://192.168.0.229:3000/mongo/summary'; // Replace with your MongoDB API URL
        $response = file_get_contents($apiUrl);
        $reportData = json_decode($response, true);

        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="mongo_summary_report.csv"');

        // Open output stream
        $output = fopen('php://output', 'w');

        // Add CSV header
        fputcsv($output, ['Field1', 'Field2', 'Field3']); // Adjust headers as needed

        // Add data to CSV
        foreach ($reportData['response'] as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit();
    }

    public function elasticsummaryreport()
    {
        // Fetch data from Elasticsearch API
        $apiUrl = 'http://192.168.0.229:3002/elastic/summary'; // Replace with your Elasticsearch API URL
        $response = file_get_contents($apiUrl);
        $reportData = json_decode($response, true);
        // print_r($reportData); die;

        $pager = \Config\Services::pager(); 
        $page = $this->request->getVar('page') ? (int)$this->request->getVar('page') : 1; 
        $perPage = 10; 
        $offset = ($page - 1) * $perPage; 
        $totalRows = count($reportData); 
        $pagedData = array_slice($reportData, $offset, $perPage); 

        // Prepare data for the view
        $data = [
            'summaryreport' => $pagedData,
            'pager' => $pager->makeLinks($page, $perPage, $totalRows, 'default_full')
        ];

        // Load the view
        return view('summaryreport', $data);
    }

    public function elasticsummarydownloadCsv()
    {
        $apiUrl = 'http://192.168.0.229:3002/elastic/summary'; // Replace with your Elasticsearch API URL
        $response = file_get_contents($apiUrl);
        $reportData = json_decode($response, true);

        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="elastic_summary_report.csv"');

        // Open output stream
        $output = fopen('php://output', 'w');

        // Add CSV header
        fputcsv($output, ['Field1', 'Field2', 'Field3']); // Adjust headers as needed

        // Add data to CSV
        foreach ($reportData as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit();
    }
}
