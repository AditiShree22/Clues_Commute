<?php

 // API for driver to create a new ride with specific origin and destination

   require_once 'include/DB_Functions.php';
   
    $db = new DB_Functions();
 
  // json response array
     $response = array("error" => FALSE);

     $jsonArr=json_decode(file_get_contents('php://input'),true);
	//print_r($jsonArr);
	 if (isset($jsonArr['employee_id'])&& !empty($jsonArr['employee_id']) && isset($jsonArr['source']) && !empty($jsonArr['source']) && isset($jsonArr['destination'])&& !empty($jsonArr['destination']) && isset($jsonArr['vehicle_type'])&& !empty($jsonArr['vehicle_type']) && is_numeric($jsonArr['vehicle_type']) && isset($jsonArr['vehicle_number']) && !empty($jsonArr['vehicle_number']) && isset($jsonArr['number_of_seats']) && !empty($jsonArr['number_of_seats']) && is_numeric($jsonArr['number_of_seats']) && isset($jsonArr['date']) && !empty($jsonArr['date']) && isset($jsonArr['time']))
	 {
	 	$employee_id = $jsonArr['employee_id'];
	     $source = $jsonArr['source'];
		  $destination = $jsonArr['destination'];
		   $vehicle_type = $jsonArr['vehicle_type'];
		    $vehicle_number = $jsonArr['vehicle_number'];
		     $number_of_seats = $jsonArr['number_of_seats'];
		      $created_at = $jsonArr['date'].' '.$jsonArr['time'];
		     
//CALLING MAPS API FOR GEOCODE
		/*$source_points = $db->getLatLong($source);
		 $source_latitude =  $source_points->results[0]->geometry->location->lat;
         $source_longitude = $source_points->results[0]->geometry->location->lng;
		 $dest_points = $db->getLatLong($destination);
		 $dest_latitude = $dest_points->results[0]->geometry->location->lat;
		 $dest_longitude = $dest_points->results[0]->geometry->location->lng; */	
		//$intermediate_points = $db->getIntermediatePoints($source_latitude, $source_longitude, $dest_latitude, $dest_longitude);

//CALLING MAPS API FOR INTERMEDIATE POINTS
	 $source1 = str_replace(' ', '', $source);
	 $destination1 = str_replace(' ', '', $destination);
	
     $intermediate_points = $db->getIntermediatePoints($source1, $destination1);
     $coordinates = $intermediate_points['routes'][0]['legs'][0]['steps'];
     
        for ($i=0; $i < count($coordinates); $i++) {

            	$result[$i] = $coordinates[$i]['start_location'];
            }
          
     $result_set = json_encode($result);
     $ride_name = $source." - ".$destination;

	 if ($db->createDriverRide($employee_id, $source, $destination, $ride_name, $result_set, $vehicle_type, $vehicle_number, $number_of_seats, $created_at))
		{   
             $response["status"] = "Success";
             $response["code"] = "200";
             $response["message"] = "You have created a new ride"; 
			 $response["response"] = "Ride created";
			 $response["error"] = (object)array();
			echo json_encode($response);
		}
		else {
        	// user is not found with the given credentials
        	$response["status"] = "Failed";
			$response["error"]["error_code"] = "406";
			$response["error"]["error_msg"] = "One or more parameters is not correct. Please try again!";
			$response["response"] = (object)array();
			echo json_encode($response);
  	  }
	}
	else {
    //required  params is missing
		$response["status"] = "Failed";
	    $response["error"]["error_code"] = "400";
	    $response["error"]["error_msg"] = "Bad Request : Required parameters are missing!";
	    $response["response"] = (object)array();
	    echo json_encode($response);
            }
?>
