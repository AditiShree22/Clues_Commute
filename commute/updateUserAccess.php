<?php
 
 //API to change login type of user...can be driver or passenger 

require_once 'include/DB_Functions.php';
$db = new DB_Functions();
 
// json response array
$response = array("error" => FALSE);
 
if (isset($_GET['employee_id']) && !empty($_GET['employee_id']) && isset($_GET['access_as']) && !empty($_GET['access_as']) && ($_GET['access_as']==1 || $_GET['access_as']==0)) {
 
    // receiving the params
    $employee_id= $_GET['employee_id'];
    $access_as = $_GET['access_as'];
 
    if ($db->updateAccess($employee_id, $access_as)) {
        $str = "User's Access Successfully Updated as ";
        if ($access_as==1)
            $str .="Driver";
        else
            $str .="Passenger";
        
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
    $response["error"]["error_msg"] = "Bad request : Required parameters (employee_id or password) is missing!";
    $response["response"] = (object)array();
    echo json_encode($response);
}
?>
