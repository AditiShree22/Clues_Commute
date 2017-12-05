<?php
 
 //API to change login type of user...can be driver or passenger 

require_once 'include/DB_Functions.php';
$db = new DB_Functions();
 
// json response array
$response = array("error" => FALSE);
$jsonArr=json_decode(file_get_contents('php://input'),true);
 
if (isset($jsonArr['employee_id']) && !empty($jsonArr['employee_id']) /*&& isset($jsonArr['status']) && is_numeric($jsonArr['status']) && ($jsonArr['status']==0)*/) {
 
    // receiving the params
    $employee_id= $jsonArr['employee_id'];
    $status = 0 ;
   // $status = $jsonArr['status'];
 
    if ($db->logOut($employee_id)) {
        
        $response["status"] = "Success";
        $response["code"] = "200";
        $response["response"] = "User is logged out successfuly ";
        $response["error"] = (object)array();
        echo json_encode($response);
    } 

    else {
            // user failed to store
            $response["status"] = "Failed";
            $response["error"]["error_code"] = "409";
            $response["error"]["error_msg"] = "Conflict : Logout Unsuccessful!";
            $response["response"] = (object)array();
            echo json_encode($response);
        }
    }
else {
   // $response["error"] = TRUE;
    $response["status"] = "Failed";
    $response["error"]["error_code"] = "400" ;
    $response["error"]["error_msg"] = "Bad request : Required parameters (employee_id or password) is missing!";
    $response["response"] = (object)array();
    echo json_encode($response);
}
?>
