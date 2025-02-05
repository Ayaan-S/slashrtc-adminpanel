<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportModel extends Model
{
    protected $table = 'reportdata'; // Assuming the table name is 'reports'
    protected $primaryKey = 'id'; // Assuming 'id' is the primary key
    protected $allowedFields = [
        'dateTime', 'reportType', 'disposeType', 'disposeName', 
        'agentName', 'campaignName', 'processName', 'leadsetid', 
        'referenceuuid', 'customeruuid', 'hold', 'mute', 
        'ringing', 'transfer', 'conference', 'callKey', 
        'duration', 'disposeTime'
    ];

    public function getSummarizedReports()
    {
        return $this->findAll(); // Fetch all records from the reports table
    }

    public function getMySQLReports()
    {
        // Fetch MySQL report data
        return $this->findAll(); // Adjust this query as needed for your MySQL data
    }

    public function getMongoReports()
    {
        // Fetch MongoDB report data
        // Assuming you have a MongoDB connection set up
        $mongoClient = new \MongoDB\Client("mongodb://localhost:27017");
        $collection = $mongoClient->yourDatabase->yourCollection; // Adjust database and collection names
        return $collection->find()->toArray(); // Fetch all documents
    }

    public function getElasticReports()
    {
        // Fetch Elasticsearch report data
        // Assuming you have an Elasticsearch client set up
        $client = \Elasticsearch\ClientBuilder::create()->build();
        $params = [
            'index' => 'your_index', // Adjust index name
            'body' => [
                'query' => [
                    'match_all' => (object)[] // Adjust query as needed
                ]
            ]
        ];
        $response = $client->search($params);
        return $response['hits']['hits']; // Adjust to return the desired data structure
    }
}
