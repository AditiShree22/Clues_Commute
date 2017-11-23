<?php
 
require_once 'include/DB_Functions.php';
$db = new DB_Functions();

$response = array("error" => FALSE);

// json response array 
if (isset($_GET['employee_id']) && !empty($_GET['employee_id'])) {
 
    // receiving the post params
    $employee_id= $_GET['employee_id'];

    $result = $db->sentRequests($employee_id);

    //print_r($result);
    if ($result){
             $response["status"] = "Success";
             $response["code"] = "200";
             $response["message"] = "Booked rides"; 
             $response["error"] = (object)array();
        for ($i=0; $i<count($result); $i++)
        {

            $response["response"][$i] = (object)array("employee_id"=>$result[$i]['employee_id'],"employee_name"=>$result[$i]['employee_name'],"vehicle_number"=>$result[$i]['vehicle_number'],"mobile_number"=>$result[$i]['mobile_number']);
            

        }
    echo json_encode($response);
    }
    else{
    $response["status"] = "Failed";
    $response["error"]["error_code"] = "204" ;
    $response["error"]["error_msg"] = "No rides are booked. Please send a request!";
    $response["response"] = (object)array();
            echo json_encode($response);
    }
    }

    else{
    $response["status"] = "Failed";
    $response["error"]["error_code"] = "400" ;
    $response["error"]["error_msg"] = "Bad request : Required parameter is missing!";
    $response["response"] = (object)array();
    echo json_encode($response);
    }
?>
