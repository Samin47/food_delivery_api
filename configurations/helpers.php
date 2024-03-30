<?php

function validateData($data, $endpoint) { //validate request data for all endpoints
    $validations = [
        'store_rider_location' => [
            'rider_id' => function($value) {
                return isset($value) && is_numeric($value);
            },
            'lat' => function($value) {
                return isset($value) && is_numeric($value) && $value >= -90 && $value <= 90;
            },
            'lng' => function($value) {
                return isset($value) && is_numeric($value) && $value >= -180 && $value <= 180;
            },
            'capture_time' => function($value) {
                return isset($value) && strtotime($value);
            }
        ],
        'find_nearest_rider' => [
            'restaurant_id' => function($value) {
                return isset($value) && is_numeric($value);
            }
        ]
    ];


    foreach ($validations[$endpoint] as $param => $validation) {
        if (!isset($data[$param]) || !$validation($data[$param])) {
            return "Bad Request - Invalid or missing $param";
        }
    }

    return null;
}


function sendJsonResponse($statusCode, $message , $data = null) { //sets response status code and sends json, closes db connection
    global $db_conn;
    http_response_code($statusCode);
    if ($data) {
        echo json_encode(['message' => $message, 'data' => $data]);
    } else {
        echo json_encode(['message' => $message]);
    }
    if ($db_conn) {
        mysqli_close($db_conn);
    }
    exit;
}

function connectToDB($db_host, $db_user, $db_pass, $db_name) { //connects to database
    $db_conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
    if (!$db_conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    return $db_conn;
}

?>


