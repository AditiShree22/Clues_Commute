<?php
require_once 'include/DB_Functions.php';
    $db = new DB_Functions();
 
  // json response array
     $response = array("error" => FALSE);
	
	if (isset($_GET['employee_id'])){
	    
		$employee_id = $_GET['employee_id'];
		
		$passengerArray = $db->fetchForPassengers($route);
		$number = count($passengerArray);
		  
		 if ($passengerArray != false) {
			 
		   $response["error"] = FALSE;
		   for ($i=0; $i< $number; $i++){
		   
		     $response["route"][$i]= array("driver_id"=>$passengerArray[$i][0],"current_latitude"=>$passengerArray[$i][1],"current_longitude"=>$passengerArray[$i][2]);
		}	 
            echo json_encode($response);die;
     } 
    else {
        // user is not found with the credentials
        $response["error"] = TRUE;
        $response["error_msg"] = "Route not correct. Please try again!";
        echo json_encode($response);
    }
        }
		else {
    // required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameter is missing!";
    echo json_encode($response);
}
?>
