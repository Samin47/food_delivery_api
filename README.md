# Food Delivery APIs

N.B: The project was done using raw PHP (7.4.1)

This repository contains two APIs for a food delivery system:

1. Store Rider Location API: This API allows storing the location of a rider. It accepts POST requests and stores the rider's location data in the database.

2. Find Nearest Rider API: This API helps find the nearest rider to a specified restaurant location. It accepts GET requests with the restaurant ID as a parameter and returns the information of the nearest rider within a certain time frame.

## API Endpoints

1. Store Rider Location API :
   
    *Endpoint: /store_rider_location
   
    *Method: POST
   
    *Request Body:
   
        rider_id: ID of the rider
        lat: Latitude of the rider's location
        lng: Longitude of the rider's location
        capture_time: Timestamp of when the rider's location was captured
        service_name (optional): Name of the service
  
2. Find Nearest Rider API :

    *Endpoint: /find_nearest_rider
   
    *Method: GET
   
    *Query Parameter:
   
        restaurant_id: ID of the restaurant
   
    *Response:
      If a nearby rider is found within the specified time frame, it returns the rider's information including name, contact number, and distance from the restaurant in kilometers.
      If no nearby rider is found, it returns an appropriate error message.


## Setup
      Clone the repository to your local machine.
      Configure the database settings in configurations/db_config.php.
      Ensure that PHP and MySQL are installed on your system.
      Start your local server.
      Populate necessary table data (riders and restaurants).
      You can now make requests to the API endpoints using tools like Postman.
## Dependencies
      PHP >= 7.0
      MySQL
