<?php
 
   require_once 'include/DB_Functions.php';
    $db = new DB_Functions();
 
  // json response array
     $response = array("error" => FALSE);
	 
	 if (isset($_GET['driver_id'])&& isset($_GET['current_lat'])&& isset($_GET['current_long']))
	 {
	    $driver_id = $_GET['driver_id'];
		$current_lat = $_GET['current_lat'];
		$current_long = $_GET['current_long'];
		
		if ($db->updateLatLong($driver_id, $current_lat, $current_long))
		{  
		    $response["error"] = (object)array();
            $response["status"] = "Success";
            $response["code"] = "200";
			$response["message"] = "Location updated";
			echo json_encode($response);
		}
		else {
        	// user is not found with the given credentials
        	$response["status"] = "Failed";
			$response["error"]["error_code"] = "406";
			$response["error"]["error_msg"] = "One or more parameters is not correct. Please try again!";
			$response["user"] = (object)array();
			echo json_encode($response);
  	  }
	}
	else {
    //required  params is missing
		$response["status"] = "Failed";
	    $response["error_code"]["error"] = "400";
	    $response["error"]["error_msg"] = "Required parameters are missing!";
	    $response["user"] = (object)array();
	    echo json_encode($response);
            }
?>
