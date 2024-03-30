<?php

require_once 'configurations/db_config.php';
require_once 'configurations/service_config.php';
require_once 'configurations/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendJsonResponse(405, $response_messages['method_not_allowed']);
}


$data = ['restaurant_id' => $_GET['restaurant_id']]; 

$validationError = validateData($data, 'find_nearest_rider'); //validate request data
if ($validationError) {
    sendJsonResponse(400, $validationError);
}


$db_conn = connectToDB($db_host, $db_user, $db_pass, $db_name);

$restaurant_id = $data['restaurant_id'];
$five_minutes_past = date('Y-m-d H:i:s', strtotime('-5 minutes')); //find 5 minutes earlier time

$restaurant_query = "SELECT lat, lng FROM restaurants WHERE id = $restaurant_id"; //fetch restaurant latitude and longitude
$restaurant_result = mysqli_query($db_conn, $restaurant_query);

if (!$restaurant_result || mysqli_num_rows($restaurant_result) == 0) {
    mysqli_close($db_conn);
    sendJsonResponse(404, $response_messages['restaurant_not_found']);
}

$restaurant_data = mysqli_fetch_assoc($restaurant_result);
$restaurant_lat = $restaurant_data['lat'];
$restaurant_lng = $restaurant_data['lng'];

//fetch nearest rider ; Haversine formula to calculate the distance between the restaurant and the rider's location
$sql = "SELECT r.name, r.contact_number, 
    CONCAT(ROUND(( 6371 * acos( cos( radians($restaurant_lat) ) * cos( radians( rl.lat ) ) 
    * cos( radians( rl.lng ) - radians($restaurant_lng) ) + sin( radians($restaurant_lat) ) 
    * sin( radians( rl.lat ) ) )), 3), ' km') AS distance
    FROM rider_location rl
    INNER JOIN riders r ON rl.rider_id = r.id
    WHERE rl.capture_time >= '$five_minutes_past'
    ORDER BY distance
    LIMIT 1";

$result = mysqli_query($db_conn, $sql);

if (!$result) {
    mysqli_close($db_conn);
    sendJsonResponse(500, $response_messages['unexpected_error']);
}

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    sendJsonResponse(200, $response_messages['rider_found'], $row); //sends rider info as json response
} else {
    sendJsonResponse(404, $response_messages['rider_not_found']);
}


mysqli_close($db_conn);


?>
