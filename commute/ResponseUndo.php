<?php
 
require_once 'include/DB_Functions.php';
$db = new DB_Functions();
$response = array("error" => FALSE);
 
// json response array 
$jsonArr=json_decode(file_get_contents('php://input'),true);

if (isset($jsonArr['employee_id']) && isset($jsonArr['requestee_id'])) {
 
    // receiving the params
    $employee_id= $jsonArr['employee_id'];
    $requestee_id = $jsonArr['requestee_id'];

    $result = $db->undoSentRequest($employee_id, $requestee_id);
    
 
    if($result){

        $response["status"] = "Success";
        $response["code"] = "200";

        $response["message"] = "Undo successful !"; 
        $response["error"] = (object)array(); 
       echo json_encode($response);
    }
    else{
            $response["status"] = "Failed";
            $response["error"]["error_code"] = "304" ;
            $response["error"]["error_msg"] = "Could not undo the request !";
            echo json_encode($response);
    }
  }

    else{
    $response["status"] = "Failed";
    $response["error"]["error_code"] = "400" ;    
    $response["error"]["error_msg"] = "Required parameter (employee_id or requestee_id) is missing!";
    echo json_encode($response);
    }
?>
