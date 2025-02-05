
<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

//$routes->resource('admin',['namespace'=>'App\Controllers\Admin','controller'=>'AdminController']);

$routes->get('/', 'Home::index');
$routes->match(['GET','POST'],'/signup', 'Signup::signup');
$routes->match(['GET','POST'],'/login', 'Login::login');

$routes->match(['GET','POST'], '/logout', 'Logout::logout');
$routes->match(['GET','POST'], '/admin', 'Admin\AdminController::index');
$routes->match(['GET','POST'], '/updateUser', 'Admin\AdminController::updateUser');
$routes->match(['GET','POST'], '/deleteuser/(:any)', 'Admin\AdminController::deleteuser/$1');

$routes->match(['GET','POST'], '/campaign', 'Admin\CampaignController::index');
$routes->match(['GET','POST'], '/campaignupdateUser', 'Admin\CampaignController::updateUser');
$routes->match(['GET','POST'], '/campaigndeleteuser/(:any)', 'Admin\CampaignController::deleteuser/$1');
$routes->match(['GET','POST'], '/addcampaign', 'Admin\CampaignController::addCampaign');

$routes->get('/chat', 'ChatController::index');


// Logger Report Routes
$routes->match(['GET','POST'], '/sqlreports', 'ReportController::showReport');
$routes->match(['GET','POST'], '/sqlreports/downloads', 'ReportController::sqldownloadCsv');
$routes->get('/sqlreports/filter', 'ReportController::showReport');

$routes->match(['GET', 'POST'], '/mongoreports', 'ReportController::mongoreport');
$routes->match(['GET','POST'], '/mongoreports/downloads', 'ReportController::mongodownloadCsv');
$routes->match(['GET','POST'], '/mongoreports/filter', 'ReportController::mongofilter');

$routes->match(['GET', 'POST'], '/elasticreports', 'ReportController::elasticreport');
$routes->match(['GET','POST'], '/elasticreports/downloads', 'ReportController::elasticdownloadCsv');
$routes->match(['GET','POST'], '/elasticreports/filter', 'ReportController::elasticfilter');

// SummaryReports Routes
$routes->match(['GET','POST'], '/summarysql', 'SummaryController::sqlsummaryreport');
$routes->match(['GET','POST'], '/summarysql/downloads', 'SummaryController::sqlsummarydownloadCsv');

$routes->match(['GET','POST'], '/summarymongo', 'SummaryController::mongosummaryreport');
$routes->match(['GET','POST'], '/summarymongo/downloads', 'SummaryController::mongosummarydownloadCsv');

$routes->match(['GET','POST'], '/summaryelastic', 'SummaryController::elasticsummaryreport');
$routes->match(['GET','POST'], '/summaryelastic/downloads', 'SummaryController::elasticsummarydownloadCsv');
