<?php

 // API to enable and disable the existing rides created by driver

require_once 'include/DB_Functions.php';
$db = new DB_Functions();
 
// json response array
$response = array("error" => FALSE);
  $jsonArr=json_decode(file_get_contents('php://input'),true);
 
if (isset($jsonArr['ride_id']) && isset($jsonArr['active_status']) && ($jsonArr['active_status'])==1 || ($jsonArr['active_status']==0)) {
 //print_r($_GET['ride_id']);
 //print_r($_GET['active_status']);
    // receiving the params
    $id = $jsonArr['ride_id'];
    $active_status = $jsonArr['active_status'];
 
    if ($db->updateRideStatus($id, $active_status)) {
        $str = "Your Ride has been ";
        if ($active_status==1)
            $str .="enabled successfully";
        else if($active_status==0)
            $str .="disabled successfully";
        
        $response["status"] = "Success";
        $response["code"] = "200";
        $response["response"] = $str;
        $response["error"] = (object)array();
        echo json_encode($response);
    } 

    else {
            // user failed to store
            //$response["error"] = TRUE;
            $response["status"] = "Failed";
            $response["error"]["error_code"] = "409";
            $response["error"]["error_msg"] = "Conflict : Updation Unsuccessful!";
            $response["response"] = (object)array();
            echo json_encode($response);
        }
    }
else {
   // $response["error"] = TRUE;
    $response["status"] = "Failed";
    $response["error"]["error_code"] = "400" ;
    $response["error"]["error_msg"] = "Bad request : Required parameters (ride_id or active_status) is missing!";
    $response["response"] = (object)array();
    echo json_encode($response);
}
?>
