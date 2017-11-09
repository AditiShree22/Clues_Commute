<?php
 
require_once 'include/DB_Functions.php';
$db = new DB_Functions();
 
// json response array
$response = array("error" => FALSE);
 
if (isset($_GET['driver_id']) && isset($_GET['passenger_id'])) {
 
    // receiving the post params
    $driver_id= $_GET['driver_id'];
    $passenger_id = $_GET['passenger_id'];

    if ($db->updateStatus($driver_id, $passenger_id, 2)) {
     //DB updated   
     $response["response"] = TRUE;  
     echo json_encode($response);
    } 

    else {
            // Error in updation
            $response["error"] = TRUE;
            $response["error_msg"] = "Updation Unsuccessful!";
            echo json_encode($response);
        }
    }
else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters (driver_id or passenger_id) is missing!";
    echo json_encode($response);
}
?>
