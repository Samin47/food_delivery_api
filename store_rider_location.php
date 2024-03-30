<?php

require_once 'configurations/db_config.php';
require_once 'configurations/service_config.php';
require_once 'configurations/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(405, $response_messages['method_not_allowed']);
}


$data = json_decode(file_get_contents('php://input'), true);

$validationError = validateData($data, 'store_rider_location'); //validate request data
if ($validationError) {
    sendJsonResponse(400, $validationError);
}

$db_conn = connectToDB($db_host, $db_user, $db_pass, $db_name);

$rider_id = $data['rider_id'];
$lat = $data['lat'];
$lng = $data['lng'];
$capture_time = $data['capture_time'];
$service_name = $data['service_name'] ?? 'NULL';


//store rider location data
$sql = "INSERT INTO rider_location (rider_id, lat, lng, capture_time, `service_name`) VALUES ($rider_id, $lat, $lng, '$capture_time', '$service_name')";

if (mysqli_query($db_conn, $sql)) {
    sendJsonResponse(201, $response_messages['rider_location_stored']);
} else {
    sendJsonResponse(500, $response_messages['unexpected_error']);
}

mysqli_close($db_conn);