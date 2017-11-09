<?php
 
require_once 'include/DB_Functions.php';
$db = new DB_Functions();
$response = array("error" => FALSE);
 
// json response array 
if (isset($_GET['employee_id']) && isset($_GET['requestee_id'])) {
 
    $employee_id= $_GET['employee_id'];
    $requestee_id = $_GET['requestee_id'];

    $result = $db->acceptRequest($employee_id, $requestee_id);

    if($result){

        $response["response"] = TRUE;
        echo json_encode($response);
    }
    else{
        $response["error"] = TRUE;
        $response["error_msg"] = "Could not accept the request";
        echo json_encode($response);
    }
  }

    else{
       $response["error"] = TRUE;
       $response["error_msg"] = "Required parameter (employee_id or requestee_id) is missing!";
       echo json_encode($response);
    }
?>
