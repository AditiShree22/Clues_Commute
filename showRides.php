
<?php

// API to display all the rides created by driver

require_once 'include/DB_Functions.php';
$db = new DB_Functions();
 
// json response array
$response = array("error" => FALSE);

if (isset($_GET['employee_id']) && !empty($_GET['employee_id'])) {

	$employee_id = $_GET['employee_id'];

	$rides = $db->getRideByEmployeeId($employee_id);

	if ($rides != false) {
        // user is found
        $response["error"] = (object)array();
        $response["status"] = "Success";
        $response["code"] = "200";
        $response["message"] = "Your Rides";

        for ($i=0; $i<count($rides); $i++){


$response["response"][$i] = (object)array("Ride_no."=>$i+1,"Ride_id"=>$rides[$i][0],"Source"=>$rides[$i][2],"Destinaton"=>$rides[$i][3],"Ride_name"=>$rides[$i][4],"Vehicle_type"=>$rides[$i][6],"Vehicle_number"=>$rides[$i][7],"Available_seats"=>$rides[$i][8],"created_at"=>$rides[$i][9]); 
       
    }
        echo json_encode($response);

    } else {
        // user is not found with the credentials
        $response["status"] = "Failed";
        $response["error"]["error_code"] = "406" ;
        $response["error"]["error_msg"] = "One or more parameters is not correct. Please try again!";
        $response["rides"] = (object)array();
        echo json_encode($response);
    }
} else {
    // required post params is missing
    $response["status"] = "Failed";
    $response["error"]["error_code"] = "400" ;
    $response["error"]["error_msg"] = "Bad request : Required parameters employee Id is missing!";
    $response["rides"] = (object)array();
    echo json_encode($response);
}

?>
