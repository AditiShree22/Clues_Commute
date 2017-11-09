<?php
 
require_once 'include/DB_Functions.php';
$db = new DB_Functions();

$response = array("error" => FALSE);

// json response array 
if (isset($_GET['employee_id'])) {
 
    // receiving the post params
    $employee_id= $_GET['employee_id'];
    // $password = $_GET['password'];

    //echo ($employee_id);

    $result = $db->sentRequests($employee_id);

    //print_r($result);
    if ($result){
        for ($i=0; $i<count($result); $i++)
        {

            $response["response"][$i] = array("employee_id"=>$result[$i]['employee_id'],"employee_name"=>$result[$i]['employee_name'],"address"=>$result[$i]['address'],"mobile_number"=>$result[$i]['mobile_number'],"latitude"=>$result[$i]['latitude'], "longitude"=>$result[$i]['longitude']);
            

        }
    echo json_encode($response);
    }
    else{
        $response["error"] = TRUE;
            $response["error_msg"] = "No Request Sent";
            echo json_encode($response);
    }
    }

    else{
        $response["error"] = TRUE;
    $response["error_msg"] = "Required parameter (employee_id) is missing!";
    echo json_encode($response);
    }
?>