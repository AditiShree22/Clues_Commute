<?php
 
require_once 'include/DB_Functions.php';

$db = new DB_Functions();

$response = array("error" => FALSE);

// json response array 
if (isset($_GET['employee_id'])) {
 
    $employee_id= $_GET['employee_id'];
    
    $result = $db->fetchOnePassengerFromRoute($employee_id);

    //print_r($result);
    if($result){
        $array = ($db->fetchFromMain($result));

        //FETCHING DRIVER LAT LONG FROM ROUTES
        $driver = $db->fetchFromRoute($employee_id);
        //print_r($driver);

        $array['driver_latitude'] = $driver['d_latitude'];
        $array['driver_longitude'] = $driver['d_longitude'];

       // $response['response'] = $array;
        echo(json_encode($array));

        $db->updateDriverLatLong($employee_id, $array['latitude'], $array['longitude']);
    	
    }
    else{
        $response["error"] = TRUE;
            $response["error_msg"] = "No Next Passenger";
            echo json_encode($response);
    }
    }

    else{
    
	    $response["error"] = TRUE;
	    $response["error_msg"] = "Required parameter (employee_id) is missing!";
	    echo json_encode($response);
	    }
?>