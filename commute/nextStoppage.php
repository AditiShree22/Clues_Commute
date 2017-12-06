<?php
 
require_once 'include/DB_Functions.php';

$db = new DB_Functions();

$response = array("error" => FALSE);

// json response array 
if (isset($_GET['employee_id']) && !empty($_GET['employee_id'])) {
 
    $employee_id= $_GET['employee_id'];
    //Fetching one stop according to waypoint sequence in DB
    $address = $db->fetchOneStopFromRoute($employee_id);
 
    if($address){
        // Converting passenger address picked from route to lat lng
    	$LatLng = $db->getLatLong($address);
        $latTo =  $LatLng['results'][0]['geometry']['location']['lat'];
        $longTo = $LatLng['results'][0]['geometry']['location']['lng'];
        //FETCHING DRIVER LAT LONG FROM ROUTES
    	$driver = $db->fetchFromRoute($employee_id);

        $response["error"] = (object)array();
        $response["status"] = "Success";
        $response["code"] = "200";
        $response["message"] = "Next Stoppage";

        $response['location_detail']['passenger_latitude'] = $latTo;
        $response['location_detail']['passenger_longitude'] = $longTo;
        $response['location_detail']['driver_latitude'] = $driver['d_latitude'];
        $response['location_detail']['driver_longitude'] = $driver['d_longitude'];
   
        echo(json_encode($response));

    	$db->updateDriverLatLong($employee_id, $latTo, $longTo);
    	}

    else{

    	    $response["status"] = "Failed";
            $response["error"]["error_code"] = " ";
            $response["error"]["error_msg"] = "No Next Passenger";
            $response["user"] = (object)array();
            echo json_encode($response);
    }
    }

    else{
        
          $response["status"] = "Failed";
	      $response["error"]["error_code"] = "400";
	      $response["error"]["error_msg"] = "Required parameter (employee_id) is missing!";
	      $response["user"] = (object)array();
	      echo json_encode($response);
	    }
?>